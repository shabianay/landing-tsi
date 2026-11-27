@extends('layouts.master')

@section('title', __('The Solution ID') . ' — ' . __('Website Profesional Untuk Bisnis Anda'))
@section('meta_description', __('The Solution ID adalah penyedia jasa pembuatan website profesional di Indonesia. Kami membangun website performa tinggi untuk meningkatkan kredibilitas merek dan mendorong konversi penjualan bisnis Anda.'))
@section('og_title', __('The Solution ID') . ' — ' . __('Website Profesional Untuk Bisnis Anda'))
@section('og_description', __('The Solution ID adalah penyedia jasa pembuatan website profesional di Indonesia. Kami membangun website performa tinggi untuk meningkatkan kredibilitas merek dan mendorong konversi penjualan bisnis Anda.'))

@php
    use App\Models\Setting;

    $featuredProjects = \App\Models\Project::with('client')->take(3)->get();
    $testimonials = \App\Models\Testimonial::take(6)->get();
    $faqList = \App\Models\Faq::where('is_active', true)->orderBy('sort_order')->get();
    $waNumber = Setting::get('contact_whatsapp', '6285967142870');
@endphp

@section('content')

    {{-- ============ First viewport: the logo mark built from the studio's proof ============ --}}
    <section class="overflow-hidden border-b border-line">
        <div class="wrap grid gap-12 pb-14 pt-10 md:pt-16 lg:grid-cols-12 lg:items-center lg:gap-10 lg:pb-20 lg:pt-16">
            <div class="lg:col-span-7 lg:pt-6">
                <h1 class="display-1">
                    {{ __('Ubah Visi Bisnis') }}
                    <span class="block text-primary-600">{{ __('Menjadi Realitas Digital') }}</span>
                </h1>
                <p class="lede mt-6 md:mt-8">
                    {{ __('Kami membangun website performa tinggi yang dirancang untuk meningkatkan kredibilitas merek dan mendorong konversi penjualan Anda.') }}
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row md:mt-10">
                    <a href="{{ route('contact') }}" class="btn-primary !min-h-14 !px-8 text-base">
                        {{ __('Mulai Konsultasi Gratis') }}
                        <x-icon-arrow class="h-5 w-5" />
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="btn-secondary !min-h-14 !px-8 text-base">
                        {{ __('Lihat Hasil Kerja Kami') }}
                    </a>
                </div>
            </div>

            {{-- The block stack: same silhouette as the logo, each block holds one fact. --}}
            <div class="lg:col-span-5">
                <div class="mx-auto grid max-w-md grid-cols-2 grid-rows-3 gap-1.5 sm:gap-2 lg:ml-auto lg:mr-0 lg:max-w-[22rem] xl:max-w-[24rem]">
                    <dl class="block-drop flex aspect-[4/3] flex-col justify-between bg-dark-100 p-4 text-white sm:aspect-square sm:p-5" style="--i: 0">
                        <dt class="text-sm font-medium text-white/85">{{ __('Proyek Selesai') }}</dt>
                        <dd class="num font-display text-4xl font-extrabold leading-none sm:text-5xl">{{ Setting::get('stat_projects', '50+') }}</dd>
                    </dl>
                    <div class="aspect-[4/3] sm:aspect-square" aria-hidden="true"></div>
                    <dl class="block-drop flex aspect-[4/3] flex-col justify-between bg-dark-100 p-4 text-white sm:aspect-square sm:p-5" style="--i: 1">
                        <dt class="text-sm font-medium text-white/85">{{ __('Klien Aktif') }}</dt>
                        <dd class="num font-display text-4xl font-extrabold leading-none sm:text-5xl">{{ Setting::get('stat_clients', '30+') }}</dd>
                    </dl>
                    <dl class="block-drop flex aspect-[4/3] flex-col justify-between bg-dark-100 p-4 text-white sm:aspect-square sm:p-5" style="--i: 2">
                        <dt class="text-sm font-medium text-white/85">{{ __('Rating Kepuasan') }}</dt>
                        <dd class="num font-display text-4xl font-extrabold leading-none sm:text-5xl">{{ Setting::get('stat_rating', '4.9/5') }}</dd>
                    </dl>
                    <dl class="block-drop flex aspect-[4/3] flex-col justify-between bg-primary-500 p-4 text-dark-300 sm:aspect-square sm:p-5" style="--i: 3">
                        <dt class="text-sm font-semibold">{{ __('Waktu pengerjaan') }}</dt>
                        <dd class="font-display text-3xl font-extrabold leading-none sm:text-4xl">{{ __(Setting::get('stat_timeline', '1-3 Minggu')) }}</dd>
                    </dl>
                    <div class="flex aspect-[4/3] items-end sm:aspect-square" aria-hidden="true"></div>
                </div>
            </div>
        </div>

        @if ($partners->isNotEmpty())
            <div class="border-t border-line bg-dark-50/60">
                <div class="wrap py-8 md:py-10">
                    <h2 class="text-sm font-semibold text-dark-100">{{ __('Dipercaya oleh') }}</h2>
                    <ul class="mt-5 flex snap-x overflow-x-auto border-l border-t border-line md:grid md:grid-cols-4 md:overflow-visible lg:grid-cols-6">
                        @foreach ($partners as $logo)
                            <li class="flex h-24 w-40 shrink-0 snap-start items-center justify-center border-b border-r border-line bg-white px-5 md:w-auto">
                                @if ($logo->logo)
                                    <img src="{{ asset('storage/' . $logo->logo) }}" alt="{{ $logo->name }}" width="400" height="280"
                                         class="max-h-14 w-auto max-w-full object-contain opacity-80 grayscale transition duration-300 hover:opacity-100 hover:grayscale-0"
                                         loading="lazy" decoding="async">
                                @else
                                    <span class="text-center text-sm font-semibold text-dark-100">{{ $logo->name }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </section>

    {{-- ============ Why: three guarantees as a spec sheet ============ --}}
    <section id="keunggulan" class="scroll-mt-20 py-16 md:py-24">
        <div class="wrap grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-28">
                    <x-blockmark size="1rem" class="mb-6" />
                    <h2 class="display-2">{{ __('Kenapa Memilih The Solution ID?') }}</h2>
                    <p class="mt-5 text-dark-100">
                        {{ __('Kami tidak hanya membuat website — kami membangun solusi digital yang mendorong pertumbuhan bisnis Anda dengan jaminan kualitas dan layanan terbaik.') }}
                    </p>
                </div>
            </div>

            <div class="lg:col-span-8">
                <ul class="divide-y divide-line border-y border-line">
                    @foreach ([
                        [__('100% Garansi Kepuasan'), __('Kami menjamin Anda puas dengan hasil kerja kami. Revisi tanpa batas sampai desain sesuai keinginan Anda. Tidak puas? Uang kembali.'), __('Garansi Pasti')],
                        [__('Pengerjaan Cepat 1-3 Minggu'), __('Website profesional selesai dalam 1-3 minggu. Proses transparan dengan laporan mingguan dan update real-time progres pengerjaan Anda.'), __('Hanya 1-3 Minggu')],
                        [__('Dukungan & Garansi Penuh'), __('Gratis perbaikan bug 30 hari pasca-launch, gratis SSL, gratis 2 email bisnis, dan konsultasi gratis kapan saja Anda butuhkan.'), __('Support Penuh')],
                    ] as [$title, $body, $tag])
                        <li class="grid gap-3 py-7 sm:grid-cols-[1fr_auto] sm:gap-8 md:py-9">
                            <div>
                                <h3 class="display-3">{{ $title }}</h3>
                                <p class="mt-3 max-w-[60ch] text-dark-100">{{ $body }}</p>
                            </div>
                            <span class="tag-blue self-start justify-self-start">{{ $tag }}</span>
                        </li>
                    @endforeach
                </ul>

                <dl class="cells mt-10 grid-cols-2 md:grid-cols-4">
                    @foreach ([
                        ['100%', __('Kepuasan Klien')],
                        ['30 ' . __('Hari'), __('Garansi Gratis Perbaikan')],
                        ['2', __('Email Bisnis Gratis')],
                        ['24/7', __('Dukungan Penuh')],
                    ] as [$value, $label])
                        <div class="flex flex-col-reverse justify-end gap-2 p-5 md:p-6">
                            <dt class="text-sm text-dark-100">{{ $label }}</dt>
                            <dd class="num font-display text-3xl font-extrabold text-dark-300 md:text-4xl">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </section>

    {{-- ============ Services ============ --}}
    <section id="layanan" class="scroll-mt-20 border-t border-line bg-dark-50 py-16 md:py-24">
        <div class="wrap">
            <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                <h2 class="display-2 lg:col-span-6">{{ __('Solusi Lengkap untuk Kebutuhan Digital Anda') }}</h2>
                <p class="text-dark-100 lg:col-span-5 lg:col-start-8">
                    {{ __('Kami menyediakan layanan pembuatan website profesional dengan fitur-fitur unggulan untuk membantu bisnis Anda tumbuh.') }}
                </p>
            </div>

            @if ($services->isNotEmpty())
                <ul class="cells mt-10 sm:grid-cols-2 {{ $services->count() % 3 === 0 ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }} md:mt-14">
                    @foreach ($services as $s)
                        <li class="group flex flex-col p-6 transition-colors hover:bg-primary-50/50 md:p-8">
                            <span class="flex h-11 w-11 items-center justify-center bg-dark-300 text-white transition-colors group-hover:bg-primary-500 group-hover:text-dark-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="{{ $s->icon ?: 'M13 10V3L4 14h7v7l9-11h-7z' }}" />
                                </svg>
                            </span>
                            <h3 class="mt-6 text-lg font-bold text-dark-300">{{ $s->trans('title') }}</h3>
                            <p class="mt-2 text-[0.95rem] text-dark-100">{{ $s->trans('description') }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-10 border border-dashed border-dark-100/40 p-8 text-dark-100">{{ __('Belum ada layanan.') }}</p>
            @endif
        </div>
    </section>

    {{-- ============ Portfolio ============ --}}
    @if ($featuredProjects->isNotEmpty())
        <section id="portfolio" class="scroll-mt-20 py-16 md:py-24">
            <div class="wrap">
                <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                    <div class="max-w-2xl">
                        <h2 class="display-2">{{ __('Karya Terbaru Kami') }}</h2>
                        <p class="mt-4 text-dark-100">{{ __('Hasil nyata kolaborasi kami dengan para mitra bisnis dalam mencapai tujuan digital mereka.') }}</p>
                    </div>
                    <a href="{{ route('portfolio.index') }}" class="link-arrow shrink-0">
                        {{ __('Lihat Semua Project') }} <x-icon-arrow />
                    </a>
                </div>

                <div class="mt-10 grid gap-6 md:mt-14 lg:grid-cols-3 lg:grid-rows-2">
                    @foreach ($featuredProjects as $project)
                        @php $lead = $loop->first && $featuredProjects->count() === 3; @endphp
                        <a href="{{ route('portfolio.show', $project->id) }}"
                           class="group flex flex-col {{ $lead ? 'lg:col-span-2 lg:row-span-2' : '' }}">
                            <div class="relative overflow-hidden border border-line bg-dark-50 {{ $lead ? 'aspect-[4/3] lg:aspect-auto lg:flex-1' : 'aspect-[16/10]' }}">
                                @if ($project->image)
                                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->alt_text ?? $project->trans('title') }}"
                                         width="1200" height="900" loading="lazy" decoding="async"
                                         class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-700 ease-out-expo group-hover:scale-[1.03]">
                                @else
                                    <span class="absolute inset-0 flex items-center justify-center font-display text-6xl font-extrabold text-dark-100/30">{{ mb_substr($project->trans('title'), 0, 2) }}</span>
                                @endif
                            </div>
                            <div class="flex items-start justify-between gap-4 pt-4">
                                <div>
                                    <p class="text-sm text-dark-100">{{ $project->client->name ?? __('Portfolio') }}</p>
                                    <h3 class="mt-1 font-bold text-dark-300 group-hover:underline {{ $lead ? 'text-xl md:text-2xl' : 'text-lg' }}">{{ $project->trans('title') }}</h3>
                                </div>
                                <x-icon-arrow dir="out" class="mt-1 h-5 w-5 shrink-0 text-dark-100 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-dark-300" />
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ Testimonials ============ --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-dark-300 py-16 text-white md:py-24">
            <div class="wrap">
                <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                    <h2 class="display-2 !text-white lg:col-span-6">{{ __('Apa Kata Klien Kami') }}</h2>
                    <p class="text-white/75 lg:col-span-5 lg:col-start-8">{{ __('Kepuasan klien adalah prioritas utama kami.') }}</p>
                </div>

                <ul class="mt-10 grid gap-px border border-white/10 bg-white/10 md:mt-14 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $t)
                        @php $rating = max(0, min(5, (int) ($t->rating ?? 5))); @endphp
                        <li class="flex flex-col bg-dark-300 p-6 md:p-8">
                            <figure class="flex h-full flex-col">
                                <div class="flex gap-1" role="img" aria-label="{{ $rating }}/5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="h-2.5 w-2.5 {{ $i <= $rating ? 'bg-primary-500' : 'bg-white/15' }}"></span>
                                    @endfor
                                </div>
                                <blockquote class="mt-6 flex-1 text-[1.05rem] leading-relaxed text-white/90">
                                    <p>{{ $t->trans('content') }}</p>
                                </blockquote>
                                <figcaption class="mt-8 flex items-center gap-3 border-t border-white/10 pt-5">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center bg-dark-100 font-display text-lg font-bold">{{ mb_substr($t->trans('client_name'), 0, 1) }}</span>
                                    <span>
                                        <span class="block font-semibold">{{ $t->trans('client_name') }}</span>
                                        @if ($t->trans('company_name'))
                                            <span class="block text-sm text-white/70">{{ $t->trans('company_name') }}</span>
                                        @endif
                                    </span>
                                </figcaption>
                            </figure>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ============ Process: each step adds a block ============ --}}
    <section class="border-b border-line py-16 md:py-24">
        <div class="wrap">
            <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                <h2 class="display-2 lg:col-span-6">{{ __('Bagaimana Kami Bekerja') }}</h2>
                <p class="text-dark-100 lg:col-span-5 lg:col-start-8">
                    {{ __('Proses pengembangan website yang terstruktur untuk memastikan hasil terbaik bagi bisnis Anda.') }}
                </p>
            </div>

            @if ($processSteps->isNotEmpty())
                @php $totalSteps = $processSteps->count(); @endphp
                <ol class="cells mt-10 sm:grid-cols-2 md:mt-14 {{ [1 => 'lg:grid-cols-2', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3'][$totalSteps] ?? 'lg:grid-cols-4' }}">
                    @foreach ($processSteps as $step)
                        <li class="flex flex-col p-6 md:p-8">
                            <div class="flex items-end gap-1" aria-hidden="true">
                                @for ($b = 1; $b <= $totalSteps; $b++)
                                    <span class="h-3 w-3 {{ $b < $loop->iteration ? 'bg-dark-100' : ($b === $loop->iteration ? 'bg-primary-500' : 'border border-dark-100/30') }}"></span>
                                @endfor
                            </div>
                            <p class="num mt-6 text-sm font-semibold text-primary-700">{{ __('Langkah') }} {{ $step->step_number }}</p>
                            <h3 class="mt-1 text-lg font-bold text-dark-300">{{ $step->trans('title') }}</h3>
                            @if ($step->trans('description'))
                                <p class="mt-2 text-[0.95rem] text-dark-100">{{ $step->trans('description') }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="mt-10 border border-dashed border-dark-100/40 p-8 text-dark-100">{{ __('Belum ada proses.') }}</p>
            @endif
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    @if ($faqList->isNotEmpty())
        <section id="faq" class="scroll-mt-20 py-16 md:py-24">
            <div class="wrap grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <div class="lg:sticky lg:top-28">
                        <h2 class="display-2">{{ __('Pertanyaan Umum') }}</h2>
                        <p class="mt-5 text-dark-100">{{ __('Semua yang perlu Anda ketahui tentang memulai proyek bersama kami.') }}</p>
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer" class="link-arrow mt-6">
                            {{ __('Chat via WhatsApp') }} <x-icon-arrow />
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-line border-y border-line lg:col-span-8">
                    @foreach ($faqList as $faq)
                        <details class="group" @if($loop->first) open @endif>
                            <summary class="flex cursor-pointer items-start justify-between gap-6 py-6 text-left">
                                <span class="text-lg font-semibold text-dark-300">{{ $faq->trans('question') }}</span>
                                <span class="relative mt-1.5 h-4 w-4 shrink-0" aria-hidden="true">
                                    <span class="absolute inset-x-0 top-1/2 h-0.5 -translate-y-1/2 bg-dark-300"></span>
                                    <span class="absolute inset-y-0 left-1/2 w-0.5 -translate-x-1/2 bg-dark-300 transition-transform duration-300 group-open:scale-y-0"></span>
                                </span>
                            </summary>
                            <div class="max-w-[65ch] pb-7 pr-10 text-dark-100">
                                {{ $faq->trans('answer') }}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.cta-band')

    <x-marketing-popup :popups="$popups ?? collect()" />
@endsection
