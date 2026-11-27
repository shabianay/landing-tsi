@php
    $navItems = [
        ['route' => 'home', 'match' => 'home', 'label' => __('Beranda')],
        ['route' => 'services.android', 'match' => 'services.android', 'label' => __('Aplikasi Android')],
        ['route' => 'portfolio.index', 'match' => 'portfolio.*', 'label' => __('Portfolio')],
        ['route' => 'articles.index', 'match' => 'articles.*', 'label' => __('Artikel')],
        ['route' => 'jobs.index', 'match' => 'jobs.*', 'label' => __('Karier')],
        ['route' => 'about', 'match' => 'about', 'label' => __('Tentang')],
        ['route' => 'contact', 'match' => 'contact', 'label' => __('Kontak')],
    ];
    $otherLocale = app()->getLocale() === 'id' ? 'en' : 'id';
@endphp

<header class="sticky top-0 z-50 border-b border-line bg-white/95 backdrop-blur-sm supports-[backdrop-filter]:bg-white/85">
    <nav aria-label="{{ __('Navigasi Utama') }}" class="wrap flex h-16 items-center justify-between gap-6 lg:h-[4.5rem]">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="The Solution ID — {{ __('Beranda') }}">
            <img src="{{ asset(\App\Models\Setting::get('logo', 'logo.webp')) }}" alt="The Solution ID"
                 width="116" height="60" class="h-9 w-auto lg:h-10" decoding="async">
        </a>

        <ul class="hidden items-center lg:flex">
            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <li>
                    <a href="{{ route($item['route']) }}" @if($active) aria-current="page" @endif
                       class="relative block px-2.5 py-2 text-[0.9rem] font-medium transition-colors xl:px-4 xl:text-[0.95rem] {{ $active ? 'text-dark-300' : 'text-dark-100 hover:text-dark-300' }}">
                        {{ $item['label'] }}
                        @if ($active)
                            <span class="absolute inset-x-2.5 -bottom-[calc(0.9rem+1px)] h-[3px] bg-primary-500 xl:inset-x-4" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="flex items-center gap-2">
            <a href="{{ route('lang.switch', $otherLocale) }}" hreflang="{{ $otherLocale }}"
               class="inline-flex h-10 min-w-10 items-center justify-center px-2.5 text-sm font-semibold text-dark-100 transition-colors hover:bg-dark-50 hover:text-dark-300"
               aria-label="{{ $otherLocale === 'en' ? 'Switch to English' : 'Ganti ke Bahasa Indonesia' }}">
                {{ strtoupper($otherLocale) }}
            </a>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="hidden h-10 items-center px-3 text-sm font-medium text-dark-100 hover:text-dark-300 lg:inline-flex">{{ __('Dashboard') }}</a>
            @endauth

            <a href="{{ route('contact') }}" class="btn-primary hidden !min-h-11 !px-5 text-sm lg:inline-flex">{{ __('Konsultasi Gratis') }}</a>

            <button type="button" id="mobile-menu-btn" data-toggle-mobile-menu
                    class="-mr-2 inline-flex h-11 w-11 items-center justify-center text-dark-300 lg:hidden"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="{{ __('Buka menu') }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true">
                    <path data-menu-icon="open" d="M4 7h16M4 12h16M4 17h10" />
                    <path data-menu-icon="close" class="hidden" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Mobile menu: full-height sheet under the bar. --}}
    <div id="mobile-menu" class="hidden h-[calc(100dvh-4rem)] overflow-y-auto border-t border-line bg-white lg:hidden">
        <ul class="wrap divide-y divide-line py-2">
            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <li>
                    <a href="{{ route($item['route']) }}" @if($active) aria-current="page" @endif
                       class="flex items-center justify-between py-4 font-display text-2xl font-bold {{ $active ? 'text-dark-300' : 'text-dark-100' }}">
                        {{ $item['label'] }}
                        @if ($active)
                            <span class="h-3 w-3 bg-primary-500" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="wrap space-y-3 pb-10 pt-4">
            <a href="{{ route('contact') }}" class="btn-primary w-full">{{ __('Konsultasi Gratis') }}</a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary w-full">{{ __('Dashboard') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn w-full text-dark-100 hover:text-red-700">{{ __('Logout') }}</button>
                </form>
            @endauth
        </div>
    </div>
</header>
