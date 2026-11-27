@php use App\Models\Setting; @endphp

<footer class="border-t border-line bg-dark-50" aria-label="{{ __('Footer') }}">
    <div class="wrap grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-12 lg:py-20">
        <div class="lg:col-span-5">
            <a href="{{ route('home') }}" class="inline-block" aria-label="{{ Setting::get('site_name', 'The Solution ID') }} — {{ __('Beranda') }}">
                <img src="{{ asset(Setting::get('logo', 'logo.webp')) }}" alt="{{ Setting::get('site_name', 'The Solution ID') }}"
                     width="116" height="60" class="h-12 w-auto" loading="lazy" decoding="async">
            </a>
            <p class="mt-6 max-w-sm text-dark-100">
                {{ __(Setting::get('site_tagline', 'Website Profesional Untuk Bisnis Anda')) }}
            </p>
        </div>

        <nav class="lg:col-span-3 lg:col-start-7" aria-label="{{ __('Navigasi Footer') }}">
            <h2 class="text-sm font-semibold text-dark-300">{{ __('Navigasi') }}</h2>
            <ul class="mt-4 space-y-1">
                @foreach ([
                    ['home', __('Beranda')],
                    ['portfolio.index', __('Portfolio')],
                    ['articles.index', __('Artikel')],
                    ['jobs.index', __('Karier')],
                    ['about', __('Tentang')],
                    ['contact', __('Kontak')],
                ] as [$route, $label])
                    <li><a href="{{ route($route) }}" class="inline-block py-1.5 text-dark-100 hover:text-dark-300 hover:underline">{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="lg:col-span-3 lg:col-start-10">
            <h2 class="text-sm font-semibold text-dark-300">{{ __('Kontak') }}</h2>
            <ul class="mt-4 space-y-1">
                @if (Setting::get('contact_phone'))
                    <li>
                        <a href="https://wa.me/{{ Setting::get('contact_whatsapp') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2.5 py-1.5 text-dark-100 hover:text-dark-300 hover:underline">
                            <x-icon-whatsapp class="h-4 w-4 text-[#1FAF54]" />
                            <span class="num">{{ Setting::get('contact_phone') }}</span>
                        </a>
                    </li>
                @endif
                @if (Setting::get('contact_email'))
                    <li>
                        <a href="mailto:{{ Setting::get('contact_email') }}" class="inline-block break-all py-1.5 text-dark-100 hover:text-dark-300 hover:underline">{{ Setting::get('contact_email') }}</a>
                    </li>
                @endif
                @if (Setting::get('contact_website'))
                    <li>
                        <a href="{{ Setting::get('contact_website') }}" target="_blank" rel="noopener noreferrer" class="inline-block break-all py-1.5 text-dark-100 hover:text-dark-300 hover:underline">{{ preg_replace('#^https?://#', '', Setting::get('contact_website')) }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="border-t border-line">
        <div class="wrap flex flex-col gap-4 py-6 text-sm text-dark-100 md:flex-row md:items-center md:justify-between">
            <p>&copy; {{ date('Y') }} {{ Setting::get('site_name', 'The Solution ID') }}. {{ __('All rights reserved.') }}</p>
            <ul class="flex flex-wrap gap-x-6 gap-y-2">
                @if (Setting::get('social_instagram'))
                    <li><a href="{{ Setting::get('social_instagram') }}" target="_blank" rel="noopener noreferrer" class="hover:text-dark-300 hover:underline">Instagram</a></li>
                @endif
                @if (Setting::get('social_facebook'))
                    <li><a href="{{ Setting::get('social_facebook') }}" target="_blank" rel="noopener noreferrer" class="hover:text-dark-300 hover:underline">Facebook</a></li>
                @endif
                <li><a href="https://wa.me/{{ Setting::get('contact_whatsapp') }}" target="_blank" rel="noopener noreferrer" class="hover:text-dark-300 hover:underline">WhatsApp</a></li>
            </ul>
        </div>
    </div>
</footer>
