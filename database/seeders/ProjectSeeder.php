<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::pluck('id', 'name');

        $projects = [
            ['title' => 'Virlab Uji Golongan Darah', 'client' => 'Virlab', 'img' => 'portfolio/Virlab-Uji-Golongan-Darah-6a387bc826088.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/virlab-uji-golongan-darah'],
            ['title' => 'Lapor Vokasi', 'client' => 'Lapor Vokasi', 'img' => 'portfolio/Lapor-Vokasi-1-6a387bc9841fd.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/lapor-vokasi'],
            ['title' => 'Bismika Interior', 'client' => 'Bismika Interior', 'img' => 'portfolio/Bismika-Interior-6a387bcb016b7.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/bismika-interior'],
            ['title' => 'Sibatur', 'client' => 'Sibatur', 'img' => 'portfolio/Sibatur-6a387bcc56918.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/sibatur'],
            ['title' => 'Lateevadinamika', 'client' => 'Lateevadinamika', 'img' => 'portfolio/lateevadinamika-1-6a387bcd8cd51.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/lateevadinamika'],
            ['title' => 'Mindful', 'client' => 'Mindful', 'img' => 'portfolio/Mindful-6a387bcee5408.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/mindful'],
            ['title' => 'Wedding Invitation V1', 'client' => 'Wedding', 'img' => 'portfolio/Weddingapp1-1-6a387bd04d9b0.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/wedding-invitation-v1'],
            ['title' => 'Shellfish - Seafood Restaurant', 'client' => 'Shellfish', 'img' => 'portfolio/Shellfish-1-6a387bd1a9fb8.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/shellfish-seafood-restaurant'],
            ['title' => 'RS Sampang Hospital System', 'client' => 'RS Sampang', 'img' => 'portfolio/RS-SAMPANG-1-6a387bd3069cc.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/rs-sampang-hospital-system'],
            ['title' => 'Cibrievco Architecture', 'client' => 'Cibrievco', 'img' => 'portfolio/Cibrievco-1-1-6a387bd46fd57.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/cibrievco-architecture'],
            ['title' => 'Serenity - Wellness & Spa', 'client' => 'Serenity', 'img' => 'portfolio/Serenity-1-6a387bd5abe88.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/serenity-wellness-spa'],
            ['title' => 'Seroom.id - Room Rental', 'client' => 'Seroom.id', 'img' => 'portfolio/Seroom.id_-6a387bd6e0efb.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/seroom-id-room-rental'],
            ['title' => 'Toothscan Dental Clinic', 'client' => 'Toothscan', 'img' => 'portfolio/Toothscan-6a387bd82b796.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/toothscan-dental-clinic'],
            ['title' => 'UBARU.ID Platform', 'client' => 'UBARU.ID', 'img' => 'portfolio/UBARU.ID_-6a387bd9bf489.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/ubaru-id-platform'],
            ['title' => 'Lo-Fi SIM UKM System', 'client' => 'Lo-Fi SIM UKM', 'img' => 'portfolio/Lo-Fi-SIM-UKM-6a387bdb3094e.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/lo-fi-sim-ukm-system'],
            ['title' => 'Lo-Fi Seserahan by Pings', 'client' => 'Lo-Fi Seserahan', 'img' => 'portfolio/Lo-Fi-Seserahan-by-Pings-6a387bdc7feec.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/lo-fi-seserahan-by-pings'],
            ['title' => 'Berdonor - Blood Donation', 'client' => 'Berdonor', 'img' => 'portfolio/Berdonor-1-6a387bddc12aa.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/berdonor-blood-donation'],
            ['title' => 'GoodLiving Lifestyle', 'client' => 'GoodLiving', 'img' => 'portfolio/GoodLiving-6a387bdf23102.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/goodliving-lifestyle'],
            ['title' => 'Basdig Platform', 'client' => 'Basdig', 'img' => 'portfolio/Basdig-6a387be0736e6.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/basdig-platform'],
            ['title' => 'Donasiyuk - Let\'s Donate', 'client' => 'Donasiyuk', 'img' => 'portfolio/Donasiyuk-6a387be1b6d36.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/donasiyuk-lets-donate'],
            ['title' => 'Freshfusion Brand', 'client' => 'Freshfusion', 'img' => 'portfolio/Freshfusion-6a387be3068cb.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/freshfusion-brand'],
            ['title' => 'Artcol Creative', 'client' => 'Artcol', 'img' => 'portfolio/Artcol-6a387be492b89.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/artcol-creative'],
            ['title' => 'Innovating Farming', 'client' => 'Innovating Farming', 'img' => 'portfolio/Innovating-Farming-6a387be62db8a.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/innovating-farming'],
            ['title' => 'SPK Smart Decision System', 'client' => 'SPK Smart', 'img' => 'portfolio/SPK-Smart-6a387be79c6f9.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/spk-smart-decision-system'],
            ['title' => 'UD Sumber Barokah', 'client' => 'UD Sumber Barokah', 'img' => 'portfolio/UD-Sumber-Barokah-6a387be8d97be.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/ud-sumber-barokah'],
            ['title' => 'Masa Depan Hukum - Future of Law', 'client' => 'Masa Depan Hukum', 'img' => 'portfolio/Masa-Depan-Hukum-6a387bea5b0a0.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/masa-depan-hukum-future-of-law'],
            ['title' => 'Angkot Transportation', 'client' => 'Angkot', 'img' => 'portfolio/Angkot-6a387beba2bc1.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/angkot-transportation'],
            ['title' => 'Mail Archive V2', 'client' => 'Arsip Surat', 'img' => 'portfolio/Arsip-Surat-6a387bee55494.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/mail-archive-v2'],
            ['title' => 'Mail Archive System', 'client' => 'Arsip Surat', 'img' => 'portfolio/Arsip-Surat-V2-6a387bed00451.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/mail-archive-system'],
            ['title' => 'Agrios Agriculture', 'client' => 'Agrios', 'img' => 'portfolio/Agrios-6a387befb2802.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/agrios-agriculture'],
            ['title' => 'Amazing Mount Adventure', 'client' => 'Amazing Mount', 'img' => 'portfolio/Amazing-Mount-6a387bf116cf6.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/amazing-mount-adventure'],
            ['title' => 'Pakar CF - Expert System', 'client' => 'Pakar CF', 'img' => 'portfolio/Pakar-CF-6a387bf25f835.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/pakar-cf-expert-system'],
            ['title' => 'Koepilihanku - My Choice', 'client' => 'Koepilihanku', 'img' => 'portfolio/Koepilihanku-6a387bf39d4a9.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/koepilihanku-my-choice'],
            ['title' => 'PT. SMNTAX INDONESIA', 'client' => 'SMN-TAX', 'img' => 'portfolio/SMNTAXID-1-6a387c8b3ad61.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/pt-smntax-indonesia'],
            ['title' => 'NAVIGO.ID', 'client' => 'Navigo', 'img' => 'portfolio/navigo-1-6a387c8b64766.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/navigo-id'],
            ['title' => 'Kampung Inggris TOEFL (Choice TOEFL)', 'client' => 'Choice', 'img' => 'portfolio/Group-47-6a387c8b8449c.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/kampung-inggris-toefl-choice-toefl'],
            ['title' => 'DPD RI Collective', 'client' => 'DPD RI', 'img' => 'portfolio/Group-48-6a387c8ba65c1.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/dpd-ri-collective'],
            ['title' => 'Safety Work Permit System', 'client' => 'Safety Work Permit', 'img' => 'portfolio/Group-49-6a387c8bc7e92.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/safety-work-permit-system'],
            ['title' => 'Mount Ntuy (ROBLOX Map)', 'client' => 'Mount Ntuy', 'img' => 'portfolio/Group-50-6a387c8be5f22.webp', 'url' => 'https://shabianportfolio.my.id/portfolio/mount-ntuy-roblox-map'],
        ];

        foreach ($projects as $p) {
            Project::create([
                'title' => $p['title'],
                'client_id' => $client[$p['client']],
                'description' => 'Project ' . $p['title'] . ' oleh The Solution ID.',
                'website_url' => $p['url'],
                'image' => $p['img'],
                'alt_text' => $p['title'],
                'status' => 'completed',
            ]);
        }
    }
}
