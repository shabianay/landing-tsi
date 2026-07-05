<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $defaults = [
            ['key' => 'site_name', 'value' => 'The Solution ID', 'group' => 'general', 'label' => 'Nama Situs'],
            ['key' => 'logo', 'value' => 'logo.webp', 'group' => 'general', 'label' => 'Logo Situs'],
            ['key' => 'site_tagline', 'value' => 'Website Profesional Untuk Bisnis Anda', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'contact_phone', 'value' => '085967142870', 'group' => 'contact', 'label' => 'Nomor Telepon'],
            ['key' => 'contact_email', 'value' => 'hello@thesolutionid.com', 'group' => 'contact', 'label' => 'Email Kontak'],
            ['key' => 'contact_whatsapp', 'value' => '6285967142870', 'group' => 'contact', 'label' => 'Nomor WhatsApp (tanpa +)'],
            ['key' => 'contact_website', 'value' => 'thesolutionid.com', 'group' => 'contact', 'label' => 'Website'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/thesolutionid', 'group' => 'social', 'label' => 'Instagram URL'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/thesolutionid', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'mail_recipient_1', 'value' => 'shabianarsyl@gmail.com', 'group' => 'mail', 'label' => 'Email Penerima 1'],
            ['key' => 'mail_recipient_2', 'value' => 'thesolutionid@gmail.com', 'group' => 'mail', 'label' => 'Email Penerima 2'],
            ['key' => 'stat_projects', 'value' => '50+', 'group' => 'stats', 'label' => 'Statistik: Proyek Selesai'],
            ['key' => 'stat_rating', 'value' => '4.9/5', 'group' => 'stats', 'label' => 'Statistik: Rating Kepuasan'],
            ['key' => 'stat_clients', 'value' => '30+', 'group' => 'stats', 'label' => 'Statistik: Klien Aktif'],
            ['key' => 'stat_timeline', 'value' => '1-3 Minggu', 'group' => 'stats', 'label' => 'Statistik: Waktu Pengerjaan'],
        ];

        foreach ($defaults as $s) {
            DB::table('settings')->insert($s + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
