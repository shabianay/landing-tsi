<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, Translatable;
    protected $fillable = ['client_id', 'title', 'description', 'website_url', 'image', 'og_image', 'alt_text', 'status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
