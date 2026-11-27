<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const ACTION_LABELS = [
        'created' => 'Dibuat',
        'updated' => 'Diubah',
        'deleted' => 'Dihapus',
        'imported' => 'Diimpor',
    ];

    protected $fillable = ['user_id', 'action', 'model_type', 'model_id', 'description'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, Model|string $subject, string $description): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => is_string($subject) ? $subject : class_basename($subject),
            'model_id' => is_string($subject) ? null : $subject->getKey(),
            'description' => $description,
        ]);
    }
}
