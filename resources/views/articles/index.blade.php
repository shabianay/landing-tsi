@extends('layouts.master')

@section('title', $pageTitle)
@section('meta_description', $metaDesc)
@section('og_title', $ogTitle)
@section('og_description', $ogDesc)
@section('meta_keywords', __('artikel, blog, digital marketing, website, SEO, The Solution ID'))

@section('content')
    <x-page-header
        :title="__('Tips & Strategi') . ' ' . __('Digital Marketing')"
        :lede="__('Baca artikel dan panduan terbaru tentang pembuatan website, SEO, dan strategi digital untuk mengembangkan bisnis Anda.')"
        :crumbs="[['label' => __('Artikel & Blog')]]">
        <form action="{{ route('articles.index') }}" method="GET" role="search">
            @if ($categoryTerm)
                <input type="hidden" name="category" value="{{ $categoryTerm }}">
            @endif
            <label for="article-search" class="field-label">{{ __('Cari artikel...') }}</label>
            <div class="flex">
                <input id="article-search" type="search" name="q" value="{{ $searchTerm }}" maxlength="100"
                       class="field !rounded-r-none" placeholder="{{ __('Cari artikel...') }}">
                <button type="submit" class="btn-ink !min-h-0 !rounded-l-none !px-4" aria-label="{{ __('Cari') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true"><path d="m21 21-5.2-5.2M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                </button>
            </div>
        </form>
    </x-page-header>

    <section class="py-10 md:py-16">
        <div class="wrap">
            @if ($categories->isNotEmpty())
                <nav aria-label="{{ __('Kategori') }}" class="-mx-5 mb-10 overflow-x-auto px-5 sm:mx-0 sm:px-0">
                    <ul class="flex gap-2 whitespace-nowrap sm:flex-wrap">
                        <li>
                            <a href="{{ route('articles.index', array_filter(['q' => $searchTerm])) }}" @if(! $categoryTerm) aria-current="page" @endif
                               class="inline-flex min-h-10 items-center border px-4 text-sm font-semibold transition-colors {{ ! $categoryTerm ? 'border-dark-300 bg-dark-300 text-white' : 'border-line bg-white text-dark-100 hover:border-dark-300 hover:text-dark-300' }}">
                                {{ __('Semua') }}
                            </a>
                        </li>
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('articles.index', array_filter(['category' => $cat, 'q' => $searchTerm])) }}" @if($categoryTerm === $cat) aria-current="page" @endif
                                   class="inline-flex min-h-10 items-center border px-4 text-sm font-semibold transition-colors {{ $categoryTerm === $cat ? 'border-dark-300 bg-dark-300 text-white' : 'border-line bg-white text-dark-100 hover:border-dark-300 hover:text-dark-300' }}">
                                    {{ $cat }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            @if ($searchTerm)
                <p class="mb-8 text-dark-100">
                    {{ __('Hasil pencarian') }}: <strong class="text-dark-300">“{{ $searchTerm }}”</strong>
                    · <a href="{{ route('articles.index', array_filter(['category' => $categoryTerm])) }}" class="font-semibold text-primary-700 underline">{{ __('Hapus pencarian') }}</a>
                </p>
            @endif

            @if ($articles->isNotEmpty())
                <ul class="grid gap-x-6 gap-y-12 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($articles as $article)
                        <li>
                            <a href="{{ route('articles.show', $article->slug) }}" class="group block">
                                <div class="relative aspect-[16/10] overflow-hidden border border-line bg-dark-50">
                                    @if ($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->alt_text ?? $article->trans('title') }}"
                                             width="600" height="375" loading="lazy" decoding="async"
                                             class="h-full w-full object-cover transition-transform duration-700 ease-out-expo group-hover:scale-[1.03]">
                                    @else
                                        <span class="absolute inset-0 flex items-center justify-center"><x-blockmark size="1.5rem" /></span>
                                    @endif
                                </div>
                                <p class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-dark-100">
                                    @if ($article->category)
                                        <span class="font-semibold text-primary-700">{{ $article->trans('category') }}</span>
                                    @endif
                                    @if ($article->published_at)
                                        <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->translatedFormat('d M Y') }}</time>
                                    @endif
                                </p>
                                <h2 class="mt-2 line-clamp-3 text-lg font-bold leading-snug text-dark-300 group-hover:underline">{{ $article->trans('title') }}</h2>
                                <p class="mt-2 line-clamp-2 text-[0.95rem] text-dark-100">
                                    {{ $article->trans('excerpt') ?? Str::limit(strip_tags($article->trans('content')), 120) }}
                                </p>
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if ($articles->hasPages())
                    <div class="mt-16 border-t border-line pt-8">
                        {{ $articles->links() }}
                    </div>
                @endif
            @else
                <div class="border border-dashed border-dark-100/40 px-6 py-16 text-center">
                    <x-blockmark size="1.25rem" class="mx-auto" />
                    <p class="mt-6 text-lg text-dark-100">{{ __('Belum ada artikel.') }}</p>
                    @if ($searchTerm || $categoryTerm)
                        <a href="{{ route('articles.index') }}" class="link-arrow mt-4">{{ __('Lihat Semua') }} <x-icon-arrow /></a>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
