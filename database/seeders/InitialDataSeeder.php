<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\ProcessStep;
use App\Models\Service;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        Service::insert([
            ['title' => 'Web Development', 'description' => 'Membangun website profesional dan responsif dengan teknologi terkini untuk bisnis Anda.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'UI/UX Design', 'description' => 'Desain antarmuka yang intuitif dan pengalaman pengguna yang memorable.', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'SEO Optimization', 'description' => 'Optimasi website agar mudah ditemukan di mesin pencari seperti Google.', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Digital Marketing', 'description' => 'Strategi pemasaran digital yang terukur untuk mengembangkan bisnis Anda.', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        ProcessStep::insert([
            ['title' => 'Konsultasi', 'description' => 'Diskusikan kebutuhan dan visi bisnis Anda bersama tim kami.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2', 'step_number' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Perencanaan', 'description' => 'Kami menyusun strategi dan rancangan yang tepat untuk proyek Anda.', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'step_number' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Eksekusi', 'description' => 'Tim kami mengerjakan proyek dengan presisi dan sesuai timeline.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'step_number' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Peluncuran & Maintenance', 'description' => 'Website siap diluncurkan dengan dukungan maintenance berkelanjutan.', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'step_number' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $partnerNames = [
            'Arsip-Surat', 'Berdonor', 'Bismika-Interior', 'Choice',
            'Cibrievco', 'Circumcare', 'DPDRI', 'Koepilihanku',
            'Lateevadinamika', 'Masa-Depan-Hukum', 'Mindful', 'Navigo',
            'Rentflow', 'RS-Sampang', 'Sepatu-Andalas', 'Serenity',
            'Seroom.id', 'Shellfish', 'Sibatur', 'SMN-TAX',
            'UD-Sumber-Bangunan', 'UPN-Jatim', 'Vokasi-UNESA',
        ];

        $partners = [];
        foreach ($partnerNames as $i => $name) {
            $partners[] = [
                'name' => $name,
                'logo' => 'partners/' . $name . '.webp',
                'sort_order' => $i + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Partner::insert($partners);
    }
}
