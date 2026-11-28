<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** One payment received for an invoice (DP, cicilan, pelunasan). */
class InvoicePayment extends Model
{
    protected $fillable = ['invoice_id', 'amount', 'paid_at', 'method', 'note'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'paid_at' => 'date'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /** Receipt number, e.g. KW/TSI/11/2026/004/2 (second payment of that invoice). */
    public function getReceiptNumberAttribute(): string
    {
        $sequence = static::where('invoice_id', $this->invoice_id)
            ->where(fn ($q) => $q->where('paid_at', '<', $this->paid_at)
                ->orWhere(fn ($q) => $q->where('paid_at', $this->paid_at)->where('id', '<=', $this->id)))
            ->count();

        return 'KW/' . $this->invoice->invoice_number . '/' . $sequence;
    }
}
