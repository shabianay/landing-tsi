@extends('layouts.master')

@php use App\Models\Setting; @endphp

@section('title', __('Kontak') . ' — ' . Setting::get('site_name', 'The Solution ID'))
@section('meta_description', __('Hubungi :site untuk konsultasi gratis pembuatan website profesional. Kami siap membantu bisnis Anda tumbuh dengan solusi digital terbaik.', ['site' => Setting::get('site_name', 'The Solution ID')]))
@section('og_title', __('Kontak') . ' — ' . Setting::get('site_name', 'The Solution ID'))
@section('og_description', __('Hubungi :site untuk konsultasi gratis pembuatan website profesional. Kami siap membantu bisnis Anda tumbuh dengan solusi digital terbaik.', ['site' => Setting::get('site_name', 'The Solution ID')]))

@section('content')
<section class="relative pt-36 pb-16 lg:pt-52 lg:pb-28 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="glow top-0 -left-40 w-[500px] h-[500px] bg-primary-200/30"></div>
        <div class="glow bottom-0 -right-40 w-[400px] h-[400px] bg-blue-200/20"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav aria-label="Breadcrumb" class="mb-8">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm font-medium">
                <li><a href="{{ url('/') }}" class="text-dark-100/40 hover:text-primary-500 transition-colors">Home</a></li>
                <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-dark-100/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-dark-100/60">{{ __('Kontak') }}</span></li>
            </ol>
        </nav>
        <span class="section-label mb-8 inline-flex">{{ __('Hubungi Kami') }}</span>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-dark-300 leading-[1.05] tracking-tight mb-8 max-w-4xl">{{ __('Mulai Diskusi') }} <br><span class="gradient-text">{{ __('Proyek Anda') }}</span></h1>
        <p class="text-lg md:text-xl text-dark-100/60 max-w-2xl leading-relaxed font-medium">{{ __('Konsultasi awal gratis. Ceritakan kebutuhan bisnis Anda, dan kami akan menemukan solusi digital terbaik untuk Anda.') }}</p>
    </div>
</section>

