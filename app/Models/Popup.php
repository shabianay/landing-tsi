<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popup extends Model
{
    use SoftDeletes, Translatable;
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

    /**
     * Schedule times are typed in the admin form as local wall-clock time (WIB) and stored as-is.
     * The app runs in UTC, so compare against the current WIB time, not now().
     */
    public const SCHEDULE_TIMEZONE = 'Asia/Jakarta';

    public static function scheduleNow(): string
    {
        return now(self::SCHEDULE_TIMEZONE)->format('Y-m-d H:i:s');
    }

    public function scopeActive($query)
    {
        $now = self::scheduleNow();

        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('start_at')->orWhere('start_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('end_at')->orWhere('end_at', '>=', $now));
    }

    /** live | scheduled | ended | inactive: what visitors actually get right now. */
    public function visibility(): string
    {
        if (! $this->is_active) {
            return 'inactive';
        }

        $now = self::scheduleNow();

        if ($this->start_at && $this->start_at->format('Y-m-d H:i:s') > $now) {
            return 'scheduled';
        }

        if ($this->end_at && $this->end_at->format('Y-m-d H:i:s') < $now) {
            return 'ended';
        }

        return 'live';
    }
}
