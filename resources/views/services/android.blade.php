@extends('layouts.master')

@php
    use App\Models\Setting;

    $title = __('Jasa Pembuatan Aplikasi Android');
    $lede = __('Dari desain sampai file APK siap pakai, dikerjakan rapi sesuai kebutuhan Anda.');
    $waLink = 'https://wa.me/' . Setting::get('contact_whatsapp') . '?text=' . rawurlencode('Halo The Solution ID! Saya ingin konsultasi pembuatan aplikasi Android');

    $deliverables = [
        __('Desain UI sesuai permintaan'),
        __('Source code lengkap'),
        __('File APK siap install'),
        __('Bantu upload ke Play Store'),
    ];

    $appTypes = [
        __('Aplikasi Tugas Kuliah/Skripsi'),
        __('Toko Online'),
        __('Kasir (POS)'),
        __('Absensi'),
        __('Inventaris/Stok Barang'),
        __('Pemesanan & Booking'),
        __('Sekolah/E-Learning'),
        'Company Profile',
        __('Konversi Website ke Aplikasi'),
        __('Lainnya (Konsultasi Dulu)'),
    ];
@endphp

@section('title', $title . ' — The Solution ID')
@section('meta_description', __('Jasa pembuatan aplikasi Android: desain UI custom, source code lengkap, file APK siap install, dan bantu upload ke Play Store. Gratis konsultasi.'))
@section('og_title', $title . ' — The Solution ID')
@section('og_description', __('Jasa pembuatan aplikasi Android: desain UI custom, source code lengkap, file APK siap install, dan bantu upload ke Play Store. Gratis konsultasi.'))

@section('content')
    {{-- Header with the app drawn in blocks --}}
    <header class="overflow-hidden border-b border-line">
        <div class="wrap grid gap-12 pb-14 pt-8 md:pt-12 lg:grid-cols-12 lg:items-center lg:pb-20">
            <div class="lg:col-span-7">
                <nav aria-label="Breadcrumb" class="mb-6 md:mb-8">
                    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-dark-100">
                        <li><a href="{{ route('home') }}" class="hover:text-dark-300 hover:underline">{{ __('Beranda') }}</a></li>
                        <li class="flex items-center gap-2" aria-current="page"><span class="h-1 w-1 bg-dark-100/60" aria-hidden="true"></span><span class="font-medium text-dark-300">{{ __('Aplikasi Android') }}</span></li>
                    </ol>
                </nav>
                <h1 class="display-1">
                    {{ __('Jasa Pembuatan') }}
                    <span class="mt-2 block w-fit bg-primary-500 px-3 pb-1 text-dark-300 md:px-4">{{ __('Aplikasi Android') }}</span>
                </h1>
                <p class="mt-8 font-display text-2xl font-bold leading-snug text-dark-300 md:text-3xl">
                    {{ __('Ide Anda,') }} <span class="text-primary-600">{{ __('kami jadikan aplikasi.') }}</span>
                </p>
                <p class="lede mt-4">{{ $lede }}</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-primary !min-h-14 !px-8 text-base">
                        {{ __('Konsultasi Gratis') }} <x-icon-arrow class="h-5 w-5" />
                    </a>
                    <a href="#jenis-aplikasi" class="btn-secondary !min-h-14 !px-8 text-base">{{ __('Aplikasi yang Bisa Dibuat') }}</a>
                </div>
            </div>
            <div class="px-8 lg:col-span-5 lg:px-6">
                <x-phone-mock />
            </div>
        </div>
    </header>

    {{-- What you get --}}
    <section class="py-16 md:py-24">
        <div class="wrap grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <h2 class="display-2">{{ __('Yang Anda Dapatkan') }}</h2>
            </div>
            <ul class="cells sm:grid-cols-2 lg:col-span-8">
                @foreach ($deliverables as $item)
                    <li class="flex items-center gap-4 p-6 md:p-8">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center bg-primary-500 text-dark-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 5 5L19 7"/></svg>
                        </span>
                        <span class="text-lg font-bold text-dark-300">{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- App types --}}
    <section id="jenis-aplikasi" class="scroll-mt-20 bg-dark-300 py-16 text-white md:py-24">
        <div class="wrap">
            <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                <h2 class="display-2 !text-white lg:col-span-6">{{ __('Aplikasi yang Bisa Dibuat') }}</h2>
                <p class="text-white/75 lg:col-span-5 lg:col-start-8">{{ __('Kebutuhan Anda tidak ada di daftar? Ceritakan saja, kami bantu cari solusinya.') }}</p>
            </div>
            <ol class="mt-10 grid gap-px border border-white/10 bg-white/10 sm:grid-cols-2 lg:grid-cols-5 md:mt-14">
                @foreach ($appTypes as $type)
                    <li class="flex min-h-28 flex-col justify-between gap-6 bg-dark-300 p-5 {{ $loop->last ? 'bg-primary-500 text-dark-300' : '' }}">
                        <span class="num font-display text-sm font-extrabold {{ $loop->last ? 'text-dark-300' : 'text-primary-400' }}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="font-semibold leading-snug">{{ $type }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Close --}}
    <section class="bg-primary-500 text-dark-300">
        <div class="wrap grid gap-8 py-14 md:py-20 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7">
                <h2 class="display-2">{{ __('Gratis Konsultasi') }}</h2>
                <p class="mt-4 text-lg text-dark-300/90">{{ __('Revisi sampai acc.') }} <span class="text-sm">({{ __('S&K berlaku') }})</span></p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:col-span-5 lg:justify-end">
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-ink !min-h-14 !px-7">
                    <x-icon-whatsapp class="h-5 w-5 text-[#3EDB7A]" />
                    {{ Setting::get('contact_phone', __('Chat via WhatsApp')) }}
                </a>
                <a href="{{ route('contact') }}" class="btn !min-h-14 border border-dark-300 !px-7 text-dark-300 hover:bg-dark-300 hover:text-white">{{ __('Isi Form Kontak') }}</a>
            </div>
        </div>
    </section>
@endsection
