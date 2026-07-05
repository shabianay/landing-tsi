<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Client;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client1 = Client::first();
        $client2 = Client::latest()->first();

        Project::create([
            'client_id' => $client1->id,
            'title' => 'Virlab Uji Golongan Darah',
            'description' => 'Website Virlab untuk layanan uji golongan darah yang profesional dan mudah diakses.',
            'website_url' => 'https://shabianportfolio.my.id/portfolio/virlabgoldarah',
            'status' => 'completed',
        ]);

        Project::create([
            'client_id' => $client2->id,
            'title' => 'Lapor Vokasi',
            'description' => 'Platform pelaporan untuk institusi vokasi yang responsif dan efisien.',
            'website_url' => 'https://shabianportfolio.my.id/portfolio/lapor-vokasi',
            'status' => 'completed',
        ]);

        Project::create([
            'client_id' => $client1->id,
            'title' => 'Bismika Interior',
            'description' => 'Website portofolio untuk bisnis desain interior yang elegan dan modern.',
            'website_url' => 'https://shabianportfolio.my.id/portfolio/bismika-interior',
            'status' => 'completed',
        ]);

        Project::create([
            'client_id' => $client2->id,
            'title' => 'Sibatur',
            'description' => 'Website informasi untuk layanan Sibatur yang terintegrasi dan user-friendly.',
            'website_url' => 'https://shabianportfolio.my.id/portfolio/sibatur',
            'status' => 'completed',
        ]);

        Project::create([
            'client_id' => $client1->id,
            'title' => 'Lateevadinamika',
            'description' => 'Website korporat untuk perusahaan manufaktur dengan tampilan profesional.',
            'website_url' => 'https://shabianportfolio.my.id/portfolio/lateevadinamika',
            'status' => 'completed',
        ]);

        Project::create([
            'client_id' => $client2->id,
            'title' => 'Mindful',
            'description' => 'Aplikasi kesehatan mental untuk mindfulness dan meditasi dengan UI yang tenang.',
            'website_url' => 'https://shabianportfolio.my.id/portfolio/mindful',
            'status' => 'completed',
        ]);
    }
}
