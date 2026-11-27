<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#FFFFFF">
    @php use App\Models\Setting; @endphp
    <link rel="icon" type="image/webp" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">

    <title>{{ config('app.name', 'The Solution ID') }} — {{ __('Admin') }}</title>

    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.fontshare.com" crossorigin>
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@700,800&f[]=switzer@400,500,600,700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-dark-50">
    <div class="grid min-h-screen lg:grid-cols-2">
        {{-- Brand panel: the block mark at architectural scale. --}}
        <div class="relative hidden overflow-hidden bg-dark-300 lg:block" aria-hidden="true">
            <div class="absolute inset-0 grid place-items-center">
                <div class="grid grid-cols-2 grid-rows-3 gap-3" style="width: min(22rem, 60%); aspect-ratio: 2 / 3;">
                    <span class="block-drop bg-dark-100" style="grid-area: 1 / 1; --i: 0"></span>
                    <span class="block-drop bg-dark-100" style="grid-area: 2 / 1; --i: 1"></span>
                    <span class="block-drop bg-dark-100" style="grid-area: 2 / 2; --i: 2"></span>
                    <span class="block-drop bg-primary-500" style="grid-area: 3 / 1; --i: 3"></span>
                </div>
            </div>
        </div>

        <main class="flex flex-col justify-center px-5 py-12 sm:px-12">
            <div class="mx-auto w-full max-w-sm">
                <a href="{{ url('/') }}" class="inline-block" aria-label="The Solution ID — {{ __('Beranda') }}">
                    <img src="{{ asset(Setting::get('logo', 'logo.webp')) }}" alt="The Solution ID" width="116" height="60" class="h-12 w-auto">
                </a>
                <h1 class="display-2 mt-10">{{ __('Masuk') }}</h1>
                <p class="mt-2 text-dark-100">{{ __('Masuk ke panel admin') }}</p>

                <div class="mt-8">
                    {{ $slot }}
                </div>

                <p class="mt-12 text-sm text-dark-100">&copy; {{ date('Y') }} The Solution ID. {{ __('Hak cipta dilindungi.') }}</p>
            </div>
        </main>
    </div>
</body>
</html>
