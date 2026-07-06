<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FillArticleImages
{
    public function handle(): int
    {
        $articles = Article::whereNull('image')->get();
        $updated = 0;
        $total = $articles->count();
        Log::info("FillArticleImages: memproses {$total} artikel.");

        foreach ($articles as $i => $article) {
            Log::info("Memproses {$i}/{$total}: {$article->title}");

            if ($article->image) {
                $oldPath = storage_path('app/public/' . $article->image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $path = $this->fetchImage($article);
            if ($path) {
                $article->update(['image' => $path, 'pexels_photo_id' => $article->pexels_photo_id]);
                $updated++;
                Log::info("Gambar diperbarui: {$article->title} -> {$path}");
            } else {
                Log::warning("Gagal dapat gambar untuk: {$article->title}");
            }
        }

        return $updated;
    }

    private function fetchImage(Article $article): ?string
    {
        $categoryQueries = [
            'Digital Marketing' => 'social media marketing strategy business',
            'SEO' => 'search engine optimization analytics data',
            'Website' => 'web design development coding computer',
            'Tips' => 'business meeting presentation office',
            'Teknologi' => 'technology innovation digital future',
        ];

        $query = $categoryQueries[$article->category] ?? 'business technology';
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
                        Log::warning("Pexels search error for {$article->id}: " . $response->body());
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
                        $path = $this->downloadAndSave($imgUrl);
                        if ($path) {
                            $article->pexels_photo_id = $selectedPhoto['id']; // Save Pexels Photo ID
                            return $path;
                        }
                    }
                } else {
                    Log::warning("No unused Pexels photos found for {$article->id} with query '{$query}'.");
                }
            } catch (\Exception $e) {
                Log::warning("Pexels fetch error for {$article->id}: " . $e->getMessage());
            }
        }

        // Fallback to Lorem Picsum if Pexels fails or no unused photos found
        try {
            return $this->downloadAndSave('https://picsum.photos/1200/675?random=' . mt_rand(10000, 99999));
        } catch (\Exception $e) {
            return null;
        }
    }

    private function downloadAndSave(string $url): ?string
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
}
