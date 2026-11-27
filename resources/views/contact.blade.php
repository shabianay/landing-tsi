@extends('layouts.master')

@php use App\Models\Setting; @endphp

@section('title', __('Kontak') . ' — ' . Setting::get('site_name', 'The Solution ID'))
@section('meta_description', __('Hubungi :site untuk konsultasi gratis pembuatan website profesional. Kami siap membantu bisnis Anda tumbuh dengan solusi digital terbaik.', ['site' => Setting::get('site_name', 'The Solution ID')]))
@section('og_title', __('Kontak') . ' — ' . Setting::get('site_name', 'The Solution ID'))
@section('og_description', __('Hubungi :site untuk konsultasi gratis pembuatan website profesional. Kami siap membantu bisnis Anda tumbuh dengan solusi digital terbaik.', ['site' => Setting::get('site_name', 'The Solution ID')]))

@section('content')
    <x-page-header
        :title="__('Mulai Diskusi') . ' ' . __('Proyek Anda')"
        :lede="__('Konsultasi awal gratis. Ceritakan kebutuhan bisnis Anda, dan kami akan menemukan solusi digital terbaik untuk Anda.')"
        :crumbs="[['label' => __('Kontak')]]" />

    <section class="py-12 md:py-20">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            {{-- Fastest route first: WhatsApp and direct details. --}}
            <aside class="lg:order-2 lg:col-span-4 lg:col-start-9">
                <div class="space-y-1.5">
                    <a href="https://wa.me/{{ Setting::get('contact_whatsapp') }}" target="_blank" rel="noopener noreferrer"
                       class="group flex items-center justify-between gap-4 bg-dark-300 p-5 text-white transition-colors hover:bg-dark-200">
                        <span>
                            <span class="block text-sm text-white/75">{{ __('Telepon / WhatsApp') }}</span>
                            <span class="num mt-1 block text-lg font-semibold">{{ Setting::get('contact_phone', '-') }}</span>
                        </span>
                        <x-icon-whatsapp class="h-7 w-7 shrink-0 text-[#3EDB7A]" />
                    </a>
                    @if (Setting::get('contact_email'))
                        <a href="mailto:{{ Setting::get('contact_email') }}" class="group block border border-line p-5 transition-colors hover:border-dark-300">
                            <span class="block text-sm text-dark-100">{{ __('Email') }}</span>
                            <span class="mt-1 block break-all font-semibold text-dark-300 group-hover:underline">{{ Setting::get('contact_email') }}</span>
                        </a>
                    @endif
                    @if (Setting::get('contact_website'))
                        <a href="{{ Setting::get('contact_website') }}" target="_blank" rel="noopener noreferrer" class="group block border border-line p-5 transition-colors hover:border-dark-300">
                            <span class="block text-sm text-dark-100">{{ __('Website') }}</span>
                            <span class="mt-1 block break-all font-semibold text-dark-300 group-hover:underline">{{ preg_replace('#^https?://#', '', Setting::get('contact_website')) }}</span>
                        </a>
                    @endif
                </div>
                <p class="mt-6 flex items-center gap-3 text-sm text-dark-100">
                    <span class="h-2.5 w-2.5 shrink-0 bg-primary-500" aria-hidden="true"></span>
                    {{ __('Konsultasi Gratis — Tanpa Kewajiban') }}
                </p>
            </aside>

            <div class="lg:order-1 lg:col-span-7">
                @if (session('success'))
                    <div class="mb-8 flex items-start gap-4 border border-primary-300 bg-primary-50 p-5 text-dark-300" role="status">
                        <span class="mt-1 h-3 w-3 shrink-0 bg-primary-500" aria-hidden="true"></span>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-8 border border-red-300 bg-red-50 p-5 text-sm text-red-800" role="alert">
                        <p class="font-semibold">{{ __('Periksa kembali formulir:') }}</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="absolute -left-[10000px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                        <label for="website">{{ __('Jangan isi kolom ini') }}</label>
                        <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="field-label">{{ __('Nama Lengkap') }} <span class="text-red-700">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name"
                                   placeholder="{{ __('Masukkan nama lengkap') }}" class="field" @error('name') aria-invalid="true" @enderror>
                            @error('name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="field-label">{{ __('Email') }} <span class="text-red-700">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" inputmode="email"
                                   placeholder="{{ __('Masukkan alamat email') }}" class="field" @error('email') aria-invalid="true" @enderror>
                            @error('email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="subject" class="field-label">{{ __('Subjek') }}</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="255"
                               placeholder="{{ __('Misal: Permintaan pembuatan website toko online') }}" class="field" @error('subject') aria-invalid="true" @enderror>
                        @error('subject')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="message" class="field-label">{{ __('Pesan') }} <span class="text-red-700">*</span></label>
                        <textarea name="message" id="message" rows="6" required maxlength="5000"
                                  placeholder="{{ __('Jelaskan kebutuhan bisnis Anda secara singkat...') }}" class="field resize-y" @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                        @error('message')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    @if (config('services.google.recaptcha_site_key'))
                        <div>
                            <div class="g-recaptcha" data-sitekey="{{ config('services.google.recaptcha_site_key') }}"></div>
                            @error('g-recaptcha-response')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    <button type="submit" class="btn-primary w-full !min-h-14 !px-10 text-base sm:w-auto">
                        {{ __('Kirim Pesan Sekarang') }} <x-icon-arrow class="h-5 w-5" />
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if (config('services.google.recaptcha_site_key'))
        @push('scripts')
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endpush
    @endif
@endsection
