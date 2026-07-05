<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use Translatable;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'image', 'og_image', 'alt_text', 'pexels_photo_id',
        'author', 'category', 'tags',
        'seo_title', 'seo_description', 'seo_keywords',
        'is_published', 'published_at', 'views',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            if (empty($article->author)) {
                $article->author = 'The Solution ID';
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
