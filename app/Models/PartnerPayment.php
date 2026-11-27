<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerPayment extends Model
{
    protected $fillable = [
        'payment_type',
        'amount',
        'commission_amount',
        'received_at',
        'commission_paid_at',
    ];

    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
            'commission_paid_at' => 'datetime',
        ];
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(PartnerDeal::class, 'partner_deal_id');
    }
}
