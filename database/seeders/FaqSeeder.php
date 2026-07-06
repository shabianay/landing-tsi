<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::insert([
            [
                'question' => 'Berapa lama proses pembuatan website?',
                'answer' => 'Proses pembuatan website umumnya memakan waktu 1-3 minggu tergantung kompleksitas fitur yang dibutuhkan. Kami memberikan estimasi timeline yang jelas di awal proyek.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bisakah saya melihat desain sebelum website selesai?',
                'answer' => 'Tentu saja! Kami menyediakan mockup desain untuk Anda review dan revisi sebelum masuk tahap pengembangan. Kepuasan Anda adalah prioritas kami.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Apakah ada garansi setelah website diluncurkan?',
                'answer' => 'Ya, kami memberikan layanan perbaikan gratis selama masa garansi untuk memastikan website berjalan optimal setelah peluncuran.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Teknologi apa yang digunakan untuk membuat website?',
                'answer' => 'Kami menggunakan teknologi terbaru seperti Laravel, Tailwind CSS, dan MySQL untuk memastikan website Anda cepat, aman, dan mudah dikelola.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Berapa biaya pembuatan website?',
                'answer' => 'Biaya tergantung pada kompleksitas fitur yang dibutuhkan. Hubungi kami untuk konsultasi gratis dan dapatkan penawaran harga yang disesuaikan dengan kebutuhan bisnis Anda.',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
