@extends('layouts.master')

@section('title', __('Tentang') . ' — The Solution ID')
@section('meta_description', __('The Solution ID adalah mitra digital terpercaya bagi pelaku bisnis Indonesia. Kami menghadirkan website profesional yang meningkatkan kredibilitas dan penjualan bisnis Anda.'))
@section('og_title', __('Tentang') . ' — The Solution ID')
@section('og_description', __('The Solution ID adalah mitra digital terpercaya bagi pelaku bisnis Indonesia. Kami menghadirkan website profesional yang meningkatkan kredibilitas dan penjualan bisnis Anda.'))

@php use App\Models\Setting; @endphp

@section('content')
    <x-page-header
        :title="__('Mitra Digital Terpercaya') . ' ' . __('untuk Bisnis Indonesia')"
        :lede="__('Kami membantu pelaku bisnis Indonesia membangun kehadiran digital yang profesional, efektif, dan berorientasi pada hasil.')"
        :crumbs="[['label' => __('Tentang Kami')]]" />

    <section class="py-16 md:py-24">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <h2 class="display-2">{{ __('Visi') }} {{ __('Kami') }}</h2>
                <p class="mt-6 max-w-[60ch] text-lg leading-relaxed text-dark-100">
                    {{ __('Kami percaya bahwa setiap bisnis, baik skala kecil maupun besar, berhak memiliki kehadiran digital yang profesional. Dengan website yang dirancang dengan baik, bisnis Anda dapat menjangkau lebih banyak pelanggan, membangun kredibilitas, dan meningkatkan penjualan secara signifikan.') }}
                </p>
            </div>

            <dl class="grid grid-cols-3 gap-1.5 self-start sm:gap-2 lg:col-span-5 lg:col-start-8">
                @foreach ([
                    [Setting::get('stat_projects', '50+'), __('Proyek Selesai'), 'bg-dark-100 text-white'],
                    [Setting::get('stat_clients', '30+'), __('Klien Aktif'), 'bg-dark-100 text-white'],
                    [Setting::get('stat_rating', '4.9/5'), __('Rating Kepuasan'), 'bg-primary-500 text-dark-300'],
                ] as [$value, $label, $tone])
                    <div class="flex aspect-square flex-col justify-between p-3 sm:p-5 {{ $tone }}">
                        <dt class="text-xs font-medium opacity-90 sm:text-sm">{{ $label }}</dt>
                        <dd class="num font-display text-2xl font-extrabold leading-none sm:text-4xl">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    <section class="border-t border-line bg-dark-50 py-16 md:py-24">
        <div class="wrap">
            <ul class="cells sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    [__('Cepat & Efisien'), __('Proses pengerjaan transparan dan tepat waktu.'), 'M13 10V3L4 14h7v7l9-11h-7z'],
                    [__('Custom Desain'), __('Tampilan unik sesuai branding bisnis Anda.'), 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                    [__('SEO Optimized'), __('Mudah ditemukan di Google dan mesin pencari.'), 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                    [__('Garansi Pasti'), __('Layanan purna jual untuk ketenangan pikiran.'), 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ] as [$title, $body, $icon])
                    <li class="p-6 md:p-8">
                        <span class="flex h-11 w-11 items-center justify-center bg-dark-300 text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icon }}" /></svg>
                        </span>
                        <h3 class="mt-6 text-lg font-bold text-dark-300">{{ $title }}</h3>
                        <p class="mt-2 text-[0.95rem] text-dark-100">{{ $body }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    @if (isset($partners) && $partners->isNotEmpty())
        <section class="py-16 md:py-20">
            <div class="wrap">
                <h2 class="text-sm font-semibold text-dark-100">{{ __('Dipercaya oleh') }}</h2>
                <ul class="mt-5 grid grid-cols-2 border-l border-t border-line sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                    @foreach ($partners as $logo)
                        <li class="flex h-32 items-center justify-center border-b border-r border-line bg-white p-3 sm:h-36 lg:h-40 lg:p-4">
                            @if ($logo->logo)
                                <img src="{{ asset('storage/' . $logo->logo) }}" alt="{{ $logo->name }}" width="400" height="280"
                                     class="h-full w-full object-contain" loading="lazy" decoding="async">
                            @else
                                <span class="text-center text-sm font-semibold text-dark-100">{{ $logo->name }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @include('partials.cta-band')
@endsection
