<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HandlesTranslations
{
    public function saveTranslations(Request $request, Model $model, array $translatableFields): void
    {
        foreach ($translatableFields as $field) {
            foreach (['id', 'en'] as $locale) {
                $key = "{$field}.{$locale}";
                if ($request->has($key)) {
                    $value = $request->input($key);
                    $model->setTranslation($field, $locale, $value);
                    if ($locale === 'id') {
                        $model->$field = $value;
                    }
                }
            }
        }
        $model->save();
    }

    protected function translatableFields(): array
    {
        return ['title', 'description'];
    }
}
