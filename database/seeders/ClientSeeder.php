<?php

namespace Database\Seeders;

use App\Models\Client;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'name' => 'Virlab',
            'email' => 'info@virlab.co.id',
            'phone' => '081234567890',
            'website_url' => 'https://virlab.co.id',
        ]);

        Client::create([
            'name' => 'Lapor Vokasi',
            'email' => 'info@laporvokasi.ac.id',
            'phone' => '089876543210',
            'website_url' => 'https://laporvokasi.ac.id',
        ]);

        Client::create([
            'name' => 'Bismika Interior',
            'email' => 'info@bismikainterior.com',
            'phone' => '081234567891',
            'website_url' => 'https://bismikainterior.com',
        ]);

        Client::create([
            'name' => 'Sibatur',
            'email' => 'info@sibatur.id',
            'phone' => '089876543211',
            'website_url' => 'https://sibatur.id',
        ]);

        Client::create([
            'name' => 'Lateevadinamika',
            'email' => 'info@lateevadinamika.co.id',
            'phone' => '081234567892',
            'website_url' => 'https://lateevadinamika.co.id',
        ]);

        Client::create([
            'name' => 'Mindful',
            'email' => 'info@mindfulapp.com',
            'phone' => '089876543212',
            'website_url' => 'https://mindfulapp.com',
        ]);
    }
}
