<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\TranslateArticleToEnglish;
use App\Models\Article;
use App\Traits\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    use HandlesTranslations;

    protected array $translatable = ['title', 'content', 'excerpt', 'seo_title', 'seo_description', 'category'];

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

        $validated['title'] = $request->input('title.id', '');
        $validated['content'] = $request->input('content.id', '');
        $validated['excerpt'] = $request->input('excerpt.id');
        $validated['seo_title'] = $request->input('seo_title.id');
        $validated['seo_description'] = $request->input('seo_description.id');
        $validated['category'] = $request->input('category.id');
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

        TranslateArticleToEnglish::dispatchAfterResponse($article->id);

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

        $validated['title'] = $request->input('title.id', $article->title);
        $validated['content'] = $request->input('content.id', $article->content);
        $validated['excerpt'] = $request->input('excerpt.id', $article->excerpt);
        $validated['seo_title'] = $request->input('seo_title.id', $article->seo_title);
        $validated['seo_description'] = $request->input('seo_description.id', $article->seo_description);
        $validated['category'] = $request->input('category.id', $article->category);
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

        TranslateArticleToEnglish::dispatchAfterResponse($article->id);

        return redirect()->route('admin.articles.index')
            ->with('success', __('Artikel berhasil diperbarui.'));
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', __('Artikel berhasil dihapus.'));
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
