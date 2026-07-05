@extends('layouts.master')

@section('title', __('Tentang') . ' — The Solution ID')
@section('meta_description', __('The Solution ID adalah mitra digital terpercaya bagi pelaku bisnis Indonesia. Kami menghadirkan website profesional yang meningkatkan kredibilitas dan penjualan bisnis Anda.'))
@section('og_title', __('Tentang') . ' — The Solution ID')
@section('og_description', __('The Solution ID adalah mitra digital terpercaya bagi pelaku bisnis Indonesia. Kami menghadirkan website profesional yang meningkatkan kredibilitas dan penjualan bisnis Anda.'))

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
                <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-dark-100/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-dark-100/60">{{ __('Tentang Kami') }}</span></li>
            </ol>
        </nav>
        <span class="section-label mb-8 inline-flex">{{ __('Tentang Kami') }}</span>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-dark-300 leading-[1.05] tracking-tight mb-8 max-w-4xl">{{ __('Mitra Digital Terpercaya') }} <br><span class="gradient-text">{{ __('untuk Bisnis Indonesia') }}</span></h1>
        <p class="text-lg md:text-xl text-dark-100/60 max-w-2xl leading-relaxed font-medium">{{ __('Kami membantu pelaku bisnis Indonesia membangun kehadiran digital yang profesional, efektif, dan berorientasi pada hasil.') }}</p>
    </div>
</section>

<section class="py-20 lg:py-28 bg-dark-50 section-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-20 items-center">
            <div>
                <h2 class="text-dark-300 text-4xl md:text-5xl font-bold tracking-tight mb-8"><span class="gradient-text">{{ __('Visi') }}</span> {{ __('Kami') }}</h2>
                <p class="text-dark-100/60 leading-relaxed mb-12 text-lg font-medium">{{ __('Kami percaya bahwa setiap bisnis, baik skala kecil maupun besar, berhak memiliki kehadiran digital yang profesional. Dengan website yang dirancang dengan baik, bisnis Anda dapat menjangkau lebih banyak pelanggan, membangun kredibilitas, dan meningkatkan penjualan secara signifikan.') }}</p>
                <div class="grid grid-cols-3 gap-8">
                    <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-6 border border-dark-100/5">
                        <div class="text-4xl font-bold gradient-text mb-2">{{ \App\Models\Setting::get('stat_projects', '50+') }}</div>
                        <div class="text-sm text-dark-100/50 font-medium">{{ __('Proyek Selesai') }}</div>
                    </div>
                    <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-6 border border-dark-100/5">
                        <div class="text-4xl font-bold gradient-text mb-2">{{ \App\Models\Setting::get('stat_clients', '30+') }}</div>
                        <div class="text-sm text-dark-100/50 font-medium">{{ __('Klien Aktif') }}</div>
                    </div>
                    <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-6 border border-dark-100/5">
                        <div class="text-4xl font-bold gradient-text mb-2">{{ \App\Models\Setting::get('stat_rating', '4.9/5') }}</div>
                        <div class="text-sm text-dark-100/50 font-medium">{{ __('Rating Kepuasan') }}</div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div class="card group">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-bold text-dark-300 mb-2">{{ __('Cepat & Efisien') }}</h3>
                    <p class="text-dark-100/60 text-sm font-medium">{{ __('Proses pengerjaan transparan dan tepat waktu.') }}</p>
                </div>
                <div class="card group">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </div>
                    <h3 class="font-bold text-dark-300 mb-2">{{ __('Custom Desain') }}</h3>
                    <p class="text-dark-100/60 text-sm font-medium">{{ __('Tampilan unik sesuai branding bisnis Anda.') }}</p>
                </div>
                <div class="card group">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-dark-300 mb-2">{{ __('SEO Optimized') }}</h3>
                    <p class="text-dark-100/60 text-sm font-medium">{{ __('Mudah ditemukan di Google dan mesin pencari.') }}</p>
                </div>
                <div class="card group">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="font-bold text-dark-300 mb-2">{{ __('Garansi Pasti') }}</h3>
                    <p class="text-dark-100/60 text-sm font-medium">{{ __('Layanan purna jual untuk ketenangan pikiran.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
