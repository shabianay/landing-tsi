<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** Default PPN rate (percent) for new invoices; changeable in Pengaturan invoice. */
    public const DEFAULT_PPN_RATE = 11.0;

    /** Largest amount the DECIMAL(15,2) money columns can hold. */
    public const MAX_AMOUNT = 9999999999999;

    protected $fillable = [
        'order_client_id', 'invoice_number', 'invoice_date', 'client_name', 'client_address',
        'notes', 'paid_amount', 'ppn_rate', 'ppn_amount', 'discount_amount', 'discount_type',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'paid_amount' => 'decimal:2',
            'ppn_rate' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(OrderClient::class, 'order_client_id');
    }

    /** PPN rate for new invoices, from the invoice settings. */
    public static function ppnRate(): float
    {
        $rate = Setting::get('invoice_ppn_rate');

        return is_numeric($rate) ? (float) $rate : self::DEFAULT_PPN_RATE;
    }

    /**
     * Amounts for a set of items. PPN is charged on the price after discount (DPP).
     *
     * @param  iterable<array{qty:int|float|string, price:int|float|string}>  $items
     * @return array{subtotal: float, discount: float, ppn: float, total: float}
     */
    public static function calculate(iterable $items, float $discount, string $discountType, bool $usePpn, float $ppnRate): array
    {
        $subtotal = (float) collect($items)->sum(fn ($i) => (float) $i['qty'] * (float) $i['price']);
        $discountValue = $discountType === 'percent' ? $subtotal * min($discount, 100) / 100 : $discount;
        $base = max(0, $subtotal - $discountValue);
        $ppn = $usePpn ? round($base * $ppnRate / 100, 2) : 0.0;

        return ['subtotal' => $subtotal, 'discount' => $discountValue, 'ppn' => $ppn, 'total' => $base + $ppn];
    }

    /** Next number in the studio's format TSI/MM/YYYY/NNN for the invoice's month. */
    public static function nextNumber(?\DateTimeInterface $date = null): string
    {
        $date ??= now();
        $prefix = sprintf('TSI/%s/%s/', $date->format('m'), $date->format('Y'));
        $last = static::where('invoice_number', 'like', $prefix . '%')
            ->pluck('invoice_number')
            ->map(fn ($n) => (int) substr($n, strlen($prefix)))
            ->max() ?? 0;

        return $prefix . str_pad((string) ($last + 1), 3, '0', STR_PAD_LEFT);
    }

    // --- SQL versions of the amounts, so lists and totals do not load every invoice ---

    private static function subtotalSql(): string
    {
        return '(SELECT COALESCE(SUM(invoice_items.item_qty * invoice_items.item_price), 0) FROM invoice_items WHERE invoice_items.invoice_id = invoices.id)';
    }

    public static function totalSql(): string
    {
        $s = self::subtotalSql();
        $raw = "({$s} + invoices.ppn_amount - (CASE WHEN invoices.discount_type = 'percent' THEN {$s} * invoices.discount_amount / 100 ELSE invoices.discount_amount END))";

        return "(CASE WHEN {$raw} < 0 THEN 0 ELSE {$raw} END)";
    }

    /** Money received, never more than the invoice total. */
    private static function paidSql(): string
    {
        $t = self::totalSql();

        return "(CASE WHEN invoices.paid_amount > {$t} THEN {$t} ELSE invoices.paid_amount END)";
    }

    public static function statusSql(): string
    {
        $t = self::totalSql();

        return "(CASE WHEN {$t} <= 0 OR invoices.paid_amount >= {$t} THEN 'paid' WHEN invoices.paid_amount > 0 THEN 'partial' ELSE 'unpaid' END)";
    }

    /** Adds amount_subtotal / amount_total columns so the accessors need no items query. */
    public function scopeWithAmounts(Builder $query): Builder
    {
        if ($query->getQuery()->columns === null) {
            $query->select('invoices.*');
        }

        return $query->selectRaw(self::subtotalSql() . ' AS amount_subtotal')
            ->selectRaw(self::totalSql() . ' AS amount_total');
    }

    public function scopePaymentStatus(Builder $query, string $status): Builder
    {
        return $query->whereRaw(self::statusSql() . ' = ?', [$status]);
    }

    /**
     * Totals for a (filtered) invoice query, computed in the database.
     * "Belum dibayar" counts only invoices with no payment yet; DP invoices are not included.
     *
     * @return array{total: float, paid: float, unpaid: array{total: float, count: int}}
     */
    public static function summarize(Builder $query): array
    {
        $t = self::totalSql();
        $status = self::statusSql();
        $row = (clone $query)->toBase()
            ->reorder()
            ->selectRaw("COALESCE(SUM({$t}), 0) AS total")
            ->selectRaw('COALESCE(SUM(' . self::paidSql() . '), 0) AS paid')
            ->selectRaw("COALESCE(SUM(CASE WHEN {$status} = 'unpaid' THEN {$t} ELSE 0 END), 0) AS unpaid_total")
            ->selectRaw("COALESCE(SUM(CASE WHEN {$status} = 'unpaid' THEN 1 ELSE 0 END), 0) AS unpaid_count")
            ->first();

        return [
            'total' => (float) $row->total,
            'paid' => (float) $row->paid,
            'unpaid' => ['total' => (float) $row->unpaid_total, 'count' => (int) $row->unpaid_count],
        ];
    }

    // --- Accessors ---

    public function getSubtotalAttribute(): float
    {
        if (! $this->relationLoaded('items') && array_key_exists('amount_subtotal', $this->attributes)) {
            return (float) $this->attributes['amount_subtotal'];
        }

        return (float) $this->items->sum(fn ($item) => $item->item_qty * (float) $item->item_price);
    }

    public function getCalculatedDiscountValueAttribute(): float
    {
        $discount = (float) $this->discount_amount;

        return $this->discount_type === 'percent' ? $this->subtotal * ($discount / 100) : $discount;
    }

    public function getTotalAttribute(): float
    {
        if (! $this->relationLoaded('items') && array_key_exists('amount_total', $this->attributes)) {
            return (float) $this->attributes['amount_total'];
        }

        return max(0, $this->subtotal + (float) $this->ppn_amount - $this->calculated_discount_value);
    }

    /** Money received, capped at the total (an overpayment is not counted twice). */
    public function getPaidCappedAttribute(): float
    {
        return min((float) $this->paid_amount, $this->total);
    }

    public function getRemainingAttribute(): float
    {
        return max(0, $this->total - (float) $this->paid_amount);
    }

    public function getStatusAttribute(): string
    {
        $total = $this->total;
        $paid = (float) $this->paid_amount;

        if ($total <= 0 || $paid >= $total) {
            return 'paid';
        }

        return $paid > 0 ? 'partial' : 'unpaid';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'Lunas',
            'partial' => 'DP',
            default => 'Belum Bayar',
        };
    }

    /** "10" or "11,5" for the PPN label; legacy invoices without a stored rate show 10. */
    public function getPpnRateLabelAttribute(): string
    {
        $rate = (float) ($this->ppn_rate ?: 10);

        return rtrim(rtrim(number_format($rate, 2, ',', '.'), '0'), ',');
    }
}
