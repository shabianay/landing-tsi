@extends('layouts.master')

@section('title', ($article->trans('seo_title') ?? $article->trans('title')) . ' — The Solution ID')
@section('meta_description', $article->trans('seo_description') ?? ($article->trans('excerpt') ?? Str::limit(strip_tags($article->trans('content')), 160)))
@section('og_title', $article->trans('title') . ' — The Solution ID')
@section('og_description', $article->trans('seo_description') ?? ($article->trans('excerpt') ?? Str::limit(strip_tags($article->trans('content')), 160)))
@section('og_image', $article->og_image ? asset('storage/' . $article->og_image) : ($article->image ? asset('storage/' . $article->image) : asset('storage/og-default.jpg')))
@section('canonical', route('articles.show', $article->slug))
@section('meta_keywords', $article->seo_keywords ?? '')

@section('content')
<article x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">
    {{-- Skeleton Hero --}}
    <section x-show="!loaded" class="relative overflow-hidden bg-dark-300">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/60 via-dark-300 to-dark-300"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 lg:pt-40 pb-16 lg:pb-24">
            <div class="h-4 w-32 skeleton mb-10"></div>
            <div class="flex gap-3 mb-6">
                <div class="h-6 w-20 skeleton rounded-full"></div>
                <div class="h-6 w-24 skeleton"></div>
            </div>
            <div class="h-12 w-full skeleton mb-3"></div>
            <div class="h-12 w-3/4 skeleton mb-6"></div>
            <div class="h-6 w-2/3 skeleton"></div>
        </div>
    </section>

    {{-- Skeleton Content --}}
    <section x-show="!loaded" class="py-16 lg:py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="aspect-[16/9] skeleton rounded-3xl -mt-32 mb-12 relative z-20"></div>
            <div class="space-y-3">
                <div class="h-4 w-full skeleton"></div>
                <div class="h-4 w-full skeleton"></div>
                <div class="h-4 w-5/6 skeleton"></div>
                <div class="h-4 w-full skeleton"></div>
                <div class="h-4 w-4/5 skeleton"></div>
                <div class="h-4 w-full skeleton"></div>
                <div class="h-4 w-3/4 skeleton"></div>
            </div>
        </div>
    </section>

    {{-- Skeleton Related --}}
    <section x-show="!loaded" class="py-20 lg:py-28 bg-dark-50 section-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-8 w-48 skeleton mb-2"></div>
            <div class="h-5 w-64 skeleton mb-12"></div>
            <div class="grid md:grid-cols-3 gap-8">
                @for($i = 0; $i < 3; $i++)
                <div class="bg-white rounded-2xl overflow-hidden border border-dark-100/5 shadow-lg">
                    <div class="aspect-[16/10] skeleton"></div>
                    <div class="p-5 space-y-2">
                        <div class="h-3 w-20 skeleton"></div>
                        <div class="h-5 w-full skeleton"></div>
                        <div class="h-5 w-2/3 skeleton"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </section>
    {{-- Real Hero --}}
    <section x-show="loaded" x-cloak class="relative overflow-hidden bg-dark-300">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/60 via-dark-300 to-dark-300 z-0"></div>
        <div class="glow top-0 -left-40 w-[500px] h-[500px] bg-primary-500/20 z-0"></div>
        <div class="glow bottom-0 -right-40 w-[500px] h-[500px] bg-blue-500/10 z-0"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 lg:pt-40 pb-16 lg:pb-24">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center text-sm font-bold text-white/50 hover:text-white gap-2 transition-all mb-10 group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('Kembali ke Artikel') }}
            </a>

            <nav aria-label="Breadcrumb" class="mb-10">
                <ol class="flex flex-wrap items-center gap-1.5 text-sm font-medium">
                    <li><a href="{{ url('/') }}" class="text-white/30 hover:text-white transition-colors">Home</a></li>
                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><a href="{{ route('articles.index') }}" class="text-white/30 hover:text-white transition-colors">{{ __('Artikel') }}</a></li>
                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-white/50">{{ $article->trans('title') }}</span></li>
                </ol>
            </nav>

            <div class="flex flex-wrap items-center gap-3 mb-6">
                @if($article->category)
                <span class="px-4 py-1.5 bg-white/10 text-white/80 rounded-full text-xs font-semibold border border-white/10 backdrop-blur-sm">{{ $article->trans('category') }}</span>
                @endif
                @if($article->published_at)
                <span class="text-white/40 text-sm font-medium">{{ $article->published_at->format('d M Y') }}</span>
                @endif
                @if($article->views)
                <span class="text-white/30 text-sm font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ number_format($article->views) }}
                </span>
                @endif
                <span class="text-white/30 text-sm">—</span>
                <span class="text-white/40 text-sm font-medium">{{ $article->author }}</span>
                @if($article->tags)
                <span class="text-white/30 text-sm">—</span>
                <span class="text-white/40 text-sm font-medium">{{ $article->tags }}</span>
                @endif
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-[1.1] tracking-tight">
                {{ $article->trans('title') }}
            </h1>
            @if($article->trans('excerpt'))
            <p class="text-lg text-white/60 mt-6 max-w-2xl leading-relaxed font-medium">{{ $article->trans('excerpt') }}</p>
            @endif
        </div>
    </section>

    {{-- Real Content --}}
    <section x-show="loaded" x-cloak class="py-16 lg:py-24 bg-white section-gradient"><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($article->image)
            <div class="rounded-3xl overflow-hidden shadow-2xl shadow-dark-300/10 mb-12 -mt-32 relative z-20">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->alt_text ?? $article->trans('title') }}"
                     width="1200" height="675" decoding="async"
                     class="w-full h-auto">
            </div>
            @endif

            <div class="prose prose-lg max-w-none prose-headings:text-dark-300 prose-headings:font-bold prose-a:text-primary-500 prose-a:font-semibold prose-img:rounded-2xl prose-img:shadow-lg prose-blockquote:border-primary-500 prose-blockquote:bg-primary-50/50 prose-blockquote:py-2 prose-blockquote:px-6 prose-blockquote:rounded-2xl prose-p:text-dark-100/70 prose-p:leading-relaxed prose-li:text-dark-100/70 prose-strong:text-dark-300">
                {!! nl2br(e($article->trans('content'))) !!}
            </div>

            @if($article->tags)
            <div class="flex flex-wrap gap-2 mt-12 pt-8 border-t border-dark-100/5">
                @foreach(explode(',', $article->tags) as $tag)
                <span class="px-4 py-1.5 bg-dark-50 text-dark-100/60 rounded-lg text-xs font-medium">{{ trim($tag) }}</span>
                @endforeach
            </div>
            @endif

            {{-- Share --}}
            <div class="mt-10 pt-8 border-t border-dark-100/5 flex flex-wrap items-center justify-between gap-4">
                <span class="text-sm font-bold text-dark-300">{{ __('Bagikan Artikel') }}</span>
                <div class="flex gap-3">
                    <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . route('articles.show', $article->slug)) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-xl bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center transition-colors"
                       aria-label="{{ __('Bagikan ke WhatsApp') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors"
                       aria-label="{{ __('Bagikan ke Facebook') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('articles.show', $article->slug)) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-100 flex items-center justify-center transition-colors"
                       aria-label="{{ __('Bagikan ke Twitter') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</article>

