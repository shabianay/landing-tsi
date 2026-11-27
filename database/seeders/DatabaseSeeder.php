<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
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

        $password = env('ADMIN_PASSWORD') ?: Str::password(24);

        User::factory()->create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL') ?: 'admin@thesolutionid.com',
            'password' => $password,
            'is_admin' => true,
        ]);

        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn('Generated admin password (store it now, it will not be shown again): '.$password);
        }

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
