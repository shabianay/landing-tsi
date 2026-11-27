@php
    use App\Models\Setting;

    $siteName = Setting::get('site_name', 'The Solution ID');
    $wa = Setting::get('contact_whatsapp');
    $phone = Setting::get('contact_phone');
    $email = Setting::get('contact_email');
    $socials = array_filter([
        'Instagram' => [Setting::get('social_instagram'), 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
        'Facebook' => [Setting::get('social_facebook'), 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
    ], fn ($s) => filled($s[0]));
    $links = [
        ['home', __('Beranda')],
        ['portfolio.index', __('Portfolio')],
        ['articles.index', __('Artikel')],
        ['jobs.index', __('Karier')],
        ['about', __('Tentang')],
        ['contact', __('Kontak')],
    ];
@endphp

<footer class="border-t border-line bg-dark-50" aria-label="{{ __('Footer') }}">
    <div class="wrap grid gap-10 py-12 md:grid-cols-2 lg:grid-cols-12 lg:gap-8 lg:py-16">
        {{-- Brand --}}
        <div class="lg:col-span-4">
            <a href="{{ route('home') }}" class="inline-block" aria-label="{{ $siteName }} — {{ __('Beranda') }}">
                <img src="{{ asset(Setting::get('logo', 'logo.webp')) }}" alt="{{ $siteName }}"
                     width="116" height="60" class="h-11 w-auto" loading="lazy" decoding="async">
            </a>
            <p class="mt-5 max-w-xs text-dark-100">{{ __(Setting::get('site_tagline', 'Website Profesional Untuk Bisnis Anda')) }}</p>

            @if ($socials)
                <ul class="mt-6 flex gap-2">
                    @foreach ($socials as $name => [$url, $icon])
                        <li>
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $name }}"
                               class="flex h-11 w-11 items-center justify-center border border-line bg-white text-dark-300 transition-colors hover:border-dark-300 hover:bg-dark-300 hover:text-white">
                                <svg class="h-[1.1rem] w-[1.1rem]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icon }}"/></svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Pages: two columns so the list stays short on phones --}}
        <nav class="lg:col-span-3 lg:col-start-6" aria-label="{{ __('Navigasi Footer') }}">
            <h2 class="text-sm font-semibold text-dark-300">{{ __('Navigasi') }}</h2>
            <ul class="mt-3 grid grid-cols-2 gap-x-6">
                @foreach ($links as [$route, $label])
                    <li>
                        <a href="{{ route($route) }}" @if(request()->routeIs($route)) aria-current="page" @endif
                           class="flex min-h-11 items-center text-dark-100 transition-colors hover:text-dark-300 hover:underline aria-[current=page]:font-semibold aria-[current=page]:text-dark-300">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Contact: big tappable rows --}}
        <div class="md:col-span-2 lg:col-span-4 lg:col-start-9">
            <h2 class="text-sm font-semibold text-dark-300">{{ __('Hubungi Kami') }}</h2>
            <ul class="mt-4 space-y-1.5">
                @if ($wa)
                    <li>
                        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener noreferrer"
                           class="group flex min-h-14 items-center gap-3 border border-line bg-white px-4 transition-colors hover:border-dark-300">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center bg-[#1FAF54] text-white"><x-icon-whatsapp class="h-4 w-4" /></span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-xs text-dark-100">WhatsApp</span>
                                <span class="num block truncate font-semibold text-dark-300">{{ $phone ?: $wa }}</span>
                            </span>
                            <x-icon-arrow class="h-4 w-4 shrink-0 text-dark-100 transition-transform group-hover:translate-x-0.5 group-hover:text-dark-300" />
                        </a>
                    </li>
                @endif
                @if ($email)
                    <li>
                        <a href="mailto:{{ $email }}"
                           class="group flex min-h-14 items-center gap-3 border border-line bg-white px-4 transition-colors hover:border-dark-300">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center bg-dark-300 text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v12H3zM3 6l9 7 9-7"/></svg>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-xs text-dark-100">{{ __('Email') }}</span>
                                <span class="block truncate font-semibold text-dark-300">{{ $email }}</span>
                            </span>
                            <x-icon-arrow class="h-4 w-4 shrink-0 text-dark-100 transition-transform group-hover:translate-x-0.5 group-hover:text-dark-300" />
                        </a>
                    </li>
                @endif
            </ul>
            <p class="mt-4 flex items-center gap-2.5 text-sm text-dark-100">
                <span class="h-2 w-2 shrink-0 bg-primary-500" aria-hidden="true"></span>
                {{ __('Konsultasi Gratis — Tanpa Kewajiban') }}
            </p>
        </div>
    </div>

    <div class="border-t border-line">
        <div class="wrap flex items-center justify-between gap-4 py-5 text-sm text-dark-100 lg:pr-28">
            <p>&copy; {{ date('Y') }} {{ $siteName }}</p>
            <a href="#main-content" class="inline-flex min-h-11 items-center gap-2 font-medium hover:text-dark-300">
                {{ __('Kembali ke atas') }}
                <x-icon-arrow dir="down" class="h-4 w-4 rotate-180" />
            </a>
        </div>
    </div>
</footer>
