<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    public const PPN_RATE = 0.10;

    protected $fillable = [
        'order_client_id', 'invoice_number', 'invoice_date', 'client_name', 'client_address',
        'notes', 'paid_amount', 'ppn_amount', 'discount_amount', 'discount_type',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'paid_amount' => 'decimal:2',
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

    /** Next number in the studio's format TSI/MM/YYYY/NNN, restarting every month. */
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

    public function getSubtotalAttribute(): float
    {
        return (float) $this->items->sum(fn ($item) => $item->item_qty * (float) $item->item_price);
    }

    public function getCalculatedDiscountValueAttribute(): float
    {
        $discount = (float) $this->discount_amount;

        return $this->discount_type === 'percent' ? $this->subtotal * ($discount / 100) : $discount;
    }

    public function getTotalAttribute(): float
    {
        return max(0, $this->subtotal + (float) $this->ppn_amount - $this->calculated_discount_value);
    }

    /**
     * Invoices with no payment at all. A DP (partial) invoice is not counted:
     * the client has started paying, so it is not "belum dibayar".
     *
     * @param  iterable<self>  $invoices
     * @return array{total: float, count: int}
     */
    public static function unpaidSummary(iterable $invoices): array
    {
        $unpaid = collect($invoices)->filter(fn (self $i) => $i->status === 'unpaid');

        return ['total' => (float) $unpaid->sum->total, 'count' => $unpaid->count()];
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
}
