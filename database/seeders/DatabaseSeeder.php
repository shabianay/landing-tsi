<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@thesolutionid.com',
            'is_admin' => true,
        ]);

        $this->call([
            ClientSeeder::class,
            ProjectSeeder::class,
            TestimonialSeeder::class,
            ArticleSeeder::class,
            InitialDataSeeder::class,
            FaqSeeder::class,
            PopupSeeder::class,
            TranslationSeeder::class,
            EnglishTranslationSeeder::class,
            ArticleTranslationSeeder::class,
            PartnerDescriptionSeeder::class,
            ArticleCategoryTranslationSeeder::class,
        ]);
    }
}
