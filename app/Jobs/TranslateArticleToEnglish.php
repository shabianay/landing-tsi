<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Translates an Indonesian article to English via OpenRouter.
 * Dispatched after the HTTP response so saving an article never waits on the AI API.
 */
class TranslateArticleToEnglish
{
    use Dispatchable;

    private const OPENROUTER_URL = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct(public int $articleId) {}

    public function handle(): void
    {
        $article = Article::find($this->articleId);
        if (! $article) return;

        // Up to 3 models x 60s; give the post-response work enough time.
        @set_time_limit(240);

        $apiKey = config('services.openrouter.api_key');
        if (!$apiKey) return;

        $article->load('translations');

        $hasEnContent = $article->translations
            ->where('locale', 'en')
            ->where('key', 'content')
            ->first()?->value;

        if ($hasEnContent) return;

        $idContent = $article->translations
            ->where('locale', 'id')
            ->where('key', 'content')
            ->first()?->value ?? $article->content;

        if (!$idContent || trim($idContent) === '') return;

        $idTitle = $article->translations
            ->where('locale', 'id')
            ->where('key', 'title')
            ->first()?->value ?? $article->title;

        $idExcerpt = $article->translations
            ->where('locale', 'id')
            ->where('key', 'excerpt')
            ->first()?->value ?? $article->excerpt;

        $idSeoTitle = $article->translations
            ->where('locale', 'id')
            ->where('key', 'seo_title')
            ->first()?->value ?? $article->seo_title;

        $idSeoDesc = $article->translations
            ->where('locale', 'id')
            ->where('key', 'seo_description')
            ->first()?->value ?? $article->seo_description;

        $idCategory = $article->translations
            ->where('locale', 'id')
            ->where('key', 'category')
            ->first()?->value ?? $article->category;

        $prompt = <<<PROMPT
Translate the following Indonesian blog article into natural English for a professional audience. Keep all formatting (newlines, paragraph breaks). Respond ONLY with valid JSON (no markdown, no backticks).

Indonesian article:
Title: {$idTitle}
Category: {$idCategory}
Excerpt: {$idExcerpt}
SEO Title: {$idSeoTitle}
SEO Description: {$idSeoDesc}
Content:
{$idContent}

Output JSON format:
{
  "title": "English title (max 60 chars, SEO-friendly)",
  "category": "English category",
  "excerpt": "English excerpt (max 160 chars)",
  "seo_title": "English SEO title (max 60 chars)",
  "seo_description": "English meta description (max 160 chars)",
  "content": "Full English translation, preserve all structure and formatting"
}
PROMPT;

        $models = [
            'deepseek/deepseek-chat',
            'meta-llama/llama-3.1-8b-instruct',
            'google/gemini-2.0-flash-lite-001',
        ];

        foreach ($models as $model) {
            try {
                $response = Http::timeout(60)->connectTimeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post(self::OPENROUTER_URL, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 4096,
                        'temperature' => 0.4,
                    ]);

                if (!$response->successful()) continue;

                $text = $response->json()['choices'][0]['message']['content'] ?? '';
                $text = trim($text);
                $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
                $text = preg_replace('/\s*```$/i', '', $text);

                $en = json_decode($text, true);
                if (!$en || !isset($en['title'], $en['content'])) continue;

                $article->setTranslation('title', 'en', $en['title']);
                $article->setTranslation('excerpt', 'en', $en['excerpt'] ?? '');
                $article->setTranslation('content', 'en', $en['content']);
                $article->setTranslation('seo_title', 'en', $en['seo_title'] ?? $en['title']);
                $article->setTranslation('seo_description', 'en', $en['seo_description'] ?? $en['excerpt'] ?? '');
                $article->setTranslation('category', 'en', $en['category'] ?? $article->category);

                Log::info("Auto-translate EN success for article {$article->id}");
                return;
            } catch (\Exception $e) {
                Log::warning("Auto-translate {$model} failed: " . $e->getMessage());
            }
        }
    }
}
