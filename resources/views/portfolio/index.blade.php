@extends('layouts.master')

@section('title', __('Portfolio') . ' — The Solution ID')
@section('meta_description', __('Lihat portofolio proyek website yang telah The Solution ID rampungkan untuk klien dari berbagai industri di Indonesia. Hasil nyata kolaborasi kami dalam mencapai tujuan digital.'))
@section('og_title', __('Portfolio') . ' — The Solution ID')
@section('og_description', __('Lihat portofolio proyek website yang telah The Solution ID rampungkan untuk klien dari berbagai industri di Indonesia. Hasil nyata kolaborasi kami dalam mencapai tujuan digital.'))

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
                <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-dark-100/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-dark-100/60">{{ __('Portfolio') }}</span></li>
            </ol>
        </nav>
        <span class="section-label mb-8 inline-flex">{{ __('Portfolio') }}</span>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-dark-300 leading-[1.05] tracking-tight mb-8 max-w-4xl">{{ __('Proyek yang Telah') }} <br><span class="gradient-text">{{ __('Kami Rampungkan') }}</span></h1>
        <p class="text-lg md:text-xl text-dark-100/60 max-w-2xl leading-relaxed font-medium">{{ __('Kami bangga dapat bermitra dengan klien dari berbagai industri untuk menghadirkan solusi digital terbaik.') }}</p>
    </div>
</section>

<section class="py-20 lg:py-28 bg-dark-50 section-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projects as $index => $project)
            <a href="{{ route('portfolio.show', $project->id) }}" class="group relative bg-white rounded-3xl overflow-hidden border border-dark-100/5 shadow-xl shadow-dark-300/5 hover:border-primary-500/20 hover:shadow-2xl hover:shadow-primary-500/10 hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[4/3] bg-gradient-to-br from-primary-50 to-primary-100 overflow-hidden relative">
                    @if($project->image)
                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->alt_text ?? $project->trans('title') }}" width="600" height="450" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-primary-300/50 font-bold text-5xl">{{ substr($project->trans('title'), 0, 2) }}</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-300/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-7">
                    <span class="text-xs text-primary-500 font-bold uppercase tracking-wider">{{ $project->client->name ?? __('Portfolio') }}</span>
                    <h3 class="font-bold text-lg text-dark-300 mt-2 mb-2 group-hover:text-primary-600 transition-colors">{{ $project->trans('title') }}</h3>
                    <p class="text-dark-100/60 text-sm font-medium line-clamp-2">{{ $project->trans('description') ? \Illuminate\Support\Str::limit($project->trans('description'), 100) : '' }}</p>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20">
                <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-primary-200/50">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <p class="text-dark-100/60 font-medium text-lg">{{ __('Belum ada project yang ditambahkan.') }}</p>
            </div>
            @endforelse
        </div>

        @if($projects->hasPages())
        <div class="mt-16 flex justify-center">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
