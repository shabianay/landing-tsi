@extends('layouts.master')

@section('title', ($article->trans('seo_title') ?? $article->trans('title')) . ' — The Solution ID')
@section('meta_description', $article->trans('seo_description') ?? ($article->trans('excerpt') ?? Str::limit(strip_tags($article->trans('content')), 160)))
@section('og_title', $article->trans('title') . ' — The Solution ID')
@section('og_description', $article->trans('seo_description') ?? ($article->trans('excerpt') ?? Str::limit(strip_tags($article->trans('content')), 160)))
@section('og_image', $article->og_image ? asset('storage/' . $article->og_image) : ($article->image ? asset('storage/' . $article->image) : asset('storage/og-default.jpg')))
@section('og_type', 'article')
@section('canonical', route('articles.show', $article->slug))
@section('meta_keywords', $article->seo_keywords ?? '')

@php
    $body = trim($article->trans('content') ?? '');
    $blocks = array_values(array_filter(array_map('trim', preg_split("/\R{2,}/", $body))));
    $wordCount = str_word_count(strip_tags($body));
    $readMinutes = max(1, (int) ceil($wordCount / 200));
    $shareUrl = route('articles.show', $article->slug);
@endphp

@section('content')
    <article>
        <header class="border-b border-line">
            <div class="wrap pb-10 pt-8 md:pb-14 md:pt-12">
                <nav aria-label="Breadcrumb" class="mb-8">
                    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-dark-100">
                        <li><a href="{{ route('home') }}" class="hover:text-dark-300 hover:underline">{{ __('Beranda') }}</a></li>
                        <li class="flex items-center gap-2"><span class="h-1 w-1 bg-dark-100/60" aria-hidden="true"></span><a href="{{ route('articles.index') }}" class="hover:text-dark-300 hover:underline">{{ __('Artikel') }}</a></li>
                        @if ($article->category)
                            <li class="flex items-center gap-2"><span class="h-1 w-1 bg-dark-100/60" aria-hidden="true"></span><a href="{{ route('articles.index', ['category' => $article->category]) }}" class="hover:text-dark-300 hover:underline">{{ $article->trans('category') }}</a></li>
                        @endif
                    </ol>
                </nav>

                <div class="max-w-4xl">
                    <h1 class="display-1 !text-[clamp(2.1rem,1.5rem+2.8vw,4rem)] !leading-[1.05]">{{ $article->trans('title') }}</h1>
                    @if ($article->trans('excerpt'))
                        <p class="lede mt-6">{{ $article->trans('excerpt') }}</p>
                    @endif
                    <p class="mt-8 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-dark-100">
                        @if ($article->author)
                            <span class="font-semibold text-dark-300">{{ $article->author }}</span>
                        @endif
                        @if ($article->published_at)
                            <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->translatedFormat('d F Y') }}</time>
                        @endif
                        <span class="num">{{ $readMinutes }} {{ __('menit baca') }}</span>
                    </p>
                </div>
            </div>
        </header>

        @if ($article->image)
            <div class="wrap pt-8 md:pt-12">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->alt_text ?? $article->trans('title') }}"
                     width="1200" height="675" fetchpriority="high" class="h-auto w-full border border-line">
            </div>
        @endif

        <div class="wrap grid gap-12 py-12 md:py-16 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div class="article-body">
                    @foreach ($blocks as $block)
                        <p>{!! nl2br(e($block)) !!}</p>
                    @endforeach
                </div>

                @if ($article->tags)
                    <ul class="mt-12 flex flex-wrap gap-2 border-t border-line pt-8">
                        @foreach (explode(',', $article->tags) as $tag)
                            @if (trim($tag) !== '')
                                <li class="tag">{{ trim($tag) }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>

            <aside class="lg:col-span-3 lg:col-start-10">
                <div class="space-y-8 lg:sticky lg:top-28">
                    <div>
                        <h2 class="text-sm font-semibold text-dark-300">{{ __('Bagikan Artikel') }}</h2>
                        <ul class="mt-3 flex gap-2">
                            <li>
                                <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . $shareUrl) }}" target="_blank" rel="noopener noreferrer"
                                   class="flex h-11 w-11 items-center justify-center border border-line text-[#178F44] hover:border-dark-300" aria-label="{{ __('Bagikan ke WhatsApp') }}">
                                    <x-icon-whatsapp class="h-5 w-5" />
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
                                   class="flex h-11 w-11 items-center justify-center border border-line text-[#1660C9] hover:border-dark-300" aria-label="{{ __('Bagikan ke Facebook') }}">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
                                   class="flex h-11 w-11 items-center justify-center border border-line text-dark-300 hover:border-dark-300" aria-label="{{ __('Bagikan ke Twitter') }}">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-primary-500 p-6 text-dark-300">
                        <x-blockmark size="0.8rem" class="[&>i]:!bg-dark-300" />
                        <p class="mt-4 font-display text-xl font-bold leading-tight">{{ __('Siap Meningkatkan Bisnis Anda?') }}</p>
                        <a href="{{ route('contact') }}" class="btn-ink mt-5 w-full">{{ __('Konsultasi Gratis') }}</a>
                    </div>
                </div>
            </aside>
        </div>

        @if ($related->isNotEmpty())
            <section class="border-t border-line bg-dark-50 py-14 md:py-20">
                <div class="wrap">
                    <div class="flex items-end justify-between gap-4">
                        <h2 class="display-3">{{ __('Artikel Terkait') }}</h2>
                        <a href="{{ route('articles.index') }}" class="link-arrow shrink-0">{{ __('Lihat Semua') }} <x-icon-arrow /></a>
                    </div>
                    <ul class="mt-8 grid gap-x-6 gap-y-10 md:grid-cols-3">
                        @foreach ($related as $r)
                            <li>
                                <a href="{{ route('articles.show', $r->slug) }}" class="group block">
                                    <div class="relative aspect-[16/10] overflow-hidden border border-line bg-white">
                                        @if ($r->image)
                                            <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->alt_text ?? $r->trans('title') }}" width="600" height="375" loading="lazy" decoding="async"
                                                 class="h-full w-full object-cover transition-transform duration-700 ease-out-expo group-hover:scale-[1.03]">
                                        @else
                                            <span class="absolute inset-0 flex items-center justify-center"><x-blockmark size="1.25rem" /></span>
                                        @endif
                                    </div>
                                    @if ($r->published_at)
                                        <time class="mt-4 block text-sm text-dark-100" datetime="{{ $r->published_at->toDateString() }}">{{ $r->published_at->translatedFormat('d M Y') }}</time>
                                    @endif
                                    <h3 class="mt-1 line-clamp-2 font-bold text-dark-300 group-hover:underline">{{ $r->trans('title') }}</h3>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif
    </article>

    {{-- Schema.org Article --}}
    @php
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt ?? Str::limit(strip_tags($article->content), 160),
            'image' => $article->image ? asset('storage/' . $article->image) : asset('storage/og-default.jpg'),
            'author' => [
                '@type' => 'Organization',
                'name' => 'The Solution ID',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'The Solution ID',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset(\App\Models\Setting::get('logo', 'logo.webp')),
                ],
            ],
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('articles.show', $article->slug),
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($articleSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endsection
