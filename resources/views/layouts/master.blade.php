@php use App\Models\Setting; @endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3498DB">
    <link rel="icon" type="image/webp" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">
    <link rel="apple-touch-icon" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">

    <title>@yield('title', Setting::get('site_name', 'The Solution ID') . ' — ' . __('Solusi Website Profesional untuk Bisnis Anda'))</title>
    <meta name="description" content="@yield('meta_description', __(':site adalah penyedia jasa pembuatan website dan aplikasi bisnis profesional untuk pelaku usaha di Indonesia.', ['site' => Setting::get('site_name', 'The Solution ID')]))">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ Setting::get('site_name', 'The Solution ID') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', Setting::get('site_name', 'The Solution ID') . ' — ' . __('Solusi Website Profesional untuk Bisnis Anda'))">
    <meta property="og:description" content="@yield('og_description', __(':site adalah penyedia jasa pembuatan website dan aplikasi bisnis profesional untuk pelaku usaha di Indonesia.', ['site' => Setting::get('site_name', 'The Solution ID')]))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('storage/og-default.jpg'))">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', Setting::get('site_name', 'The Solution ID') . ' — ' . __('Solusi Website Profesional untuk Bisnis Anda'))">
    <meta name="twitter:description" content="@yield('og_description', __(':site adalah penyedia jasa pembuatan website dan aplikasi bisnis profesional untuk pelaku usaha di Indonesia.', ['site' => Setting::get('site_name', 'The Solution ID')]))">
    <meta name="twitter:image" content="@yield('og_image', asset('storage/og-default.jpg'))">

    {{-- Hreflang --}}
    @php $currentUrl = url()->current(); @endphp
    <link rel="alternate" hreflang="id" href="{{ preg_replace('/\/lang\/(en|id)/', '', $currentUrl) }}">
    <link rel="alternate" hreflang="en" href="{{ $currentUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $currentUrl }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "{{ Setting::get('site_name', 'The Solution ID') }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset(Setting::get('logo', 'logo.webp')) }}",
        "description": "{{ __(':site adalah penyedia jasa pembuatan website dan aplikasi bisnis profesional untuk pelaku usaha di Indonesia.', ['site' => Setting::get('site_name', 'The Solution ID')]) }}",
        "address": {
            "@@type": "PostalAddress",
            "addressCountry": "ID"
        },
        "contactPoint": {
            "@@type": "ContactPoint",
            "telephone": "+62-{{ Setting::get('contact_phone') }}",
            "contactType": "customer service",
            "availableLanguage": ["Indonesian", "English"]
        },
        "sameAs": [
            "{{ Setting::get('social_instagram') }}",
            "{{ Setting::get('social_facebook') }}",
            "https://wa.me/{{ Setting::get('contact_whatsapp') }}"
        ]
    }
    </script>

    {{-- Font loading (non-render-blocking) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700,800&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap">
        <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700,800&display=swap">
    </noscript>

    {{-- Vite CSS (blocking, no FOUC) --}}
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="bg-white" x-data="{ pageLoading: true }" x-init="setTimeout(() => pageLoading = false, 350)">
    {{-- Page Loading Spinner --}}
    <div x-show="pageLoading" x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-white page-loader"
         style="transition: opacity 0.4s ease-out">
        <div class="flex flex-col items-center gap-4">
            <div class="w-10 h-10 border-[3px] border-primary-100 border-t-primary-500 rounded-full" style="animation: spin-smooth 0.8s linear infinite"></div>
            <span class="text-sm font-semibold text-dark-100/50">{{ __('Memuat...') }}</span>
        </div>
    </div>

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-3 focus:bg-primary-500 focus:text-white focus:rounded-xl focus:shadow-lg focus:outline-none focus:font-semibold focus:text-sm">
        {{ __('Langsung ke konten utama') }}
    </a>
    @include('layouts.navbar')
    
    <main id="main-content">
        @yield('content')
    </main>
    
    @include('layouts.footer')

    {{-- WhatsApp Floating Button --}}
    <a href="https://wa.me/{{ Setting::get('contact_whatsapp') }}?text=Halo%20{{ urlencode(Setting::get('site_name', 'The Solution ID')) }}!%20Saya%20tertarik%20untuk%20konsultasi%20pembuatan%20website" 
       target="_blank" 
       rel="noopener noreferrer"
       class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-xl shadow-green-500/30 hover:shadow-2xl hover:shadow-green-500/40 hover:scale-110 active:scale-95 transition-all duration-300 group"
       aria-label="{{ __('Chat via WhatsApp') }}">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        <span class="absolute -top-10 right-0 bg-dark-300 text-white text-xs font-medium px-3 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap shadow-lg" role="tooltip">
            {{ __('Chat via WhatsApp') }}
            <span class="absolute -bottom-1 right-4 w-2 h-2 bg-dark-300 rotate-45"></span>
        </span>
    </a>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
