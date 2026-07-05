<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = [
            'Arsip-Surat' => [
                'id' => 'Sistem pengarsipan surat digital untuk instansi pemerintahan.',
                'en' => 'Digital letter archiving system for government agencies.',
            ],
            'Berdonor' => [
                'id' => 'Platform donasi darah online yang menghubungkan pendonor dengan PMI.',
                'en' => 'Online blood donation platform connecting donors with PMI.',
            ],
            'Bismika-Interior' => [
                'id' => 'Studio desain interior dengan layanan konsultasi dan perencanaan ruang.',
                'en' => 'Interior design studio with consultation and space planning services.',
            ],
            'Choice' => [
                'id' => 'Platform pembelajaran TOEFL online dengan materi lengkap dan terstruktur.',
                'en' => 'Online TOEFL learning platform with complete and structured materials.',
            ],
            'Cibrievco' => [
                'id' => 'Perusahaan arsitektur yang menghadirkan desain bangunan modern dan fungsional.',
                'en' => 'Architecture firm delivering modern and functional building designs.',
            ],
            'Circumcare' => [
                'id' => 'Layanan kesehatan sirkumsisi modern dengan standar medis terbaik.',
                'en' => 'Modern circumcision healthcare service with the best medical standards.',
            ],
            'DPDRI' => [
                'id' => 'Sistem informasi dan layanan digital untuk Dewan Perwakilan Daerah RI.',
                'en' => 'Information system and digital services for the Regional Representative Council.',
            ],
            'Koepilihanku' => [
                'id' => 'Aplikasi pemilihan digital untuk organisasi dan komunitas.',
                'en' => 'Digital voting application for organizations and communities.',
            ],
            'Lateevadinamika' => [
                'id' => 'Perusahaan engineering yang menyediakan solusi teknis dan manajemen proyek.',
                'en' => 'Engineering company providing technical solutions and project management.',
            ],
            'Masa-Depan-Hukum' => [
                'id' => 'Platform edukasi dan konsultasi hukum secara online.',
                'en' => 'Online legal education and consultation platform.',
            ],
            'Mindful' => [
                'id' => 'Aplikasi kesehatan mental dengan fitur meditasi, journaling, dan konseling.',
                'en' => 'Mental health app with meditation, journaling, and counseling features.',
            ],
            'Navigo' => [
                'id' => 'Platform navigasi dan informasi bisnis berbasis lokasi.',
                'en' => 'Location-based business navigation and information platform.',
            ],
            'Rentflow' => [
                'id' => 'Sistem manajemen penyewaan properti dan inventory.',
                'en' => 'Property rental and inventory management system.',
            ],
            'RS-Sampang' => [
                'id' => 'Sistem informasi manajemen rumah sakit untuk RS Sampang.',
                'en' => 'Hospital management information system for RS Sampang.',
            ],
            'Sepatu-Andalas' => [
                'id' => 'Brand sepatu lokal dengan desain modern dan bahan berkualitas.',
                'en' => 'Local footwear brand with modern designs and quality materials.',
            ],
            'Serenity' => [
                'id' => 'Platform wellness & spa dengan fitur booking, paket perawatan, dan membership.',
                'en' => 'Wellness & spa platform with booking, treatment packages, and membership features.',
            ],
            'Seroom.id' => [
                'id' => 'Platform penyewaan ruangan dan meeting room secara online.',
                'en' => 'Online room and meeting space rental platform.',
            ],
            'Shellfish' => [
                'id' => 'Restoran seafood dengan sistem pemesanan online dan manajemen meja.',
                'en' => 'Seafood restaurant with online ordering and table management system.',
            ],
            'Sibatur' => [
                'id' => 'Aplikasi wisata batik dengan informasi destinasi, galeri, dan e-commerce.',
                'en' => 'Batik tourism app with destination info, gallery, and e-commerce features.',
            ],
            'SMN-TAX' => [
                'id' => 'Jasa konsultan pajak profesional dengan sistem pelaporan digital.',
                'en' => 'Professional tax consultant service with digital reporting system.',
            ],
            'UD-Sumber-Bangunan' => [
                'id' => 'Toko material bangunan dengan sistem inventaris dan penjualan digital.',
                'en' => 'Building materials store with digital inventory and sales system.',
            ],
            'UPN-Jatim' => [
                'id' => 'Portal informasi dan sistem akademik untuk UPN Veteran Jawa Timur.',
                'en' => 'Information portal and academic system for UPN Veteran Jawa Timur.',
            ],
            'Vokasi-UNESA' => [
                'id' => 'Sistem pelaporan dan monitoring untuk Fakultas Vokasi UNESA.',
                'en' => 'Reporting and monitoring system for UNESA Vocational Faculty.',
            ],
        ];

        foreach (Partner::all() as $partner) {
            if (!isset($descriptions[$partner->name])) continue;

            // Set ID description (original column)
            $partner->description = $descriptions[$partner->name]['id'];

            // Set EN translation
            $partner->setTranslation('description', 'en', $descriptions[$partner->name]['en']);

            $partner->save();
        }

        $this->command->info('Partner descriptions seeded for ' . count($descriptions) . ' partners.');
    }
}
