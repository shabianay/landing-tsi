<?php

namespace Database\Seeders;

use App\Models\Popup;
use Illuminate\Database\Seeder;

class PopupSeeder extends Seeder
{
    public function run(): void
    {
        Popup::insert([
            [
                'title' => 'Dapatkan Diskon 20% untuk Proyek Pertama Anda!',
                'content' => 'Kami menawarkan penawaran spesial untuk klien baru. Konsultasi gratis + diskon 20% untuk proyek website pertama Anda. Promo terbatas!',
                'button_text' => 'Hubungi Kami Sekarang',
                'button_url' => 'https://wa.me/6285967142870?text=Halo%20The%20Solution%20ID!%20Saya%20tertarik%20dengan%20promo%20diskon%2020%25',
                'is_active' => true,
                'display_delay' => 1,
                'start_at' => now()->subDays(7),
                'end_at' => now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
