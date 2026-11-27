<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use SoftDeletes, Translatable;
    protected $fillable = ['client_id', 'title', 'description', 'website_url', 'image', 'og_image', 'alt_text', 'status'];

    protected static function booted(): void
    {
        // Files are kept while soft-deleted so a restored project still has its images.
        static::forceDeleted(function ($project) {
            foreach (array_filter([$project->image, $project->og_image]) as $path) {
                Storage::disk('public')->delete($path);
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
