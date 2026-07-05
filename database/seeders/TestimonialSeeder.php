<?php

namespace Database\Seeders;

use App\Models\Testimonial;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Testimonial::create([
            'client_name' => 'Ahmad Fauzi',
            'company_name' => 'Virlab',
            'content' => 'Website yang dibuat sangat profesional dan sesuai dengan identitas bisnis kami. Hasilnya melebihi ekspektasi!',
            'rating' => 5,
        ]);

        Testimonial::create([
            'client_name' => 'Sari Dewi',
            'company_name' => 'Bismika Interior',
            'content' => 'Pelayanan sangat baik, konsultasi gratis sangat membantu kami yang awam tentang website.',
            'rating' => 5,
        ]);

        Testimonial::create([
            'client_name' => 'Budi Santoso',
            'company_name' => 'Lateevadinamika',
            'content' => 'SEO nya benar-benar terasa. Setelah website diluncurkan, pelanggan baru mulai berdatangan dari Google.',
            'rating' => 5,
        ]);
    }
}