{{-- Real Related Articles --}}
@if($related->isNotEmpty())
<section x-show="loaded" x-cloak class="py-20 lg:py-28 bg-dark-50 section-gradient border-t border-dark-100/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-dark-300">{{ __('Artikel Terkait') }}</h2>
                <p class="text-dark-100/50 font-medium mt-2">{{ __('Baca juga artikel menarik lainnya') }}</p>
            </div>
            <a href="{{ route('articles.index') }}" class="text-sm font-bold text-primary-500 hover:text-primary-600 transition-colors shrink-0 hidden sm:inline-flex items-center gap-1 group">
                {{ __('Lihat Semua') }}
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($related as $r)
            <a href="{{ route('articles.show', $r->slug) }}"
               class="group bg-white rounded-2xl overflow-hidden border border-dark-100/5 shadow-lg shadow-dark-300/5 hover:border-primary-500/20 hover:shadow-xl hover:shadow-primary-500/10 hover:-translate-y-0.5 transition-all duration-300">
                <div class="aspect-[16/10] bg-gradient-to-br from-primary-50 to-primary-100 overflow-hidden">
                    @if($r->image)
                    <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->alt_text ?? $r->trans('title') }}" width="600" height="375" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-primary-300/50 font-bold text-4xl">{{ substr($r->trans('title'), 0, 2) }}</div>
                    @endif
                </div>
                <div class="p-5">
                    <span class="text-xs text-dark-100/40 font-medium">{{ $r->published_at?->format('d M Y') }}</span>
                    <h3 class="font-bold text-dark-300 mt-1.5 group-hover:text-primary-600 transition-colors line-clamp-2">{{ $r->trans('title') }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Schema.org Article --}}
@php
    $articleSchema = [
        '@@context' => 'https://schema.org',
        '@@type' => 'Article',
        'headline' => $article->title,
        'description' => $article->excerpt ?? Str::limit(strip_tags($article->content), 160),
        'image' => $article->image ? asset('storage/' . $article->image) : asset('storage/og-default.jpg'),
        'author' => [
            '@@type' => 'Organization',
            'name' => 'The Solution ID',
        ],
        'publisher' => [
            '@@type' => 'Organization',
            'name' => 'The Solution ID',
            'logo' => [
                '@@type' => 'ImageObject',
                'url' => asset(\App\Models\Setting::get('logo', 'logo.webp')),
            ],
        ],
        'datePublished' => $article->published_at?->toIso8601String(),
        'dateModified' => $article->updated_at->toIso8601String(),
        'mainEntityOfPage' => [
            '@@type' => 'WebPage',
            '@@id' => route('articles.show', $article->slug),
        ],
    ];
@endphp
<script type="application/ld+json">{{ json_encode($articleSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</script>
@endsection
