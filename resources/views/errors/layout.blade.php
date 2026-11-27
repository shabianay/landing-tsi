{{--
    Error pages. Deliberately standalone: no database (Setting), no JavaScript, so they still
    render when the database is down (500) or the site is in maintenance mode (503).
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <title>@yield('code') · @yield('title') — The Solution ID</title>
    <link rel="icon" type="image/webp" href="{{ asset('logo.webp') }}">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@700,800&f[]=switzer@400,500,600&display=swap">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-dark-50 font-sans text-dark-200 antialiased">
    <main class="wrap flex min-h-screen flex-col justify-center py-16">
        <a href="{{ url('/') }}" class="mb-12 inline-block w-fit">
            <img src="{{ asset('logo.webp') }}" alt="The Solution ID" class="h-10 w-auto">
        </a>

        <div class="grid gap-6 md:grid-cols-12 md:items-center md:gap-12">
            <p class="num font-display text-[7rem] font-extrabold leading-none tracking-tight text-primary-500 sm:text-[10rem] md:col-span-5 md:text-right lg:text-[12rem]" aria-hidden="true">@yield('code')</p>
            <div class="md:col-span-7 md:border-l md:border-line md:pl-12">
                <h1 class="display-2 text-dark-300">@yield('title')</h1>
                <p class="lede mt-4 max-w-xl">@yield('message')</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    @section('actions')
                        <a href="{{ url('/') }}" class="btn-primary">Ke beranda</a>
                        <a href="{{ url('/contact') }}" class="btn-secondary">Hubungi kami</a>
                    @show
                </div>
            </div>
        </div>
    </main>
</body>
</html>
