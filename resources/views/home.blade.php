@extends('layouts.master')

@section('title', __('The Solution ID') . ' — ' . __('Website Profesional Untuk Bisnis Anda'))
@section('meta_description', __('The Solution ID adalah penyedia jasa pembuatan website profesional di Indonesia. Kami membangun website performa tinggi untuk meningkatkan kredibilitas merek dan mendorong konversi penjualan bisnis Anda.'))
@section('og_title', __('The Solution ID') . ' — ' . __('Website Profesional Untuk Bisnis Anda'))
@section('og_description', __('The Solution ID adalah penyedia jasa pembuatan website profesional di Indonesia. Kami membangun website performa tinggi untuk meningkatkan kredibilitas merek dan mendorong konversi penjualan bisnis Anda.'))

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-36 pb-16 lg:pt-52 lg:pb-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="glow top-0 -left-40 w-[500px] h-[500px] bg-primary-200/40"></div>
            <div class="glow bottom-0 -right-40 w-[500px] h-[500px] bg-blue-200/30"></div>
            <div
                class="absolute top-1/3 right-0 w-px h-64 bg-gradient-to-b from-transparent via-primary-300/30 to-transparent">
            </div>
            <div
                class="absolute bottom-1/3 left-0 w-px h-64 bg-gradient-to-b from-transparent via-primary-300/30 to-transparent">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2.5 px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full text-sm font-semibold mb-8 border border-primary-200/50 shadow-lg shadow-primary-500/5">
                    <span class="flex h-2 w-2 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    {{ __('Tersedia Slot Konsultasi Gratis Minggu Ini') }}
                </div>
                <h1
                    class="text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-bold text-dark-300 leading-[1.05] tracking-tight mb-8">
                    {{ __('Ubah Visi Bisnis') }}<br>
                    <span class="gradient-text relative">{{ __('Menjadi Realitas Digital') }}
                        <svg class="absolute -bottom-2 left-0 w-full h-3 text-primary-500/20" viewBox="0 0 200 12"
                            fill="currentColor">
                            <path d="M0 8c40-10 160-10 200 0" />
                        </svg>
                    </span>
                </h1>
                <p class="text-lg md:text-xl text-dark-100/60 max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                    {{ __('Kami membangun website performa tinggi yang dirancang untuk meningkatkan kredibilitas merek dan mendorong konversi penjualan Anda.') }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-5">
                    <a href="{{ route('contact') }}" class="btn-primary !px-10 !py-5 text-base group">
                        {{ __('Mulai Konsultasi Gratis') }}
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="btn-secondary !px-10 !py-5 text-base group">
                        {{ __('Lihat Hasil Kerja Kami') }}
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-20 overflow-hidden">
                <h2 class="text-center text-xs md:text-sm font-semibold text-dark-100/40 uppercase tracking-widest mb-8">
                    {{ __('Dipercaya oleh') }}</h2>
                <div class="relative">
                    <div class="flex gap-8 md:gap-12 marquee-scroll">
                        @php $logos = $partners->merge($partners); @endphp
                        @foreach ($logos as $logo)
                            <div class="shrink-0 flex items-center justify-center px-4">
                                @if($logo->logo)
                                    <img src="{{ asset('storage/' . $logo->logo) }}" alt="{{ $logo->name }}"
                                        width="400" height="280" decoding="async"
                                        class="h-14 md:h-20 max-w-44 md:max-w-56 w-auto object-contain grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300"
                                        loading="lazy">
                                @else
                                    <span class="text-lg font-bold text-dark-300/30">{{ $logo->name }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="mt-16 md:mt-20 grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-dark-100/5 shadow-lg shadow-dark-300/5 p-5 md:p-7 text-center hover:shadow-xl hover:shadow-primary-500/10 hover:border-primary-500/20 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl flex items-center justify-center mx-auto mb-3 md:mb-4 shadow-sm group-hover:shadow-md group-hover:shadow-primary-500/20 transition-all duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold gradient-text mb-1">{{ \App\Models\Setting::get('stat_projects', '50+') }}</div>
                    <div class="text-xs md:text-sm text-dark-100/50 font-medium">{{ __('Proyek Selesai') }}</div>
                </div>
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-dark-100/5 shadow-lg shadow-dark-300/5 p-5 md:p-7 text-center hover:shadow-xl hover:shadow-emerald-500/10 hover:border-emerald-500/20 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3 md:mb-4 shadow-sm group-hover:shadow-md group-hover:shadow-emerald-500/20 transition-all duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold gradient-text mb-1">{{ \App\Models\Setting::get('stat_rating', '4.9/5') }}</div>
                    <div class="text-xs md:text-sm text-dark-100/50 font-medium">{{ __('Rating Kepuasan') }}</div>
                </div>
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-dark-100/5 shadow-lg shadow-dark-300/5 p-5 md:p-7 text-center hover:shadow-xl hover:shadow-violet-500/10 hover:border-violet-500/20 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl flex items-center justify-center mx-auto mb-3 md:mb-4 shadow-sm group-hover:shadow-md group-hover:shadow-violet-500/20 transition-all duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold gradient-text mb-1">{{ \App\Models\Setting::get('stat_clients', '30+') }}</div>
                    <div class="text-xs md:text-sm text-dark-100/50 font-medium">{{ __('Klien Aktif') }}</div>
                </div>
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-dark-100/5 shadow-lg shadow-dark-300/5 p-5 md:p-7 text-center hover:shadow-xl hover:shadow-amber-500/10 hover:border-amber-500/20 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3 md:mb-4 shadow-sm group-hover:shadow-md group-hover:shadow-amber-500/20 transition-all duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold gradient-text mb-1">{{ __(\App\Models\Setting::get('stat_timeline', '1-3 Minggu')) }}</div>
                    <div class="text-xs md:text-sm text-dark-100/50 font-medium">{{ __('Minggu Pengerjaan') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose The Solution ID --}}
    <section class="py-20 lg:py-28 bg-white section-gradient" id="keunggulan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="section-label mb-6">{{ __('Keunggulan Kami') }}</span>
                <h2 class="section-title mb-6">{{ __('Kenapa Memilih The Solution ID?') }}</h2>
                <p class="section-subtitle max-w-2xl mx-auto">
                    {{ __('Kami tidak hanya membuat website — kami membangun solusi digital yang mendorong pertumbuhan bisnis Anda dengan jaminan kualitas dan layanan terbaik.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="card group text-center hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-green-200/50 group-hover:from-green-500 group-hover:to-green-600 group-hover:border-green-400 transition-all duration-300">
                        <svg class="w-8 h-8 text-green-600 group-hover:text-white transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark-300 mb-3">{{ __('100% Garansi Kepuasan') }}</h3>
                    <p class="text-dark-100/60 text-sm leading-relaxed font-medium">
                        {{ __('Kami menjamin Anda puas dengan hasil kerja kami. Revisi tanpa batas sampai desain sesuai keinginan Anda. Tidak puas? Uang kembali.') }}
                    </p>
                    <div
                        class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ __('Garansi Pasti') }}
                    </div>
                </div>

                <div class="card group text-center hover:-translate-y-2 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 bg-gradient-to-bl from-primary-500/10 to-transparent w-32 h-32 rounded-bl-full">
                    </div>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-primary-200/50 group-hover:from-primary-500 group-hover:to-primary-600 group-hover:border-primary-400 transition-all duration-300">
                        <svg class="w-8 h-8 text-primary-600 group-hover:text-white transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark-300 mb-3">{{ __('Pengerjaan Cepat 1-3 Minggu') }}</h3>
                    <p class="text-dark-100/60 text-sm leading-relaxed font-medium">
                        {{ __('Website profesional selesai dalam 1-3 minggu. Proses transparan dengan laporan mingguan dan update real-time progres pengerjaan Anda.') }}
                    </p>
                    <div
                        class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 bg-primary-50 px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ __('Hanya 1-3 Minggu') }}
                    </div>
                </div>

                <div class="card group text-center hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-purple-200/50 group-hover:from-purple-500 group-hover:to-purple-600 group-hover:border-purple-400 transition-all duration-300">
                        <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark-300 mb-3">{{ __('Dukungan & Garansi Penuh') }}</h3>
                    <p class="text-dark-100/60 text-sm leading-relaxed font-medium">
                        {{ __('Gratis perbaikan bug 30 hari pasca-launch, gratis SSL, gratis 2 email bisnis, dan konsultasi gratis kapan saja Anda butuhkan.') }}
                    </p>
                    <div
                        class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ __('Support Penuh') }}
                    </div>
                </div>
            </div>

            <div class="mt-16 grid md:grid-cols-4 gap-6">
                <div
                    class="bg-gradient-to-br from-primary-50 to-primary-100/50 rounded-2xl p-6 text-center border border-primary-200/50">
                    <div class="text-3xl font-bold gradient-text mb-1">100%</div>
                    <div class="text-xs text-dark-100/50 font-medium">{{ __('Kepuasan Klien') }}</div>
                </div>
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100/50 rounded-2xl p-6 text-center border border-green-200/50">
                    <div class="text-3xl font-bold text-green-600 mb-1">30 {{ __('Hari') }}</div>
                    <div class="text-xs text-dark-100/50 font-medium">{{ __('Garansi Gratis Perbaikan') }}</div>
                </div>
                <div
                    class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl p-6 text-center border border-purple-200/50">
                    <div class="text-3xl font-bold text-purple-600 mb-1">2</div>
                    <div class="text-xs text-dark-100/50 font-medium">{{ __('Email Bisnis Gratis') }}</div>
                </div>
                <div
                    class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl p-6 text-center border border-amber-200/50">
                    <div class="text-3xl font-bold text-amber-600 mb-1">24/7</div>
                    <div class="text-xs text-dark-100/50 font-medium">{{ __('Dukungan Penuh') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section class="py-20 lg:py-28 bg-dark-50 section-gradient" id="layanan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="section-label mb-6">{{ __('Layanan Unggulan') }}</span>
                <h2 class="section-title mb-6">{{ __('Solusi Lengkap untuk Kebutuhan Digital Anda') }}</h2>
                <p class="section-subtitle max-w-2xl mx-auto">
                    {{ __('Kami menyediakan layanan pembuatan website profesional dengan fitur-fitur unggulan untuk membantu bisnis Anda tumbuh.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($services as $s)
                    <div class="card group cursor-default">
                        <div class="card-icon">
                            @if($s->icon)
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $s->icon }}" />
                            </svg>
                            @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-dark-300 mb-3">{{ $s->trans('title') }}</h3>
                        <p class="text-dark-100/60 text-sm leading-relaxed font-medium">{{ $s->trans('description') }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-dark-100/50">
                        <p>{{ __('Belum ada layanan.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Portfolio Preview --}}
    @php $featuredProjects = \App\Models\Project::take(3)->get(); @endphp
    @if ($featuredProjects->count() > 0)
        <section class="py-20 lg:py-28 bg-white section-gradient" id="portfolio">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-16 gap-8">
                    <div class="max-w-2xl">
                        <span class="section-label mb-6">{{ __('Portfolio') }}</span>
                        <h2 class="section-title mb-4">{{ __('Karya Terbaru Kami') }}</h2>
                        <p class="section-subtitle">
                            {{ __('Hasil nyata kolaborasi kami dengan para mitra bisnis dalam mencapai tujuan digital mereka.') }}
                        </p>
                    </div>
                    <a href="{{ route('portfolio.index') }}"
                        class="font-bold text-primary-500 hover:text-primary-600 transition-colors flex items-center gap-2 group shrink-0">
                        {{ __('Lihat Semua Project') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($featuredProjects as $index => $project)
                        <a href="{{ route('portfolio.show', $project->id) }}"
                            class="group bg-white rounded-3xl overflow-hidden border border-dark-100/5 shadow-xl shadow-dark-300/5 hover:border-primary-500/20 hover:shadow-2xl hover:shadow-primary-500/10 hover:-translate-y-1 transition-all duration-300">
                            <div
                                class="aspect-[4/3] bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center relative overflow-hidden">
                                <span
                                    class="text-primary-200/50 font-bold text-8xl select-none">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                @if($project->image)
                                <img src="{{ asset('storage/' . $project->image) }}"
                                    alt="{{ $project->trans('title') }}"
                                    loading="lazy" decoding="async"
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-dark-300/60 via-dark-300/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </div>
                            <div class="p-7">
                                <span
                                    class="text-xs text-primary-500 font-bold uppercase tracking-wider">{{ $project->client->name ?? __('Portfolio') }}</span>
                                <h3
                                    class="font-bold text-lg text-dark-300 mt-2 mb-2 group-hover:text-primary-600 transition-colors">
                                    {{ $project->trans('title') }}</h3>
                                <p class="text-dark-100/60 text-sm font-medium line-clamp-2">{{ $project->trans('description') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials Section --}}
    @php $testimonials = \App\Models\Testimonial::take(6)->get(); @endphp
    @if ($testimonials->count() > 0)
        <section class="py-20 lg:py-28 bg-dark-300 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-dark-300 to-dark-300"></div>
            <div class="glow top-0 right-0 w-[600px] h-[600px] bg-primary-500/15"></div>
            <div class="glow bottom-0 left-0 w-[400px] h-[400px] bg-blue-500/10"></div>
            <div class="absolute inset-0 opacity-[0.02]"
                style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;">
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 text-white/80 rounded-full text-sm font-semibold border border-white/10 backdrop-blur-sm mb-6">{{ __('Testimonial') }}</span>
                    <h2 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">{{ __('Apa Kata Klien Kami') }}</h2>
                    <p class="text-lg text-white/50 max-w-2xl mx-auto font-medium">
                        {{ __('Kepuasan klien adalah prioritas utama kami.') }}</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($testimonials as $t)
                        <div
                            class="bg-white/[0.07] backdrop-blur-sm p-8 rounded-2xl border border-white/[0.08] hover:bg-white/[0.10] hover:border-white/20 transition-all duration-300">
                            <div class="flex gap-1 mb-6">
                                @for ($i = 0; $i < ($t->rating ?? 5); $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <div class="flex items-center gap-2 mb-5">
                                <div
                                    class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center font-bold text-sm shadow-lg">
                                    {{ substr($t->trans('client_name'), 0, 1) }}</div>
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $t->trans('client_name') }}</div>
                                    @if ($t->trans('company_name'))
                                        <div class="text-xs text-white/40 font-medium">{{ $t->trans('company_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <p class="text-white/70 leading-relaxed font-medium text-sm">"{{ $t->trans('content') }}"</p>
                            @if ($t->rating >= 5)
                                <div
                                    class="mt-5 flex items-center gap-1.5 text-xs text-yellow-300/80 font-medium bg-white/5 rounded-full px-3 py-1.5 w-fit">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Klien Sangat Puas') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- How It Works --}}
    <section class="py-20 lg:py-28 bg-dark-50 section-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="section-label mb-6">{{ __('Proses') }}</span>
                <h2 class="section-title mb-6">{{ __('Bagaimana Kami Bekerja') }}</h2>
                <p class="section-subtitle max-w-3xl mx-auto">
                    {{ __('Proses pengembangan website yang terstruktur untuk memastikan hasil terbaik bagi bisnis Anda.') }}
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($processSteps as $step)
                    <div
                        class="group relative bg-white rounded-2xl p-6 border border-dark-100/5 shadow-lg shadow-dark-300/5 hover:shadow-xl hover:shadow-primary-500/10 hover:border-primary-500/20 hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="relative shrink-0">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:shadow-primary-500/20 transition-all duration-300">
                                    @if($step->icon)
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step->icon }}" />
                                    </svg>
                                    @else
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    @endif
                                </div>
                                <span
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-br from-primary-500 to-primary-700 text-white rounded-lg flex items-center justify-center text-[11px] font-bold shadow-lg shadow-primary-500/30">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-dark-300 text-sm mb-2">{{ $step->trans('title') }}</h3>
                                @if($step->trans('description'))
                                <p class="text-xs text-dark-100/50 font-medium leading-relaxed text-justify">{{ $step->trans('description') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-dark-100/50">
                        <p>{{ __('Belum ada proses.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="py-20 lg:py-28 bg-white section-gradient" id="faq">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="section-label mb-6">{{ __('FAQ') }}</span>
                <h2 class="section-title mb-4">{{ __('Pertanyaan Umum') }}</h2>
                <p class="section-subtitle">{{ __('Semua yang perlu Anda ketahui tentang memulai proyek bersama kami.') }}
                </p>
            </div>

            @php $faqList = \App\Models\Faq::where('is_active', true)->orderBy('sort_order')->get(); @endphp
            @if($faqList->count() > 0)
            <div class="space-y-4" x-data="{ open: null }">
                @foreach ($faqList as $faq)
                    <div class="bg-white rounded-2xl border border-dark-100/5 overflow-hidden shadow-lg shadow-dark-300/5 transition-all duration-300 hover:shadow-xl hover:shadow-primary-500/5"
                        :class="{ 'ring-1 ring-primary-500/20 shadow-xl shadow-primary-500/5': open === {{ $loop->index }} }">
                        <button @click="open = open === {{ $loop->index }} ? null : {{ $loop->index }}"
                            class="w-full flex items-center justify-between p-6 md:p-7 text-left focus:outline-none">
                            <span class="font-bold text-dark-300 pr-4">{{ $faq->trans('question') }}</span>
                            <svg class="w-5 h-5 text-primary-500 shrink-0 transition-transform duration-300"
                                :class="{ 'rotate-180': open === {{ $loop->index }} }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open === {{ $loop->index }}" x-collapse x-cloak>
                            <div
                                class="px-6 md:px-7 pb-6 md:pb-7 text-dark-100/60 font-medium leading-relaxed border-t border-dark-100/5 pt-6">
                                {{ $faq->trans('answer') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 lg:py-28 overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-500 to-primary-700"></div>
        <div class="glow -top-40 -right-40 w-[500px] h-[500px] bg-white/15"></div>
        <div class="glow -bottom-40 -left-40 w-[500px] h-[500px] bg-white/10"></div>
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;">
        </div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-wrap justify-center gap-3 mb-10">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 text-white/70 rounded-full text-xs font-medium border border-white/10 backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ __('Konsultasi Gratis') }}
                </span>
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 text-white/70 rounded-full text-xs font-medium border border-white/10 backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5 text-primary-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ __('Respon Cepat') }}
                </span>
            </div>
            <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-6">
                {{ __('Siap Meningkatkan Bisnis Anda?') }}</h2>
            <p class="text-lg md:text-xl text-white/70 max-w-xl mx-auto mb-12 font-medium leading-relaxed">
                {{ __('Hubungi kami sekarang untuk konsultasi gratis. Tidak ada kewajiban, hanya diskusi tentang bagaimana kami dapat membantu bisnis Anda tumbuh.') }}
            </p>
            <div class="flex flex-wrap justify-center gap-5">
                <a href="https://wa.me/{{ \App\Models\Setting::get('contact_whatsapp', '6285967142870') }}" class="btn-white !px-10 !py-5 text-base group">
                    <svg class="w-5 h-5 inline mr-2 -mt-0.5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    {{ __('Chat via WhatsApp') }}
                </a>
                <a href="{{ route('contact') }}" class="btn-dark !px-10 !py-5 text-base group">
                    {{ __('Isi Form Kontak') }}
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <x-marketing-popup :popups="$popups ?? collect()" />
@endsection
