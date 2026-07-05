@extends('layouts.master')

@section('title', $pageTitle)
@section('meta_description', $metaDesc)
@section('og_title', $ogTitle)
@section('og_description', $ogDesc)
@section('meta_keywords', __('artikel, blog, digital marketing, website, SEO, The Solution ID'))

@section('content')
<section class="relative pt-36 pb-16 lg:pt-52 lg:pb-24 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="glow top-0 -left-40 w-[500px] h-[500px] bg-primary-200/30"></div>
        <div class="glow bottom-0 -right-40 w-[400px] h-[400px] bg-blue-200/20"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav aria-label="Breadcrumb" class="mb-8">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm font-medium">
                <li><a href="{{ url('/') }}" class="text-dark-100/40 hover:text-primary-500 transition-colors">Home</a></li>
                <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-dark-100/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-dark-100/60">{{ __('Artikel & Blog') }}</span></li>
            </ol>
        </nav>
        <span class="section-label mb-8 inline-flex">{{ __('Artikel & Blog') }}</span>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-dark-300 leading-[1.05] tracking-tight mb-8 max-w-4xl">
            {{ __('Tips & Strategi') }} <br><span class="gradient-text">{{ __('Digital Marketing') }}</span>
        </h1>
        <p class="text-lg md:text-xl text-dark-100/60 max-w-2xl leading-relaxed font-medium">
            {{ __('Baca artikel dan panduan terbaru tentang pembuatan website, SEO, dan strategi digital untuk mengembangkan bisnis Anda.') }}
        </p>

        <form action="{{ route('articles.index') }}" method="GET" class="mt-10 max-w-xl">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="{{ __('Cari artikel...') }}"
                       class="w-full pl-12 pr-5 py-4 bg-white border border-dark-100/10 rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 shadow-lg shadow-dark-300/5">
                <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-100/30 hover:text-primary-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                @if(request('q'))
                <a href="{{ route('articles.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-dark-100/30 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
                @endif
            </div>
        </form>
    </div>
</section>

<section class="py-20 lg:py-28 bg-dark-50 section-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($categories->isNotEmpty())
        <div class="flex flex-wrap gap-3 mb-12">
            <a href="{{ route('articles.index', array_filter(['q' => request('q')])) }}"
               class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ !request('category') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/25' : 'bg-white text-dark-100/60 hover:text-dark-300 border border-dark-100/10 hover:border-primary-500/30' }}">
                {{ __('Semua') }}
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('articles.index', array_filter(['category' => $cat, 'q' => request('q')])) }}"
               class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request('category') === $cat ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/25' : 'bg-white text-dark-100/60 hover:text-dark-300 border border-dark-100/10 hover:border-primary-500/30' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 400)">
            <template x-if="!loaded">
                <div class="md:col-span-2 lg:col-span-3 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @for($i = 0; $i < 6; $i++)
                    <div class="bg-white rounded-3xl overflow-hidden border border-dark-100/5 shadow-xl">
                        <div class="aspect-[16/10] skeleton"></div>
                        <div class="p-7 space-y-3">
                            <div class="flex gap-3">
                                <div class="h-5 w-20 skeleton"></div>
                                <div class="h-5 w-16 skeleton"></div>
                            </div>
                            <div class="h-6 w-full skeleton"></div>
                            <div class="h-6 w-3/4 skeleton"></div>
                            <div class="h-4 w-full skeleton"></div>
                            <div class="h-4 w-2/3 skeleton"></div>
                            <div class="h-5 w-32 skeleton mt-4"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </template>
            <template x-if="loaded">
                <div class="md:col-span-2 lg:col-span-3 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($articles as $index => $article)
                    <a href="{{ route('articles.show', $article->slug) }}"
                       class="group bg-white rounded-3xl overflow-hidden border border-dark-100/5 shadow-xl shadow-dark-300/5 hover:border-primary-500/20 hover:shadow-2xl hover:shadow-primary-500/10 hover:-translate-y-1 transition-all duration-300"
                       data-animate="fade-up" style="transition-delay: {{ $index * 0.05 }}s">
                        <div class="aspect-[16/10] bg-gradient-to-br from-primary-50 to-primary-100 overflow-hidden relative">
                            @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->alt_text ?? $article->trans('title') }}"
                                 width="600" height="375" loading="lazy" decoding="async"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-primary-200/50 font-bold text-6xl">{{ substr($article->trans('title'), 0, 1) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="p-7">
                            <div class="flex items-center gap-3 mb-3">
                                @if($article->category)
                                <span class="text-xs font-bold text-primary-500 bg-primary-50 px-3 py-1 rounded-full">{{ $article->trans('category') }}</span>
                                @endif
                                @if($article->published_at)
                                <span class="text-xs text-dark-100/40 font-medium">{{ $article->published_at->format('d M Y') }}</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-lg text-dark-300 mb-2 group-hover:text-primary-600 transition-colors line-clamp-2">
                                {{ $article->trans('title') }}
                            </h3>
                            <p class="text-dark-100/60 text-sm font-medium line-clamp-2">
                                {{ $article->trans('excerpt') ?? Str::limit(strip_tags($article->trans('content')), 120) }}
                            </p>
                            <div class="mt-4 flex items-center gap-2 text-sm font-semibold text-primary-500 group-hover:gap-3 transition-all">
                                {{ __('Baca Selengkapnya') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full text-center py-20">
                        <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-primary-200/50">
                            <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <p class="text-dark-100/60 font-medium text-lg">{{ __('Belum ada artikel.') }}</p>
                    </div>
                    @endforelse
                </div>
            </template>
        </div>

        @if($articles->hasPages())
        <div class="mt-16 flex justify-center">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
