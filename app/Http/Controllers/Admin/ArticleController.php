<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['title', 'content', 'excerpt', 'seo_title', 'seo_description', 'category'];

    private const OPENROUTER_URL = 'https://openrouter.ai/api/v1/chat/completions';

    public function index()
    {
        $articles = Article::with('translations')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'nullable|max:255|unique:articles,slug',
            'image' => 'nullable|image|mimes:webp,jpg,jpeg,png|max:5120',
            'og_image' => 'nullable|image|mimes:webp,jpg,jpeg,png|max:5120',
            'alt_text' => 'nullable|max:255',
            'author' => 'nullable|max:255',
            'category' => 'nullable|max:255',
            'tags' => 'nullable',
            'seo_keywords' => 'nullable',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['title'] = $request->input('trans_title_id', '');
        $validated['content'] = $request->input('trans_content_id', '');
        $validated['excerpt'] = $request->input('trans_excerpt_id');
        $validated['seo_title'] = $request->input('trans_seo_title_id');
        $validated['seo_description'] = $request->input('trans_seo_description_id');
        $validated['category'] = $request->input('trans_category_id');
        $validated['slug'] = $validated['slug'] ?? ($validated['title'] ? Str::slug($validated['title']) : Str::random(10));
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? ($validated['published_at'] ?? now()) : null;

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadAndConvertToWebp($request->file('image'));
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $this->uploadAndConvertToWebp($request->file('og_image'));
        }

        $article = Article::create($validated);
        $this->saveTranslations($request, $article, $this->translatable);

        $this->autoTranslate($article);

        return redirect()->route('admin.articles.index')
            ->with('success', __('Artikel berhasil ditambahkan.'));
    }

    public function edit(Article $article)
    {
        $article->load('translations');
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'slug' => 'nullable|max:255|unique:articles,slug,' . $article->id,
            'image' => 'nullable|image|mimes:webp,jpg,jpeg,png|max:5120',
            'og_image' => 'nullable|image|mimes:webp,jpg,jpeg,png|max:5120',
            'alt_text' => 'nullable|max:255',
            'author' => 'nullable|max:255',
            'category' => 'nullable|max:255',
            'tags' => 'nullable',
            'seo_keywords' => 'nullable',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['title'] = $request->input('trans_title_id', $article->title);
        $validated['content'] = $request->input('trans_content_id', $article->content);
        $validated['excerpt'] = $request->input('trans_excerpt_id', $article->excerpt);
        $validated['seo_title'] = $request->input('trans_seo_title_id', $article->seo_title);
        $validated['seo_description'] = $request->input('trans_seo_description_id', $article->seo_description);
        $validated['category'] = $request->input('trans_category_id', $article->category);
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? ($validated['published_at'] ?? $article->published_at ?? now()) : null;

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $validated['image'] = $this->uploadAndConvertToWebp($request->file('image'));
        } elseif ($request->boolean('remove_image') && $article->image) {
            Storage::disk('public')->delete($article->image);
            $validated['image'] = null;
        }

        if ($request->hasFile('og_image')) {
            if ($article->og_image) {
                Storage::disk('public')->delete($article->og_image);
            }
            $validated['og_image'] = $this->uploadAndConvertToWebp($request->file('og_image'));
        } elseif ($request->boolean('remove_og_image') && $article->og_image) {
            Storage::disk('public')->delete($article->og_image);
            $validated['og_image'] = null;
        }

        $article->update($validated);
        $this->saveTranslations($request, $article, $this->translatable);

        $this->autoTranslate($article);

        return redirect()->route('admin.articles.index')
            ->with('success', __('Artikel berhasil diperbarui.'));
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        if ($article->og_image) {
            Storage::disk('public')->delete($article->og_image);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', __('Artikel berhasil dihapus.'));
    }

    private function autoTranslate(Article $article): void
    {
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

    private function uploadAndConvertToWebp($file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40) . '.webp';

        $dir = storage_path('app/public/articles');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if ($ext === 'webp') {
            $file->storeAs('articles', $filename, 'public');
            return 'articles/' . $filename;
        }

        [$width, $height] = getimagesize($file->getRealPath());

        $src = match ($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'png' => imagecreatefrompng($file->getRealPath()),
            default => null,
        };

        if (!$src) {
            $file->storeAs('articles', $filename, 'public');
            return 'articles/' . $filename;
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

        imagewebp($src, $dir . '/' . $filename, 80);
        imagedestroy($src);

        return 'articles/' . $filename;
    }
}
