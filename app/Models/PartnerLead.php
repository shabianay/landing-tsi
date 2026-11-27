<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerLead extends Model
{
    protected $fillable = [
        'applicant_id',
        'full_name',
        'whatsapp',
        'service',
        'notes',
        'reported_at',
    ];

    protected function casts(): array
    {
        return ['reported_at' => 'datetime'];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id')->withTrashed();
    }

    public function deals(): HasMany
    {
        return $this->hasMany(PartnerDeal::class);
    }
}
