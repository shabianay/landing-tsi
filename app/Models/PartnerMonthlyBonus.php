<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerMonthlyBonus extends Model
{
    protected $fillable = [
        'applicant_id',
        'month',
        'eligible_client_count',
        'amount',
        'paid_amount',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id')->withTrashed();
    }
}
