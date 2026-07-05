<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessStep extends Model
{
    use SoftDeletes, Translatable;
    protected $fillable = ['title', 'description', 'icon', 'step_number', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'step_number' => 'integer',
        ];
    }
}
