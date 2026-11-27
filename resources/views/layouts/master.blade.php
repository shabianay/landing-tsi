@php use App\Models\Setting; @endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#FFFFFF">
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

    {{-- Fonts (Fontshare): Cabinet Grotesk for display, Switzer for text. Non-render-blocking. --}}
    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.fontshare.com" crossorigin>
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@700,800&f[]=switzer@400,500,600,700&display=swap" media="print" data-async-style>
    <noscript>
        <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@700,800&f[]=switzer@400,500,600,700&display=swap">
    </noscript>

    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="bg-white pb-[4.75rem] lg:pb-0">
    <!--
    THESIS: The logo's stepped block stack is the page structure: proof lives inside the blocks, and one blue block per view carries the action. Refuses the floating-card, gradient-glow agency landing.
    OWN-WORLD: White ground, logo slate #415060 and ink #0F1720, brand blue #3498DB as solid fills with ink text. Shared 1px hairline cells instead of shadowed cards, near-square corners, Cabinet Grotesk display over Switzer text.
    STORY: A business owner or a vendor-comparing team sees in seconds what the studio builds, how fast, and the proof, then WhatsApps or books a consultation from any screen.
    FIRST VIEWPORT: Left 7/12: H1, lede, blue "Mulai Konsultasi Gratis" plus secondary portfolio link. Right 5/12: the logo mark rebuilt from four stat blocks, the blue block holding the 1-3 week timeline. Mobile: H1, CTA, compact block stack, sticky WhatsApp/Konsultasi bar.
    FORM: Grid Blok Logo (impeccable pick, #1 of 7), seed d359ee35.
    FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
    -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:bg-dark-300 focus:px-4 focus:py-3 focus:text-sm focus:font-semibold focus:text-white">
        {{ __('Langsung ke konten utama') }}
    </a>

    @include('layouts.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('layouts.footer')

    @php
        $waLink = 'https://wa.me/' . Setting::get('contact_whatsapp') . '?text=' . rawurlencode('Halo ' . Setting::get('site_name', 'The Solution ID') . '! Saya tertarik untuk konsultasi pembuatan website');
    @endphp

    {{-- Mobile action bar: the next step is always one tap away. --}}
    <div class="pb-safe fixed inset-x-0 bottom-0 z-40 border-t border-line bg-white lg:hidden">
        <div class="grid grid-cols-2 gap-2 px-3 py-2.5">
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-wa !min-h-11 !px-3 text-sm">
                <x-icon-whatsapp class="h-5 w-5" />
                WhatsApp
            </a>
            <a href="{{ route('contact') }}" class="btn-primary !min-h-11 !px-3 text-sm">{{ __('Konsultasi Gratis') }}</a>
        </div>
    </div>

    {{-- Desktop WhatsApp shortcut: a square that opens into a label on hover/focus. --}}
    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
       class="group fixed bottom-6 right-6 z-40 hidden h-14 items-center bg-[#1FAF54] px-4 text-white shadow-lg transition-colors hover:bg-[#178F44] lg:inline-flex"
       aria-label="{{ __('Chat via WhatsApp') }}">
        <x-icon-whatsapp class="h-6 w-6 shrink-0" />
        <span class="max-w-0 overflow-hidden whitespace-nowrap text-sm font-semibold transition-[max-width,margin] duration-300 ease-out-expo group-hover:ml-2.5 group-hover:max-w-[12rem] group-focus-visible:ml-2.5 group-focus-visible:max-w-[12rem]">{{ __('Chat via WhatsApp') }}</span>
    </a>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
