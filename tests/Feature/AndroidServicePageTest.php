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
            ->assertSee('Aplikasi Android')
            ->assertSee('File APK siap install')
            ->assertSee('Bantu upload ke Play Store')
            ->assertSee('Kasir (POS)')
            ->assertSee('Konversi Website ke Aplikasi');
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
            ->assertSee('Ready-to-install APK file')
            ->assertSee('Apps We Can Build');
    }
}
