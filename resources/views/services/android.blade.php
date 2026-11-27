@extends('layouts.master')

@php
    use App\Models\Setting;
    use App\Support\AndroidAppExamples;

    $examples = AndroidAppExamples::all();
    $waLink = 'https://wa.me/' . Setting::get('contact_whatsapp') . '?text=' . rawurlencode('Halo The Solution ID! Saya ingin konsultasi pembuatan aplikasi Android');

    $steps = [
        [__('Ceritakan idenya'), __('Konsultasi gratis. Kita bahas siapa penggunanya, fitur apa yang wajib ada, dan apa yang bisa menyusul.')],
        [__('Desain tampilan'), __('Tampilan aplikasi dirancang sesuai permintaan Anda, dan direvisi sampai Anda setuju (S&K berlaku).')],
        [__('Dibangun jadi APK'), __('Anda menerima file APK yang siap dipasang di HP, beserta source code lengkapnya.')],
        [__('Terbit di Play Store'), __('Kami bantu proses upload aplikasi Anda ke Play Store.')],
    ];
@endphp

@section('title', __('Jasa Pembuatan Aplikasi Android') . ' — The Solution ID')
@section('meta_description', __('Jasa pembuatan aplikasi Android: desain UI custom, source code lengkap, file APK siap install, dan bantu upload ke Play Store. Gratis konsultasi.'))
@section('og_title', __('Jasa Pembuatan Aplikasi Android') . ' — The Solution ID')
@section('og_description', __('Jasa pembuatan aplikasi Android: desain UI custom, source code lengkap, file APK siap install, dan bantu upload ke Play Store. Gratis konsultasi.'))

@section('content')
    {{-- First viewport: pick an app type, the phone shows it. --}}
    <header class="overflow-hidden border-b border-line" x-data="{ app: 'toko' }">
        <div class="wrap grid gap-12 pb-14 pt-8 md:pt-12 lg:grid-cols-12 lg:items-center lg:pb-20">
            <div class="min-w-0 lg:col-span-7">
                <nav aria-label="Breadcrumb" class="mb-6 md:mb-8">
                    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-dark-100">
                        <li><a href="{{ route('home') }}" class="hover:text-dark-300 hover:underline">{{ __('Beranda') }}</a></li>
                        <li class="flex items-center gap-2" aria-current="page"><span class="h-1 w-1 bg-dark-100/60" aria-hidden="true"></span><span class="font-medium text-dark-300">{{ __('Aplikasi Android') }}</span></li>
                    </ol>
                </nav>

                <h1 class="display-1 max-w-[14ch]">{{ __('Aplikasi Android untuk bisnis Anda.') }}</h1>
                <p class="lede mt-6">{{ __('Kami rancang tampilannya dan kami bangun aplikasinya. Anda terima file APK dan source code lengkapnya, lalu kami bantu terbitkan di Play Store.') }}</p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-primary !min-h-14 !px-8 text-base">
                        {{ __('Konsultasi Gratis') }} <x-icon-arrow class="h-5 w-5" />
                    </a>
                    <a href="#proses" class="btn-secondary !min-h-14 !px-8 text-base">{{ __('Lihat prosesnya') }}</a>
                </div>

                <div class="mt-12">
                    <p id="app-picker-label" class="text-sm font-semibold text-dark-300">{{ __('Mau bikin aplikasi apa? Pilih untuk melihat contohnya.') }}</p>
                    <div class="-mx-5 mt-3 overflow-x-auto px-5 sm:mx-0 sm:px-0" role="group" aria-labelledby="app-picker-label">
                        <ul class="flex gap-2 whitespace-nowrap sm:flex-wrap sm:whitespace-normal">
                            @foreach ($examples as $key => $example)
                                <li>
                                    <button type="button" @click="app = '{{ $key }}'" :aria-pressed="(app === '{{ $key }}').toString()"
                                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                            class="inline-flex min-h-10 items-center border px-3.5 text-sm font-semibold transition-colors"
                                            :class="app === '{{ $key }}' ? 'border-dark-300 bg-dark-300 text-white' : 'border-line bg-white text-dark-100 hover:border-dark-300 hover:text-dark-300'">
                                        {{ $example['label'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="mt-3 text-xs text-dark-100">{{ __('Contoh tampilan untuk ilustrasi, bukan aplikasi klien.') }}</p>
                </div>
            </div>

            <div class="px-6 lg:col-span-5">
                <x-app-phone :screens="$examples" :interactive="true" />
            </div>
        </div>
    </header>

    {{-- From idea to Play Store: each step adds a block. --}}
    <section id="proses" class="scroll-mt-20 py-16 md:py-24">
        <div class="wrap">
            <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                <h2 class="display-2 lg:col-span-6">{{ __('Dari ide sampai Play Store') }}</h2>
                <p class="text-dark-100 lg:col-span-5 lg:col-start-8">{{ __('Empat tahap, dan Anda tahu apa yang diterima di setiap tahapnya.') }}</p>
            </div>
            <ol class="cells mt-10 sm:grid-cols-2 md:mt-14 lg:grid-cols-4">
                @foreach ($steps as [$title, $body])
                    <li class="flex flex-col p-6 md:p-8">
                        <div class="flex items-end gap-1" aria-hidden="true">
                            @for ($b = 1; $b <= count($steps); $b++)
                                <span class="h-3 w-3 {{ $b < $loop->iteration ? 'bg-dark-100' : ($b === $loop->iteration ? 'bg-primary-500' : 'border border-dark-100/30') }}"></span>
                            @endfor
                        </div>
                        <p class="num mt-6 text-sm font-semibold text-primary-700">{{ __('Langkah') }} {{ $loop->iteration }}</p>
                        <h3 class="mt-1 text-lg font-bold text-dark-300">{{ $title }}</h3>
                        <p class="mt-2 text-[0.95rem] text-dark-100">{{ $body }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Close --}}
    <section class="bg-primary-500 text-dark-300">
        <div class="wrap grid gap-8 py-14 md:py-20 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7">
                <h2 class="display-2">{{ __('Punya ide aplikasi? Ceritakan dulu, gratis.') }}</h2>
                <p class="mt-5 max-w-[52ch] text-lg text-dark-300/90">{{ __('Kami bantu memetakan fiturnya sebelum Anda memutuskan apa pun.') }}</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:col-span-5 lg:justify-end">
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-ink !min-h-14 !px-7">
                    <x-icon-whatsapp class="h-5 w-5 text-[#3EDB7A]" />
                    {{ __('Chat via WhatsApp') }}
                </a>
                <a href="{{ route('contact') }}" class="btn !min-h-14 border border-dark-300 !px-7 text-dark-300 hover:bg-dark-300 hover:text-white">{{ __('Isi Form Kontak') }}</a>
            </div>
        </div>
    </section>
@endsection
