<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $query = Article::published();

        $search = request()->query('q');
        $category = request()->query('category');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : null;
        $category = is_string($category) ? mb_substr(trim($category), 0, 255) : null;

        if ($search) {
            $search = addcslashes($search, '\\%_');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $articles = $query->recent()->paginate(9)->withQueryString();
        $categories = Article::published()->select('category')->distinct()->pluck('category')->filter();

        $searchTerm = is_string(request()->query('q')) ? mb_substr(trim(request()->query('q')), 0, 100) : null;
        $categoryTerm = $category;

        $pageTitle = $searchTerm
            ? __('Hasil Pencarian') . ': ' . $searchTerm
            : ($categoryTerm ? $categoryTerm . ' — ' . __('Artikel') : __('Artikel & Blog') . ' — The Solution ID');
        $metaDesc = $searchTerm
            ? __('Hasil pencarian artikel untuk') . ' "' . $searchTerm . '" ' . __('di The Solution ID.')
            : ($categoryTerm
                ? __('Kumpulan artikel kategori') . ' ' . $categoryTerm . ' ' . __('dari The Solution ID.')
                : __('Baca artikel dan blog terbaru dari The Solution ID tentang tips website, SEO, dan strategi digital untuk bisnis Indonesia.'));
        $ogTitle = $pageTitle;
        $ogDesc = $metaDesc;

        return view('articles.index', compact('articles', 'categories', 'pageTitle', 'metaDesc', 'ogTitle', 'ogDesc', 'searchTerm', 'categoryTerm'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->published()->firstOrFail();

        $viewedKey = 'viewed_articles.'.$article->id;
        if (! session()->has($viewedKey)) {
            $article->increment('views');
            session()->put($viewedKey, true);
        }

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                if ($article->category) {
                    $q->where('category', $article->category);
                }
            })
            ->recent()
            ->take(3)
            ->get();

        if ($related->count() < 3 && $article->category) {
            $excludeIds = $related->pluck('id')->push($article->id);
            $fallback = Article::published()
                ->whereNotIn('id', $excludeIds)
                ->recent()
                ->take(3 - $related->count())
                ->get();
            $related = $related->concat($fallback);
        }

        return view('articles.show', compact('article', 'related'));
    }
}
