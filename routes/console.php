<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('articles:generate', function () {
    $this->info('Memulai generate artikel harian...');
    $result = (new \App\Console\Commands\GenerateArticles)->handle();
    if ($result['success']) {
        $this->info($result['message']);
    } else {
        $this->error($result['message']);
    }
})->purpose('Generate 1 SEO article using AI and auto-publish');

Artisan::command('articles:fill-images', function () {
    $this->info('Memperbarui gambar artikel dengan keyword relevan...');
    $count = (new \App\Console\Commands\FillArticleImages)->handle();
    $this->info("Selesai. {$count} artikel diperbarui.");
})->purpose('Download and replace article images with category-relevant photos');

Schedule::command('articles:generate')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/article-bot.log'));
