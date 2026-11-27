<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0F1720">
    @php use App\Models\Setting; @endphp
    <link rel="icon" type="image/webp" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">
    <link rel="apple-touch-icon" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">
    <title>@yield('title', 'Admin — TSI')</title>
    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.fontshare.com" crossorigin>
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@700,800&f[]=switzer@400,500,600,700&display=swap" media="print" data-async-style>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-dark-50 text-dark-200">
    <a href="#admin-main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:bg-white focus:px-4 focus:py-3 focus:text-sm focus:font-semibold">{{ __('Langsung ke konten utama') }}</a>

    <div class="flex min-h-screen">
        {{-- Desktop sidebar --}}
        <aside class="sticky top-0 hidden h-screen w-64 shrink-0 flex-col bg-dark-300 lg:flex">
            <div class="flex h-16 items-center border-b border-white/10 px-5">
                @include('layouts.partials.admin-brand')
            </div>
            @include('layouts.partials.admin-nav')
        </aside>

        {{-- Mobile header --}}
        <div class="fixed inset-x-0 top-0 z-50 flex h-14 items-center justify-between bg-dark-300 px-4 lg:hidden">
            @include('layouts.partials.admin-brand')
            <button type="button" id="admin-mobile-toggle" data-toggle-admin-sidebar class="-mr-2 flex h-11 w-11 items-center justify-center text-white" aria-controls="admin-sidebar" aria-label="{{ __('Buka menu') }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
            </button>
        </div>

        {{-- Mobile drawer --}}
        <div id="admin-sidebar-overlay" class="fixed inset-0 z-[55] hidden bg-dark-300/60 lg:hidden" data-toggle-admin-sidebar></div>
        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-[60] hidden w-72 max-w-[85vw] flex-col bg-dark-300 shadow-2xl lg:hidden [&:not(.hidden)]:flex">
            <div class="flex h-14 items-center justify-between border-b border-white/10 px-4">
                @include('layouts.partials.admin-brand')
                <button type="button" data-toggle-admin-sidebar class="-mr-2 flex h-11 w-11 items-center justify-center text-white" aria-label="{{ __('Tutup') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>
            @include('layouts.partials.admin-nav')
        </aside>

        <div class="min-w-0 flex-1">
            <div class="h-14 lg:hidden"></div>
            <main id="admin-main" class="mx-auto max-w-8xl p-4 sm:p-6 lg:p-10">
                @if (session('admin_weak_password') && ! request()->routeIs('admin.account.*'))
                    <div role="alert" class="mb-6 flex flex-wrap items-center justify-between gap-3 border border-red-300 bg-red-50 px-5 py-4 text-sm text-red-900">
                        <p><strong>Password admin masih default.</strong> Siapa pun bisa menebaknya. Ganti sekarang untuk mengamankan data pelamar dan konten.</p>
                        <a href="{{ route('admin.account.edit') }}" class="inline-flex min-h-10 items-center bg-red-700 px-4 font-semibold text-white hover:bg-red-800">Ganti password</a>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    @vite(['resources/js/app.js'])
</body>
</html>
