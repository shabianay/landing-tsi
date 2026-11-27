<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AndroidServicePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_android_page_shows_the_poster_content(): void
    {
        $this->get(route('services.android'))
            ->assertOk()
            ->assertSee('Aplikasi Android untuk bisnis Anda.')
            ->assertSee('file APK')
            ->assertSee('source code lengkap')
            ->assertSee('Play Store')
            ->assertSee('Kasir (POS)')
            ->assertSee('Konversi Website ke Aplikasi')
            ->assertSee('Lainnya (Konsultasi Dulu)');
    }

    public function test_android_page_is_linked_from_home_nav_and_sitemap(): void
    {
        $this->get(route('home'))->assertOk()->assertSee(route('services.android'), false);
        $this->get(route('sitemap'))->assertOk()->assertSee(route('services.android'), false);
    }

    public function test_android_page_follows_english_locale(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get(route('services.android'))
            ->assertOk()
            ->assertSee('Android apps for your business.')
            ->assertSee('From idea to Play Store');
    }
}
