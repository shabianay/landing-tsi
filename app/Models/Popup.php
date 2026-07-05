<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use Translatable;
    protected $fillable = [
        'title',
        'content',
        'image',
        'button_text',
        'button_url',
        'is_active',
        'display_delay',
        'start_at',
        'end_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_delay' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('start_at')->orWhere('start_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('end_at')->orWhere('end_at', '>=', now()));
    }
}
