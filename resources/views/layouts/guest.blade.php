<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3498DB">
    @php use App\Models\Setting; @endphp
    <link rel="icon" type="image/webp" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">

    <title>{{ config('app.name', 'The Solution ID') }} — {{ __('Admin') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-white">
    <div class="relative min-h-screen flex items-center justify-center px-4 py-12 overflow-hidden bg-gradient-to-br from-dark-300 via-dark-200/95 to-dark-300">
        {{-- Decorative grid pattern --}}
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 32px 32px;"></div>

        {{-- Glow effects --}}
        <div class="glow top-1/3 -left-48 w-[700px] h-[700px] bg-primary-500/10"></div>
        <div class="glow bottom-0 -right-48 w-[600px] h-[600px] bg-blue-500/8"></div>

        {{-- Card --}}
        <div class="relative z-10 w-full max-w-md">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:shadow-xl group-hover:shadow-primary-500/40 transition-all duration-300">
                        <span class="text-white font-display font-bold text-xl">TSI</span>
                    </div>
                    <div class="text-left">
                        <span class="text-xl font-display font-bold text-white tracking-tight block leading-tight">The <span class="text-primary-400">Solution</span></span>
                        <span class="text-xs text-white/30 font-medium tracking-widest uppercase block">Solusi Digital Anda</span>
                    </div>
                </a>
            </div>

            {{-- Form Card --}}
            <div class="bg-gradient-to-b from-white/[0.07] to-white/[0.03] backdrop-blur-xl border border-white/10 rounded-3xl p-8 md:p-10 shadow-2xl shadow-dark-300/60">
                <div class="text-center mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-primary-500/10 border border-primary-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white">{{ __('Masuk') }}</h1>
                    <p class="text-white/40 text-sm font-medium mt-1.5">{{ __('Masuk ke panel admin') }}</p>
                </div>

                {{ $slot }}
            </div>

            {{-- Footer --}}
            <p class="text-center text-white/15 text-xs mt-6 font-medium">
                &copy; {{ date('Y') }} The Solution ID. {{ __('Hak cipta dilindungi.') }}
            </p>
        </div>
    </div>
</body>
</html>
