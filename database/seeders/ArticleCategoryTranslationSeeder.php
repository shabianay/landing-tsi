<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleCategoryTranslationSeeder extends Seeder
{
    private array $categoryMap = [
        'Teknologi' => 'Technology',
        'Bisnis' => 'Business',
        'E-commerce' => 'E-commerce',
        'Startup' => 'Startup',
        'Digital Marketing' => 'Digital Marketing',
        'SEO' => 'SEO',
        'Website' => 'Website',
        'Tips' => 'Tips',
        'Web Development' => 'Web Development',
        'Desain' => 'Design',
    ];

    public function run(): void
    {
        $count = 0;
        foreach (Article::all() as $article) {
            if ($article->category && isset($this->categoryMap[$article->category])) {
                $enCategory = $this->categoryMap[$article->category];

                // Check if EN translation already exists
                $existingEn = $article->translations
                    ->where('locale', 'en')
                    ->where('key', 'category')
                    ->first();

                if (!$existingEn || !$existingEn->value) {
                    $article->setTranslation('category', 'en', $enCategory);
                    $count++;
                }
            }
        }

        $this->command->info("Category EN translations set for {$count} articles.");
    }
}
