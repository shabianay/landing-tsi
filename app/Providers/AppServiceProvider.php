<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Generated links (sitemap, canonical, OG, redirects) must use HTTPS in production.
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // @rp($amount) prints an amount as Indonesian rupiah, e.g. Rp1.250.000
        Blade::directive('rp', fn (string $expression) => "<?php echo 'Rp' . number_format((float) ($expression), 0, ',', '.'); ?>");
    }
}
