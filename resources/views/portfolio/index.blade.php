@extends('layouts.master')

@section('title', __('Portfolio') . ' — The Solution ID')
@section('meta_description', __('Lihat portofolio proyek website yang telah The Solution ID rampungkan untuk klien dari berbagai industri di Indonesia. Hasil nyata kolaborasi kami dalam mencapai tujuan digital.'))
@section('og_title', __('Portfolio') . ' — The Solution ID')
@section('og_description', __('Lihat portofolio proyek website yang telah The Solution ID rampungkan untuk klien dari berbagai industri di Indonesia. Hasil nyata kolaborasi kami dalam mencapai tujuan digital.'))

@section('content')
    <x-page-header
        :title="__('Proyek yang Telah Kami Rampungkan')"
        :lede="__('Kami bangga dapat bermitra dengan klien dari berbagai industri untuk menghadirkan solusi digital terbaik.')"
        :crumbs="[['label' => __('Portfolio')]]" />

    <section class="py-12 md:py-20">
        <div class="wrap">
            @if ($projects->isNotEmpty())
                <ul class="grid gap-x-6 gap-y-12 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <li>
                            <a href="{{ route('portfolio.show', $project->id) }}" class="group block">
                                <div class="relative aspect-[4/3] overflow-hidden border border-line bg-dark-50">
                                    @if ($project->image)
                                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->alt_text ?? $project->trans('title') }}"
                                             width="600" height="450" loading="lazy" decoding="async"
                                             class="h-full w-full object-cover object-top transition-transform duration-700 ease-out-expo group-hover:scale-[1.03]">
                                    @else
                                        <span class="absolute inset-0 flex items-center justify-center font-display text-5xl font-extrabold text-dark-100/30">{{ mb_substr($project->trans('title'), 0, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-start justify-between gap-4 pt-4">
                                    <div class="min-w-0">
                                        <p class="text-sm text-dark-100">{{ $project->client->name ?? __('Portfolio') }}</p>
                                        <h2 class="mt-1 text-lg font-bold text-dark-300 group-hover:underline">{{ $project->trans('title') }}</h2>
                                        @if ($project->trans('description'))
                                            <p class="mt-2 line-clamp-2 text-[0.95rem] text-dark-100">{{ \Illuminate\Support\Str::limit($project->trans('description'), 120) }}</p>
                                        @endif
                                    </div>
                                    <x-icon-arrow dir="out" class="mt-1 h-5 w-5 shrink-0 text-dark-100 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-dark-300" />
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if ($projects->hasPages())
                    <div class="mt-16 border-t border-line pt-8">
                        {{ $projects->links() }}
                    </div>
                @endif
            @else
                <div class="border border-dashed border-dark-100/40 px-6 py-16 text-center">
                    <x-blockmark size="1.25rem" class="mx-auto" />
                    <p class="mt-6 text-lg text-dark-100">{{ __('Belum ada project yang ditambahkan.') }}</p>
                </div>
            @endif
        </div>
    </section>

    @include('partials.cta-band')
@endsection
