<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Popup;
use App\Models\ProcessStep;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $locale = 'id';

        // Existing models
        foreach (Service::all() as $model) {
            $model->setTranslation('title', $locale, $model->title);
            $model->setTranslation('description', $locale, $model->description);
        }

        foreach (ProcessStep::all() as $model) {
            $model->setTranslation('title', $locale, $model->title);
            $model->setTranslation('description', $locale, $model->description);
        }

        foreach (Partner::all() as $model) {
            $model->setTranslation('name', $locale, $model->name);
            $model->setTranslation('description', $locale, $model->description);
        }

        foreach (Faq::all() as $model) {
            $model->setTranslation('question', $locale, $model->question);
            $model->setTranslation('answer', $locale, $model->answer);
        }

        foreach (Testimonial::all() as $model) {
            $model->setTranslation('client_name', $locale, $model->client_name);
            $model->setTranslation('company_name', $locale, $model->company_name);
            $model->setTranslation('content', $locale, $model->content);
        }

        foreach (Project::all() as $model) {
            $model->setTranslation('title', $locale, $model->title);
            $model->setTranslation('description', $locale, $model->description);
        }

        // New models that were missing ID translations
        foreach (Popup::all() as $model) {
            $model->setTranslation('title', $locale, $model->title);
            $model->setTranslation('content', $locale, $model->content);
            $model->setTranslation('button_text', $locale, $model->button_text);
        }

        foreach (Article::all() as $model) {
            $model->setTranslation('title', $locale, $model->title);
            $model->setTranslation('excerpt', $locale, $model->excerpt);
            $model->setTranslation('content', $locale, $model->content);
            $model->setTranslation('category', $locale, $model->category);
            $model->setTranslation('seo_title', $locale, $model->seo_title);
            $model->setTranslation('seo_description', $locale, $model->seo_description);
        }
    }
}
