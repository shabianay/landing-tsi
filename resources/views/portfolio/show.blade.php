@extends('layouts.master')

@section('title', $project->trans('title') . ' — The Solution ID')
@section('meta_description', $project->trans('description') ? \Illuminate\Support\Str::limit(strip_tags($project->trans('description')), 160) : __('Lihat detail proyek') . ' ' . $project->trans('title') . ' ' . __('oleh The Solution ID'))
@section('og_title', $project->trans('title') . ' — The Solution ID')
@section('og_description', $project->trans('description') ? \Illuminate\Support\Str::limit(strip_tags($project->trans('description')), 160) : __('Lihat detail proyek') . ' ' . $project->trans('title') . ' ' . __('oleh The Solution ID'))
@section('og_image', $project->og_image ? asset('storage/' . $project->og_image) : ($project->image ? asset('storage/' . $project->image) : asset('storage/og-default.jpg')))

@php
    $desc = trim($project->trans('description') ?? '');
    $paragraphs = array_values(array_filter(array_map('trim', preg_split("/\R{2,}/", $desc))));
    $related = \App\Models\Project::with('client')->where('id', '!=', $project->id)->inRandomOrder()->take(3)->get();
    $prev = \App\Models\Project::where('id', '<', $project->id)->orderBy('id', 'desc')->first();
    $next = \App\Models\Project::where('id', '>', $project->id)->orderBy('id')->first();
@endphp

@section('content')
    <x-page-header
        :title="$project->trans('title')"
        :crumbs="[['label' => __('Portfolio'), 'url' => route('portfolio.index')], ['label' => $project->trans('title')]]">
        <dl class="divide-y divide-line border-y border-line text-[0.95rem]">
            <div class="flex justify-between gap-4 py-3">
                <dt class="text-dark-100">{{ __('Klien') }}</dt>
                <dd class="text-right font-semibold text-dark-300">{{ $project->client->name ?? '—' }}</dd>
            </div>
            <div class="flex justify-between gap-4 py-3">
                <dt class="text-dark-100">{{ __('Status') }}</dt>
                <dd class="flex items-center gap-2 font-semibold text-dark-300">
                    <span class="h-2.5 w-2.5 bg-primary-500" aria-hidden="true"></span>
                    {{ ucfirst($project->status ?? 'Completed') }}
                </dd>
            </div>
            @if ($project->website_url)
                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-dark-100">{{ __('Website') }}</dt>
                    <dd class="min-w-0 text-right">
                        <a href="{{ $project->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 break-all font-semibold text-primary-700 underline decoration-primary-300 hover:text-dark-300">
                            {{ preg_replace('#^https?://(www\.)?#', '', rtrim($project->website_url, '/')) }}
                            <x-icon-arrow dir="out" class="h-3.5 w-3.5 shrink-0" />
                        </a>
                    </dd>
                </div>
            @endif
        </dl>
    </x-page-header>

    {{-- The work itself, framed as a plain browser window. --}}
    <section class="bg-dark-50 py-10 md:py-16">
        <div class="wrap">
            <figure class="border border-dark-300/15 bg-white shadow-xl">
                <div class="flex items-center gap-3 border-b border-line px-4 py-2.5">
                    <span class="flex gap-1.5" aria-hidden="true">
                        <span class="h-2.5 w-2.5 bg-dark-100/30"></span><span class="h-2.5 w-2.5 bg-dark-100/30"></span><span class="h-2.5 w-2.5 bg-dark-100/30"></span>
                    </span>
                    <span class="min-w-0 flex-1 truncate bg-dark-50 px-3 py-1 text-xs text-dark-100">{{ $project->website_url ?? $project->trans('title') }}</span>
                </div>
                @if ($project->image)
                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->alt_text ?? $project->trans('title') }}"
                         width="1200" height="675" fetchpriority="high" class="h-auto w-full">
                @else
                    <div class="flex aspect-video items-center justify-center font-display text-7xl font-extrabold text-dark-100/25">{{ mb_substr($project->trans('title'), 0, 2) }}</div>
                @endif
            </figure>
            @if ($project->website_url)
                <div class="mt-6">
                    <a href="{{ $project->website_url }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                        {{ __('Kunjungi Website') }} <x-icon-arrow dir="out" />
                    </a>
                </div>
            @endif
        </div>
    </section>

    @if ($paragraphs)
        <section class="py-16 md:py-24">
            <div class="wrap grid gap-8 lg:grid-cols-12">
                <h2 class="display-3 lg:col-span-4">{{ __('Tentang Project Ini') }}</h2>
                <div class="article-body lg:col-span-8">
                    @foreach ($paragraphs as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($prev || $next)
        <nav class="border-y border-line" aria-label="{{ __('Project Lainnya') }}">
            <div class="wrap grid grid-cols-2 divide-x divide-line">
                @if ($prev)
                    <a href="{{ route('portfolio.show', $prev->id) }}" class="group flex flex-col gap-1 py-6 pr-4">
                        <span class="inline-flex items-center gap-2 text-sm text-dark-100"><x-icon-arrow dir="left" /> {{ __('Sebelumnya') }}</span>
                        <span class="line-clamp-2 font-semibold text-dark-300 group-hover:underline">{{ $prev->trans('title') }}</span>
                    </a>
                @else
                    <span></span>
                @endif
                @if ($next)
                    <a href="{{ route('portfolio.show', $next->id) }}" class="group flex flex-col items-end gap-1 py-6 pl-4 text-right">
                        <span class="inline-flex items-center gap-2 text-sm text-dark-100">{{ __('Selanjutnya') }} <x-icon-arrow /></span>
                        <span class="line-clamp-2 font-semibold text-dark-300 group-hover:underline">{{ $next->trans('title') }}</span>
                    </a>
                @endif
            </div>
        </nav>
    @endif

    @if ($related->isNotEmpty())
        <section class="py-16 md:py-24">
            <div class="wrap">
                <div class="flex items-end justify-between gap-4">
                    <h2 class="display-3">{{ __('Project Lainnya') }}</h2>
                    <a href="{{ route('portfolio.index') }}" class="link-arrow shrink-0">{{ __('Lihat Semua') }} <x-icon-arrow /></a>
                </div>
                <ul class="mt-8 grid gap-x-6 gap-y-10 md:grid-cols-3">
                    @foreach ($related as $r)
                        <li>
                            <a href="{{ route('portfolio.show', $r->id) }}" class="group block">
                                <div class="relative aspect-[16/10] overflow-hidden border border-line bg-dark-50">
                                    @if ($r->image)
                                        <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->alt_text ?? $r->trans('title') }}" width="600" height="375" loading="lazy" decoding="async"
                                             class="h-full w-full object-cover object-top transition-transform duration-700 ease-out-expo group-hover:scale-[1.03]">
                                    @else
                                        <span class="absolute inset-0 flex items-center justify-center font-display text-4xl font-extrabold text-dark-100/30">{{ mb_substr($r->trans('title'), 0, 2) }}</span>
                                    @endif
                                </div>
                                <p class="mt-4 text-sm text-dark-100">{{ $r->client->name ?? __('Portfolio') }}</p>
                                <h3 class="mt-1 font-bold text-dark-300 group-hover:underline">{{ $r->trans('title') }}</h3>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @include('partials.cta-band', ['title' => __('Tertarik dengan project serupa?'), 'body' => __('Konsultasi gratis dengan tim kami')])
@endsection
