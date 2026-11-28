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
        'order_client_id', 'order_id', 'invoice_number', 'invoice_date', 'client_name', 'client_address',
        'notes', 'paid_amount', 'ppn_rate', 'ppn_amount', 'discount_amount', 'discount_type',
        'settles_invoice_id', 'credit_amount',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'paid_amount' => 'decimal:2',
            'ppn_rate' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'credit_amount' => 'decimal:2',
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

    /** The client project this invoice bills for (optional). */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** For a pelunasan invoice: the DP invoice it settles. */
    public function settles(): BelongsTo
    {
        return $this->belongsTo(self::class, 'settles_invoice_id');
    }

    /** For a DP invoice: the pelunasan invoice that closed it. */
    public function settledBy(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(self::class, 'settles_invoice_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class)->orderBy('paid_at')->orderBy('id');
    }

    /**
     * Recompute paid_amount from the payment history, then let the linked project
     * pick up its DP / lunas dates.
     */
    public function syncPaidAmount(): void
    {
        $this->forceFill(['paid_amount' => (float) $this->payments()->sum('amount')])->save();
        $this->unsetRelation('payments');

        $this->order?->syncPaymentDatesFromInvoices();
    }

    // --- Client-facing link ---

    /** Secret link the client can open without logging in. Created on first use. */
    public function shareUrl(): string
    {
        if (! $this->share_token) {
            $this->forceFill(['share_token' => \Illuminate\Support\Str::random(40)])->save();
        }

        return route('invoices.public', $this->share_token);
    }

    /** Replace the link: the old one stops working. */
    public function resetShareToken(): void
    {
        $this->forceFill(['share_token' => \Illuminate\Support\Str::random(40)])->save();
    }

    /** wa.me link with a ready-to-send message (to the client's number when known). */
    public function whatsappUrl(?string $phone, string $companyName): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        $rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');
        $lines = [
            "Halo {$this->client_name},",
            '',
            "Berikut invoice {$this->invoice_number} dari {$companyName} sebesar {$rp($this->total)}.",
        ];
        if ($this->status === 'partial') {
            $lines[] = "Sudah dibayar {$rp($this->paid_amount)}, sisa {$rp($this->remaining)}.";
        } elseif ($this->status === 'paid') {
            $lines[] = 'Invoice ini sudah lunas. Terima kasih.';
        }
        $lines[] = '';
        $lines[] = 'Lihat atau unduh PDF: ' . $this->shareUrl();
        $lines[] = '';
        $lines[] = 'Terima kasih.';

        return 'https://wa.me/' . (strlen($digits) >= 10 ? $digits : '') . '?text=' . rawurlencode(implode("\n", $lines));
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

    /**
     * Create an invoice inside a transaction. Without a manual number, the number comes
     * from the invoice date; if two invoices grab the same number at once, the unique
     * index rejects one and the next number is tried.
     *
     * @param  callable(string $number): self  $create
     */
    public static function createNumbered(\DateTimeInterface $date, ?string $manualNumber, callable $create): self
    {
        for ($attempt = 1; ; $attempt++) {
            $number = $manualNumber ?: static::nextNumber($date);
            try {
                return \Illuminate\Support\Facades\DB::transaction(fn () => $create($number));
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                if ($manualNumber || $attempt >= 3) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['invoice_number' => "Nomor invoice {$number} sudah dipakai. Simpan ulang untuk memakai nomor berikutnya."]);
                }
            }
        }
    }

    // --- SQL versions of the amounts, so lists and totals do not load every invoice ---

    private static function subtotalSql(): string
    {
        return '(SELECT COALESCE(SUM(invoice_items.item_qty * invoice_items.item_price), 0) FROM invoice_items WHERE invoice_items.invoice_id = invoices.id)';
    }

    /** True when a pelunasan invoice has closed this (DP) invoice. */
    private static function settledSql(): string
    {
        return '(EXISTS (SELECT 1 FROM invoices AS settlement WHERE settlement.settles_invoice_id = invoices.id))';
    }

    /** What this document asks for: items + PPN − discount − DP already paid on an earlier invoice. */
    private static function amountDueSql(): string
    {
        $s = self::subtotalSql();
        $raw = "({$s} + invoices.ppn_amount - (CASE WHEN invoices.discount_type = 'percent' THEN {$s} * invoices.discount_amount / 100 ELSE invoices.discount_amount END) - invoices.credit_amount)";

        return "(CASE WHEN {$raw} < 0 THEN 0 ELSE {$raw} END)";
    }

    /**
     * Amount billed for bookkeeping. A DP invoice closed by a pelunasan invoice counts
     * only what was paid on it; the rest is billed (once) by the pelunasan invoice.
     */
    public static function totalSql(): string
    {
        return '(CASE WHEN ' . self::settledSql() . ' THEN invoices.paid_amount ELSE ' . self::amountDueSql() . ' END)';
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
            ->selectRaw(self::amountDueSql() . ' AS amount_due_sql')
            ->selectRaw(self::settledSql() . ' AS is_settled')
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

    /** Items + PPN − discount, before any DP credit (the "SUB TOTAL … DISKON" part of the PDF). */
    public function getDocumentTotalAttribute(): float
    {
        return max(0, $this->subtotal + (float) $this->ppn_amount - $this->calculated_discount_value);
    }

    /** What this document asks the client to pay (the PDF's TOTAL line). */
    public function getAmountDueAttribute(): float
    {
        if (! $this->relationLoaded('items') && array_key_exists('amount_due_sql', $this->attributes)) {
            return (float) $this->attributes['amount_due_sql'];
        }

        return max(0, $this->document_total - (float) $this->credit_amount);
    }

    /** Closed by a pelunasan invoice (see settledBy). */
    public function getIsSettledAttribute(): bool
    {
        if ($this->relationLoaded('settledBy')) {
            return $this->settledBy !== null;
        }
        if (array_key_exists('is_settled', $this->attributes)) {
            return (bool) $this->attributes['is_settled'];
        }

        return $this->exists && static::where('settles_invoice_id', $this->id)->exists();
    }

    /** Amount billed for bookkeeping; a settled DP invoice counts only what was paid on it. */
    public function getTotalAttribute(): float
    {
        if (! $this->relationLoaded('items') && array_key_exists('amount_total', $this->attributes)) {
            return (float) $this->attributes['amount_total'];
        }

        return $this->is_settled ? (float) $this->paid_amount : $this->amount_due;
    }

    /** PDF badge: this document on its own (a settled DP invoice still reads "DP"). */
    public function getDocumentStatusAttribute(): string
    {
        $due = $this->amount_due;
        $paid = (float) $this->paid_amount;

        return $due <= 0 || $paid >= $due ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');
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
        if ($this->is_settled) {
            return 'Dilunasi';
        }

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
