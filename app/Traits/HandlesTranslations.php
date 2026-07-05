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
                $key = "trans_{$field}_{$locale}";
                if ($request->has($key)) {
                    $model->setTranslation($field, $locale, $request->input($key));
                    if ($locale === 'id') {
                        $model->$field = $request->input($key);
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
