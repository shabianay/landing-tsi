<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** A client who orders work from the studio (not a portfolio logo, see Client). */
class OrderClient extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address', 'company', 'notes'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
