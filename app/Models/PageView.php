<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use MassPrunable;

    /** Visitor IPs and user agents are personal data; keep them only as long as needed for stats. */
    public const RETENTION_DAYS = 180;

    protected $fillable = ['url', 'title', 'ip', 'user_agent'];

    public function prunable(): Builder
    {
        return static::where('created_at', '<', now()->subDays(self::RETENTION_DAYS));
    }
}
