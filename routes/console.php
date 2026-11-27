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

Schedule::command('database:backup')
    ->dailyAt('02:00')
    ->withoutOverlapping();

Schedule::command('model:prune', ['--model' => [\App\Models\PageView::class]])
    ->dailyAt('03:00')
    ->withoutOverlapping();

Artisan::command('admin:password {email}', function (string $email) {
    $user = \App\Models\User::where('email', $email)->where('is_admin', true)->first();

    if (! $user) {
        $this->error('Admin dengan email tersebut tidak ditemukan.');

        return 1;
    }

    $password = $this->secret('Password baru (minimal 12 karakter)');

    if (! is_string($password) || mb_strlen($password) < 12 || strtolower($password) === 'password') {
        $this->error('Password terlalu lemah. Gunakan minimal 12 karakter.');

        return 1;
    }

    if ($password !== $this->secret('Ulangi password baru')) {
        $this->error('Konfirmasi password tidak cocok.');

        return 1;
    }

    $user->forceFill([
        'password' => $password,
        'remember_token' => \Illuminate\Support\Str::random(60),
    ])->save();

    $sessions = \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();

    $this->info("Password admin diperbarui. {$sessions} sesi aktif dihapus.");

    return 0;
})->purpose('Change an admin password and sign out all of their sessions');
