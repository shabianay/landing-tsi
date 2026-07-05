@extends('layouts.master')

@section('title', $project->trans('title') . ' — The Solution ID')
@section('meta_description', $project->trans('description') ? \Illuminate\Support\Str::limit(strip_tags($project->trans('description')), 160) : __('Lihat detail proyek') . ' ' . $project->trans('title') . ' ' . __('oleh The Solution ID'))
@section('og_title', $project->trans('title') . ' — The Solution ID')
@section('og_description', $project->trans('description') ? \Illuminate\Support\Str::limit(strip_tags($project->trans('description')), 160) : __('Lihat detail proyek') . ' ' . $project->trans('title') . ' ' . __('oleh The Solution ID'))
@section('og_image', $project->og_image ? asset('storage/' . $project->og_image) : ($project->image ? asset('storage/' . $project->image) : asset('storage/og-default.jpg')))

@section('content')
{{-- Hero with Image --}}
<section class="relative overflow-hidden bg-dark-300">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/60 via-dark-300 to-dark-300 z-0"></div>
    <div class="glow top-0 -left-40 w-[500px] h-[500px] bg-primary-500/20 z-0"></div>
    <div class="glow bottom-0 -right-40 w-[500px] h-[500px] bg-blue-500/10 z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 lg:pt-40 pb-10">
        <a href="{{ route('portfolio.index') }}" class="inline-flex items-center text-sm font-bold text-white/50 hover:text-white gap-2 transition-all mb-10 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Kembali ke Portfolio') }}
        </a>

        <nav aria-label="Breadcrumb" class="mb-10">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm font-medium">
                <li><a href="{{ url('/') }}" class="text-white/30 hover:text-white transition-colors">Home</a></li>
                <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><a href="{{ route('portfolio.index') }}" class="text-white/30 hover:text-white transition-colors">{{ __('Portfolio') }}</a></li>
                <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-white/50">{{ $project->trans('title') }}</span></li>
            </ol>
        </nav>

        <div class="max-w-4xl">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 text-white/80 rounded-full text-sm font-semibold border border-white/10 backdrop-blur-sm mb-6">{{ $project->client->name ?? __('Portfolio') }}</span>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.05] tracking-tight mb-6">{{ $project->trans('title') }}</h1>
            <div class="flex flex-wrap items-center gap-4">
                @if($project->website_url)
                <a href="{{ $project->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/20 backdrop-blur-sm transition-all group">
                    {{ __('Kunjungi Website') }}
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative">
            <div class="bg-white/10 backdrop-blur-sm rounded-t-2xl border border-white/10 border-b-0 px-4 py-3 flex items-center gap-2">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-400/70"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400/70"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400/70"></div>
                </div>
                <div class="flex-1 mx-4 px-4 py-1.5 bg-white/5 rounded-lg text-xs text-white/40 font-medium truncate">{{ $project->website_url ?? ($project->trans('title') . ' — The Solution ID') }}</div>
            </div>
            <div class="bg-dark-200 rounded-b-2xl border border-white/10 border-t-0 overflow-hidden shadow-2xl">
                @if($project->image)
                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->alt_text ?? $project->trans('title') }}" width="1200" height="675" loading="eager" fetchpriority="high" class="w-full h-auto">
                @else
                <div class="aspect-video bg-gradient-to-br from-primary-900/30 to-dark-200 flex items-center justify-center">
                    <span class="text-white/20 font-bold text-6xl md:text-8xl">{{ substr($project->trans('title'), 0, 2) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="relative z-10 h-16 bg-dark-50 section-gradient" style="border-radius: 3rem 3rem 0 0;"></div>
</section>

{{-- Description --}}
<section class="bg-dark-50 section-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <h2 class="text-2xl md:text-3xl font-bold text-dark-300 mb-6">{{ __('Tentang Project Ini') }}</h2>
                <div class="text-dark-100/60 leading-relaxed font-medium space-y-4">
                    @php
                        $desc = $project->trans('description') ?? '';
                        $paragraphs = explode("\n\n", $desc);
                    @endphp
                    @foreach($paragraphs as $para)
                        @if(trim($para))
                        <p>{{ trim($para) }}</p>
                        @endif
                    @endforeach
                    @if(!str_contains($desc, "\n\n"))
                    <p>{{ $desc }}</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="card">
                    <h3 class="font-bold text-dark-300 text-sm uppercase tracking-wider mb-6">{{ __('Info Project') }}</h3>
                    <div class="space-y-5">
                        <div>
                            <span class="text-xs text-dark-100/40 font-medium uppercase tracking-wider">{{ __('Klien') }}</span>
                            <p class="font-bold text-dark-300 mt-1">{{ $project->client->name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-dark-100/40 font-medium uppercase tracking-wider">{{ __('Status') }}</span>
                            <p class="mt-1">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    {{ ucfirst($project->status ?? 'Completed') }}
                                </span>
                            </p>
                        </div>
                        @if($project->website_url)
                        <div>
                            <span class="text-xs text-dark-100/40 font-medium uppercase tracking-wider">{{ __('Website') }}</span>
                            <a href="{{ $project->website_url }}" target="_blank" rel="noopener noreferrer" class="block text-primary-500 font-bold text-sm mt-1 hover:underline break-all">{{ $project->website_url }}</a>
                        </div>
                        @endif
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="card block text-center hover:bg-primary-500 hover:text-white group border-primary-200/50 bg-gradient-to-br from-primary-50 to-primary-100/50 !p-6">
                    <svg class="w-8 h-8 mx-auto mb-3 text-primary-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="font-bold text-sm group-hover:text-white transition-colors">{{ __('Tertarik dengan project serupa?') }}</span>
                    <span class="block text-xs mt-1 text-dark-100/50 group-hover:text-white/70 transition-colors">{{ __('Konsultasi gratis dengan tim kami') }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Related Projects --}}
@php $related = \App\Models\Project::where('id', '!=', $project->id)->inRandomOrder()->take(3)->get(); @endphp
@if($related->count() > 0)
<section class="py-20 lg:py-28 bg-white section-gradient border-t border-dark-100/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-dark-300">{{ __('Project Lainnya') }}</h2>
                <p class="text-dark-100/50 font-medium mt-2">{{ __('Lihat project lain yang telah kami kerjakan') }}</p>
            </div>
            <a href="{{ route('portfolio.index') }}" class="text-sm font-bold text-primary-500 hover:text-primary-600 transition-colors shrink-0 hidden sm:inline-flex items-center gap-1 group">
                {{ __('Lihat Semua') }}
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($related as $r)
            <a href="{{ route('portfolio.show', $r->id) }}" class="group bg-white rounded-2xl overflow-hidden border border-dark-100/5 shadow-lg shadow-dark-300/5 hover:border-primary-500/20 hover:shadow-xl hover:shadow-primary-500/10 hover:-translate-y-0.5 transition-all duration-300">
                <div class="aspect-[16/10] bg-gradient-to-br from-primary-50 to-primary-100 overflow-hidden">
                    @if($r->image)
                    <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->alt_text ?? $r->trans('title') }}" width="600" height="450" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-primary-300/50 font-bold text-4xl">{{ substr($r->trans('title'), 0, 2) }}</div>
                    @endif
                </div>
                <div class="p-5">
                    <span class="text-xs text-primary-500 font-bold uppercase tracking-wider">{{ $r->client->name ?? __('Portfolio') }}</span>
                    <h3 class="font-bold text-dark-300 mt-1.5 group-hover:text-primary-600 transition-colors">{{ $r->trans('title') }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Navigation --}}
