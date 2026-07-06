<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'Virlab', 'email' => 'info@virlab.co.id', 'phone' => '081234567890', 'website_url' => 'https://virlab.co.id'],
            ['name' => 'Lapor Vokasi', 'email' => 'info@laporvokasi.ac.id', 'phone' => '089876543210', 'website_url' => 'https://laporvokasi.ac.id'],
            ['name' => 'Bismika Interior', 'email' => 'info@bismikainterior.com', 'phone' => '081234567891', 'website_url' => 'https://bismikainterior.com'],
            ['name' => 'Sibatur', 'email' => 'info@sibatur.id', 'phone' => '089876543211', 'website_url' => 'https://sibatur.id'],
            ['name' => 'Lateevadinamika', 'email' => 'info@lateevadinamika.co.id', 'phone' => '081234567892', 'website_url' => 'https://lateevadinamika.co.id'],
            ['name' => 'Mindful', 'email' => 'info@mindfulapp.com', 'phone' => '089876543212', 'website_url' => 'https://mindfulapp.com'],
            ['name' => 'Wedding', 'email' => 'wedding@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Shellfish', 'email' => 'shellfish@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'RS Sampang', 'email' => 'rssampang@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Cibrievco', 'email' => 'cibrievco@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Serenity', 'email' => 'serenity@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Seroom.id', 'email' => 'seroomid@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Toothscan', 'email' => 'toothscan@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'UBARU.ID', 'email' => 'ubaruid@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Lo-Fi SIM UKM', 'email' => 'lofisimukm@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Lo-Fi Seserahan', 'email' => 'lofiseserahan@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Berdonor', 'email' => 'berdonor@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'GoodLiving', 'email' => 'goodliving@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Basdig', 'email' => 'basdig@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Donasiyuk', 'email' => 'donasiyuk@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Freshfusion', 'email' => 'freshfusion@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Artcol', 'email' => 'artcol@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Innovating Farming', 'email' => 'innovatingfarming@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'SPK Smart', 'email' => 'spksmart@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'UD Sumber Barokah', 'email' => 'udsumberbarokah@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Masa Depan Hukum', 'email' => 'masadepanhukum@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Angkot', 'email' => 'angkot@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Arsip Surat', 'email' => 'arsipsurat@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Agrios', 'email' => 'agrios@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Amazing Mount', 'email' => 'amazingmount@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Pakar CF', 'email' => 'pakarcf@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Koepilihanku', 'email' => 'koepilihanku@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'SMN-TAX', 'email' => 'smntax@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Navigo', 'email' => 'navigo@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Choice', 'email' => 'choice@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'DPD RI', 'email' => 'dpdri@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Safety Work Permit', 'email' => 'safetyworkpermit@example.com', 'phone' => '', 'website_url' => ''],
            ['name' => 'Mount Ntuy', 'email' => 'mountntuy@example.com', 'phone' => '', 'website_url' => ''],
        ];

        foreach ($clients as $c) {
            Client::create($c);
        }
    }
}
