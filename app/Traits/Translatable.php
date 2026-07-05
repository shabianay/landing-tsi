<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Translatable
{
    public static function bootTranslatable(): void
    {
        static::deleting(function ($model) {
            $model->translations()->delete();
        });
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function trans(string $key, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        $translation = $this->translations
            ->where('locale', $locale)
            ->where('key', $key)
            ->first();

        if ($translation && $translation->value) {
            return $translation->value;
        }

        $fallback = $this->translations
            ->where('locale', config('app.fallback_locale', 'en'))
            ->where('key', $key)
            ->first();

        if ($fallback && $fallback->value) {
            return $fallback->value;
        }

        return $this->$key ?? null;
    }

    public function setTranslation(string $key, string $locale, ?string $value): void
    {
        if ($value === null || trim($value) === '') {
            $this->translations()
                ->where('locale', $locale)
                ->where('key', $key)
                ->delete();
            return;
        }

        $this->translations()->updateOrCreate(
            ['locale' => $locale, 'key' => $key],
            ['value' => $value]
        );
    }

    public function translate(?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $translations = [];

        foreach ($this->translations->where('locale', $locale) as $t) {
            $translations[$t->key] = $t->value;
        }

        return $translations;
    }
}