@php
    $prev = \App\Models\Project::where('id', '<', $project->id)->orderBy('id', 'desc')->first();
    $next = \App\Models\Project::where('id', '>', $project->id)->orderBy('id')->first();
@endphp
@if($prev || $next)
<section class="py-20 lg:py-28 bg-dark-50 border-t border-dark-100/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-between gap-4">
            @if($prev)
            <a href="{{ route('portfolio.show', $prev->id) }}" class="group flex items-center gap-3 text-dark-100/50 hover:text-primary-600 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <div class="text-right">
                    <span class="text-xs font-medium">{{ __('Sebelumnya') }}</span>
                    <p class="font-bold text-dark-300 group-hover:text-primary-600 transition-colors text-sm">{{ $prev->trans('title') }}</p>
                </div>
            </a>
            @else
            <div></div>
            @endif
            @if($next)
            <a href="{{ route('portfolio.show', $next->id) }}" class="group flex items-center gap-3 text-dark-100/50 hover:text-primary-600 transition-colors text-right">
                <div>
                    <span class="text-xs font-medium">{{ __('Selanjutnya') }}</span>
                    <p class="font-bold text-dark-300 group-hover:text-primary-600 transition-colors text-sm">{{ $next->trans('title') }}</p>
                </div>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>
    </div>
</section>
@endif
@endsection
