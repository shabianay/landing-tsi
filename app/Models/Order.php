<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** A client project ("proyek pesanan") tracked on the Kanban board. */
class Order extends Model
{
    public const STATUSES = ['Pending', 'Ongoing', 'Completed', 'Cancelled'];

    public const STATUS_LABELS = [
        'Pending' => 'Menunggu',
        'Ongoing' => 'Dikerjakan',
        'Completed' => 'Selesai',
        'Cancelled' => 'Batal',
    ];

    public const PAYMENT_METHODS = ['QRIS', 'BCA', 'BLU', 'DANA', 'Tunai'];

    protected $fillable = [
        'order_client_id', 'client_name', 'task_type', 'description', 'deadline', 'price',
        'status', 'payment_method', 'payment_date', 'payment_lunas_date',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'price' => 'decimal:2',
            'payment_date' => 'date',
            'payment_lunas_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(OrderClient::class, 'order_client_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Status follows the payment dates (as in SIM-Proyek): paid in full means Completed,
     * a down payment means Ongoing, otherwise Pending. A cancelled project stays cancelled.
     */
    public static function statusFromPayments(array $data, ?string $current = null): string
    {
        if ($current === 'Cancelled') {
            return 'Cancelled';
        }
        if (! empty($data['payment_lunas_date'])) {
            return 'Completed';
        }
        if (! empty($data['payment_date'])) {
            return 'Ongoing';
        }

        return 'Pending';
    }

    /**
     * Status after an edit. A status the admin picked (on the board or in the form) is kept;
     * it is only recalculated when the payment dates themselves change.
     */
    public function statusAfterEdit(array $data, ?string $requested = null): string
    {
        if ($requested !== null && in_array($requested, self::STATUSES, true) && $requested !== $this->status) {
            return $requested;
        }

        $datesChanged = ($data['payment_date'] ?? null) != $this->payment_date?->toDateString()
            || ($data['payment_lunas_date'] ?? null) != $this->payment_lunas_date?->toDateString();

        return $datesChanged ? self::statusFromPayments($data, $this->status) : $this->status;
    }

    public function isOverdue(): bool
    {
        return $this->deadline !== null
            && $this->deadline->isBefore(today())
            && ! in_array($this->status, ['Completed', 'Cancelled'], true);
    }
}