<section class="py-20 lg:py-28 bg-dark-50 section-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-5 gap-12 lg:gap-16">
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-dark-100/5 shadow-xl shadow-dark-300/5 p-8 md:p-12">
                    @if(session('success'))
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200/50 text-green-700 px-5 py-4 rounded-2xl mb-8 flex items-center gap-3 font-semibold shadow-sm">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="group">
                                <label for="name" class="block text-sm font-bold text-dark-300 mb-2.5">{{ __('Nama Lengkap') }} <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-dark-100/30 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-primary-500 transition-colors pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <input type="text" name="name" id="name" required placeholder="{{ __('Masukkan nama lengkap') }}" class="w-full pl-11 pr-4 py-3.5 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 text-dark-300">
                                </div>
                            </div>
                            <div class="group">
                                <label for="email" class="block text-sm font-bold text-dark-300 mb-2.5">{{ __('Email') }} <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <svg class="w-5 h-5 text-dark-100/30 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-primary-500 transition-colors pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <input type="email" name="email" id="email" required placeholder="{{ __('Masukkan alamat email') }}" class="w-full pl-11 pr-4 py-3.5 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 text-dark-300">
                                </div>
                            </div>
                        </div>
                        <div class="group">
                            <label for="subject" class="block text-sm font-bold text-dark-300 mb-2.5">{{ __('Subjek') }}</label>
                            <div class="relative">
                                <svg class="w-5 h-5 text-dark-100/30 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-primary-500 transition-colors pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <input type="text" name="subject" id="subject" placeholder="{{ __('Misal: Permintaan pembuatan website toko online') }}" class="w-full pl-11 pr-4 py-3.5 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 text-dark-300">
                            </div>
                        </div>
                        <div class="group">
                            <label for="message" class="block text-sm font-bold text-dark-300 mb-2.5">{{ __('Pesan') }} <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <svg class="w-5 h-5 text-dark-100/30 absolute left-4 top-4 group-focus-within:text-primary-500 transition-colors pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <textarea name="message" id="message" rows="5" required placeholder="{{ __('Jelaskan kebutuhan bisnis Anda secara singkat...') }}" class="w-full pl-11 pr-4 py-3.5 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 text-dark-300 resize-none"></textarea>
                            </div>
                        </div>
                        @if(config('services.google.recaptcha_site_key'))
                        <div class="g-recaptcha" data-sitekey="{{ config('services.google.recaptcha_site_key') }}"></div>
                        @endif
                        <button type="submit" class="btn-primary w-full sm:w-auto !px-10 !py-4 text-base group">
                            {{ __('Kirim Pesan Sekarang') }}
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-dark-100/5 shadow-lg shadow-dark-300/5 p-6 flex items-start gap-5 hover:shadow-xl hover:border-primary-500/20 hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md group-hover:shadow-primary-500/20 transition-all duration-300">
                        <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-dark-300 mb-1.5">{{ __('Telepon / WhatsApp') }}</h3>
                        <p class="text-dark-100/60 font-medium break-all">{{ Setting::get('contact_phone', '-') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-dark-100/5 shadow-lg shadow-dark-300/5 p-6 flex items-start gap-5 hover:shadow-xl hover:border-emerald-500/20 hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md group-hover:shadow-emerald-500/20 transition-all duration-300">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-dark-300 mb-1.5">{{ __('Email') }}</h3>
                        <p class="text-dark-100/60 font-medium break-all">{{ Setting::get('contact_email', '-') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-dark-100/5 shadow-lg shadow-dark-300/5 p-6 flex items-start gap-5 hover:shadow-xl hover:border-violet-500/20 hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-50 to-violet-100 rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md group-hover:shadow-violet-500/20 transition-all duration-300">
                        <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-dark-300 mb-1.5">{{ __('Website') }}</h3>
                        <a href="{{ Setting::get('contact_website', '#') }}" class="text-primary-500 font-bold hover:underline break-all">{{ Setting::get('contact_website', '-') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 lg:py-28 overflow-hidden relative">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-700 via-primary-600 to-indigo-800"></div>
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-white/[0.06] to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-full bg-gradient-to-r from-white/[0.04] to-transparent"></div>
    <div class="glow -top-48 -right-48 w-[600px] h-[600px] bg-white/[0.12]"></div>
    <div class="glow -bottom-48 -left-48 w-[600px] h-[600px] bg-indigo-400/20"></div>
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-8 md:mb-10">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-300 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
            </span>
            <span class="text-white/80 text-sm font-semibold tracking-wide">{{ __('Konsultasi Gratis — Tanpa Kewajiban') }}</span>
        </div>

        <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-[1.1] mb-6">
            {{ __('Siap Tingkatkan') }}<br>
            <span class="relative">{{ __('Bisnis Anda?') }}
                <svg class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-48 md:w-64 h-3 text-white/20" viewBox="0 0 200 12" fill="currentColor">
                    <path d="M0 8c40-10 160-10 200 0"/>
                </svg>
            </span>
        </h2>

        <p class="text-base md:text-lg text-white/60 max-w-2xl mx-auto mb-10 md:mb-12 font-medium leading-relaxed">
            {{ __('Hubungi kami sekarang untuk konsultasi gratis. Tidak ada kewajiban, hanya diskusi tentang bagaimana kami dapat membantu bisnis Anda tumbuh.') }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="https://wa.me/{{ Setting::get('contact_whatsapp') }}"
               class="group relative inline-flex items-center gap-3 px-8 py-4 bg-white text-primary-700 rounded-2xl font-bold text-base shadow-2xl shadow-white/25 hover:shadow-white/40 hover:-translate-y-0.5 transition-all duration-300 w-full sm:w-auto justify-center">
                <span class="absolute inset-0 rounded-2xl bg-gradient-to-r from-white via-primary-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <svg class="w-5 h-5 relative z-10 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span class="relative z-10">{{ __('Chat via WhatsApp') }}</span>
                <svg class="w-4 h-4 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}"
               class="group inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-2xl font-bold text-base border border-white/20 hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-300 w-full sm:w-auto justify-center">
                <svg class="w-5 h-5 text-white/70 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                {{ __('Isi Form Kontak') }}
            </a>
        </div>
    </div>
</section>

@if(config('services.google.recaptcha_site_key'))
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif
@endsection
