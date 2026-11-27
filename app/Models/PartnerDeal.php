<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerDeal extends Model
{
    protected $fillable = [
        'description',
        'deal_amount',
        'discount_amount',
        'final_amount',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(PartnerLead::class, 'partner_lead_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PartnerPayment::class);
    }
}
