<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesShowcaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_website_and_android_services_together(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="jasa"', false)
            ->assertSee('Mau dibuatkan apa?')
            // Website
            ->assertSee('Gratis 2 Email Bisnis')
            ->assertSee('Company Profile')
            ->assertSee('Pengerjaan 1–3 minggu')
            // Android
            ->assertSee('File APK siap install')
            ->assertSee('Bantu upload ke Play Store')
            ->assertSee('Kasir (POS)')
            ->assertSee('Konversi Website ke Aplikasi');
    }

    public function test_old_android_page_redirects_to_the_home_section(): void
    {
        $this->get('/aplikasi-android')->assertStatus(301)->assertRedirect('/#jasa');
    }

    public function test_services_section_follows_english_locale(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('What should we build for you?')
            ->assertSee('Ready-to-install APK file');
    }
}
