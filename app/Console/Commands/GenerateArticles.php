<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateArticles
{
    private const OPENROUTER_URL = 'https://openrouter.ai/api/v1/chat/completions';

    private array $models = [
        'deepseek/deepseek-chat',
        'meta-llama/llama-3.1-8b-instruct',
        'google/gemini-2.0-flash-lite-001',
    ];

    private array $categories = [
        'Digital Marketing',
        'SEO',
        'Website',
        'Tips',
        'Teknologi',
    ];

    public function handle(): array
    {
        $apiKey = config('services.openrouter.api_key');

        if (!$apiKey) {
            return ['success' => false, 'message' => 'OPENROUTER_API_KEY tidak ditemukan di .env' . PHP_EOL . 'Daftar gratis di https://openrouter.ai/ lalu dapatkan API key.'];
        }

        $todayCount = Article::whereDate('created_at', today())->count();
        if ($todayCount > 0) {
            return ['success' => false, 'message' => "Hari ini sudah ada {$todayCount} artikel. Lewati."];
        }

        $category = $this->pickCategory();
        $prompt = $this->buildPrompt($category);

        $lastError = '';
        foreach ($this->models as $model) {
            $result = $this->fetchFromOpenRouter($prompt, $model, $apiKey);
            if ($result && !isset($result['error'])) {
                $imageResult = $this->fetchImageForArticle($category, $result['title']);
                $imagePath = $imageResult['path'] ?? null;
                $pexelsPhotoId = $imageResult['pexels_photo_id'] ?? null;

                $article = Article::create([
                    'title' => $result['title'],
                    'slug' => Str::slug($result['title']),
                    'content' => $result['content'],
                    'excerpt' => $result['excerpt'],
                    'image' => $imagePath,
                    'alt_text' => $result['title'],
                    'pexels_photo_id' => $pexelsPhotoId,
                    'category' => $category,
                    'author' => 'The Solution ID',
                    'tags' => $result['tags'],
                    'seo_title' => $result['seo_title'] ?? $result['title'],
                    'seo_description' => $result['seo_description'] ?? $result['excerpt'],
                    'seo_keywords' => $result['seo_keywords'] ?? $result['tags'],
                    'is_published' => true,
                    'published_at' => now(),
                ]);

                // Generate English translation via AI
                $this->translateArticle($article, $model, $apiKey);

                return ['success' => true, 'message' => "Artikel berhasil dibuat ({$model}): {$article->title}"];
            }
            $lastError = $result['error'] ?? 'Unknown error';
            Log::warning("OpenRouter model {$model} gagal", ['error' => $lastError]);
        }

        return ['success' => false, 'message' => "Semua model gagal. Error terakhir: {$lastError}"];
    }

    private function pickCategory(): string
    {
        $weights = [4, 3, 3, 2, 2];
        $total = array_sum($weights);
        $rand = mt_rand(1, $total);
        $cumulative = 0;

        foreach ($this->categories as $i => $cat) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $cat;
            }
        }

        return $this->categories[0];
    }

    private function buildPrompt(string $category): string
    {
        $isIndonesian = app()->getLocale() === 'id';
        $langLabel = $isIndonesian ? 'Indonesia' : 'Inggris';
        $greet = $isIndonesian ? 'Indonesia yang baik dan sopan, gunakan "Anda" untuk sapaan' : 'Inggris yang natural';

        return <<<PROMPT
Kamu adalah content writer untuk website The Solution ID, sebuah agency digital marketing dan pembuatan website di Indonesia.

Buat SATU artikel blog {$category} dalam bahasa {$langLabel} dengan format JSON berikut (jangan pakai markdown, hanya JSON valid):

{
  "title": "Judul artikel menarik, maksimal 60 karakter, mengandung kata kunci utama",
  "excerpt": "Ringkasan 1-2 kalimat, maksimal 160 karakter, menggambarkan isi artikel",
  "tags": "tag1, tag2, tag3, tag4, tag5",
  "seo_title": "Judul SEO maksimal 60 karakter",
  "seo_description": "Meta deskripsi maksimal 160 karakter",
  "seo_keywords": "keyword1, keyword2, keyword3, keyword4",
  "content": "Konten artikel lengkap minimal 500 kata, paragraf terstruktur dengan sub-judul (format teks biasa, gunakan newline untuk pemisah, jangan pakai markdown atau HTML)"
}

Aturan:
- Judul harus menarik dan mengandung kata kunci {$category}
- Konten informatif, praktis, dan relevan untuk bisnis Indonesia
- Gunakan angka, contoh nyata, dan tips yang actionable
- Bahasa {$greet}
- JANGAN gunakan markdown, HTML, atau backticks
- Output hanya JSON valid, tanpa teks lain
PROMPT;
    }

    private function fetchFromOpenRouter(string $prompt, string $model, string $apiKey): ?array
    {
        try {
            $response = Http::timeout(120)
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
                    'temperature' => 0.8,
                ]);

            if (!$response->successful()) {
                $errMsg = $response->json()['error']['message'] ?? $response->body();
                Log::warning("OpenRouter {$model} error", ['status' => $response->status(), 'message' => $errMsg]);
                return ['error' => "{$model}: HTTP {$response->status()} - {$errMsg}"];
            }

            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? null;

            if (!$text) {
                return ['error' => "{$model}: Respon kosong."];
            }

            $text = trim($text);
            $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
            $text = preg_replace('/\s*```$/i', '', $text);

            $parsed = json_decode($text, true);

            if (!$parsed || !isset($parsed['title'], $parsed['content'])) {
                return ['error' => "{$model}: Gagal parse JSON: " . substr($text, 0, 300)];
            }

            return $parsed;
        } catch (\Exception $e) {
            Log::error("OpenRouter {$model} exception: " . $e->getMessage());
            return ['error' => "{$model}: " . $e->getMessage()];
        }
    }

    private function fetchImageForArticle(string $category, string $title): ?array
    {
        $categoryQueries = [
            'Digital Marketing' => 'social media marketing strategy business',
            'SEO' => 'search engine optimization analytics data',
            'Website' => 'web design development coding computer',
            'Tips' => 'business meeting presentation office',
            'Teknologi' => 'technology innovation digital future',
        ];

        $query = $categoryQueries[$category] ?? 'business technology';
        $pexelsKey = config('services.pexels.api_key');

        if ($pexelsKey) {
            try {
                // Get already used Pexels photo IDs
                $usedPhotoIds = Article::whereNotNull('pexels_photo_id')->pluck('pexels_photo_id')->toArray();
                $page = 1;
                $photos = [];
                while (true) {
                    $response = Http::timeout(15)
                        ->withHeaders(['Authorization' => $pexelsKey])
                        ->get('https://api.pexels.com/v1/search', [
                            'query' => $query,
                            'per_page' => 10, // Fetch more to find unused ones
                            'orientation' => 'landscape',
                            'page' => $page,
                        ]);

                    if (!$response->successful()) {
                        Log::warning("Pexels search error for new article: " . $response->body());
                        break;
                    }

                    $currentPhotos = $response->json()['photos'] ?? [];
                    if (empty($currentPhotos)) break;
                    $photos = array_merge($photos, $currentPhotos);

                    if (count($currentPhotos) < 10) break; // Less than per_page means no more pages
                    $page++;

                    // Limit deep search to avoid too many API calls
                    if ($page > 5) break;
                }

                $selectedPhoto = null;
                foreach ($photos as $photo) {
                    if (!in_array($photo['id'], $usedPhotoIds)) {
                        $selectedPhoto = $photo;
                        break;
                    }
                }

                if ($selectedPhoto) {
                    $imgUrl = $selectedPhoto['src']['large'] ?? $selectedPhoto['src']['medium'] ?? null;
                    if ($imgUrl) {
                        $path = $this->downloadAndSaveImage($imgUrl);
                        if ($path) {
                            return ['path' => $path, 'pexels_photo_id' => $selectedPhoto['id']];
                        }
                    }
                } else {
                    Log::warning("No unused Pexels photos found for new article with query '{$query}'.");
                }
            } catch (\Exception $e) {
                Log::warning('Pexels fetch failed for new article: ' . $e->getMessage());
            }
        }

        // Fallback to Lorem Picsum if Pexels fails or no unused photos found
        try {
            $path = $this->downloadAndSaveImage('https://picsum.photos/1200/675?random=' . mt_rand(10000, 99999));
            if ($path) {
                return ['path' => $path, 'pexels_photo_id' => null];
            }
        } catch (\Exception $e) {
            Log::warning('Picsum fetch failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    private function downloadAndSaveImage(string $url): ?string
    {
        $response = Http::timeout(15)->get($url);
        if (!$response->successful()) {
            return null;
        }

        $filename = Str::random(40) . '.webp';
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        file_put_contents($tempPath, $response->body());

        $info = @getimagesize($tempPath);
        if (!$info) {
            @unlink($tempPath);
            return null;
        }

        [$width, $height] = $info;
        $src = @imagecreatefromstring(file_get_contents($tempPath));
        @unlink($tempPath);

        if (!$src) {
            return null;
        }

        $maxWidth = 1200;
        if ($width > $maxWidth) {
            $ratio = $maxWidth / $width;
            $newW = $maxWidth;
            $newH = (int)round($height * $ratio);
            $resampled = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($resampled, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
            imagedestroy($src);
            $src = $resampled;
        }

        $dir = storage_path('app/public/articles');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $savePath = $dir . '/' . $filename;
        imagewebp($src, $savePath, 80);
        imagedestroy($src);

        return 'articles/' . $filename;
    }

    private function translateArticle(Article $article, string $model, string $apiKey): void
    {
        $enPrompt = <<<PROMPT
Translate the following Indonesian blog article into natural English for a professional audience. Keep all formatting (newlines, paragraph breaks). Respond ONLY with valid JSON (no markdown, no backticks).

Indonesian article:
Title: {$article->title}
Category: {$article->category}
Excerpt: {$article->excerpt}
SEO Title: {$article->seo_title}
SEO Description: {$article->seo_description}
Content:
{$article->content}

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

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post(self::OPENROUTER_URL, [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'user', 'content' => $enPrompt],
                    ],
                    'max_tokens' => 4096,
                    'temperature' => 0.4,
                ]);

            if (!$response->successful()) {
                Log::warning("Translate EN failed: " . $response->body());
                return;
            }

            $text = $response->json()['choices'][0]['message']['content'] ?? '';
            $text = trim($text);
            $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
            $text = preg_replace('/\s*```$/i', '', $text);

            $en = json_decode($text, true);
            if (!$en || !isset($en['title'], $en['content'])) {
                Log::warning("Translate EN parse failed: " . substr($text, 0, 300));
                return;
            }

            $article->setTranslation('title', 'en', $en['title']);
            $article->setTranslation('excerpt', 'en', $en['excerpt'] ?? '');
            $article->setTranslation('content', 'en', $en['content']);
            $article->setTranslation('seo_title', 'en', $en['seo_title'] ?? $en['title']);
            $article->setTranslation('seo_description', 'en', $en['seo_description'] ?? $en['excerpt'] ?? '');
            $article->setTranslation('category', 'en', $en['category'] ?? $article->category);

            $article->setTranslation('title', 'id', $article->title);
            $article->setTranslation('excerpt', 'id', $article->excerpt);
            $article->setTranslation('content', 'id', $article->content);
            $article->setTranslation('seo_title', 'id', $article->seo_title);
            $article->setTranslation('seo_description', 'id', $article->seo_description);
            $article->setTranslation('category', 'id', $article->category);

            Log::info("EN translation saved for article {$article->id}: {$en['title']}");
        } catch (\Exception $e) {
            Log::error("Translate EN exception: " . $e->getMessage());
        }
    }
}

