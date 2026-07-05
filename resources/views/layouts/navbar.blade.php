<nav aria-label="{{ __('Navigasi Utama') }}"
    class="fixed top-0 left-0 right-0 z-50 bg-white/70 backdrop-blur-xl border-b border-white/20 shadow-lg shadow-dark-300/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="The Solution ID - {{ __('Beranda') }}">
                <img src="{{ asset(\App\Models\Setting::get('logo', 'logo.webp')) }}" alt="The Solution ID"
                     width="116" height="60"
                     class="h-8 w-auto group-hover:opacity-80 transition-opacity duration-300"
                     decoding="async">
            </a>

            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'text-primary-600 bg-primary-50/80' : 'text-dark-100/60 hover:text-dark-300 hover:bg-dark-50/50' }}">{{ __('Beranda') }}</a>
                <a href="{{ route('portfolio.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('portfolio.*') ? 'text-primary-600 bg-primary-50/80' : 'text-dark-100/60 hover:text-dark-300 hover:bg-dark-50/50' }}">{{ __('Portfolio') }}</a>
                <a href="{{ route('articles.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('articles.*') ? 'text-primary-600 bg-primary-50/80' : 'text-dark-100/60 hover:text-dark-300 hover:bg-dark-50/50' }}">{{ __('Artikel') }}</a>
                <a href="{{ route('about') }}"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('about') ? 'text-primary-600 bg-primary-50/80' : 'text-dark-100/60 hover:text-dark-300 hover:bg-dark-50/50' }}">{{ __('Tentang') }}</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('lang.switch', app()->getLocale() === 'id' ? 'en' : 'id') }}"
                    class="flex items-center gap-1.5 text-xs font-bold text-dark-600/40 hover:text-primary-600 transition-colors border border-dark-900/30 hover:border-primary-500/30 rounded-lg px-3 py-1.5 bg-white/50 hover:bg-white">
                    @if (app()->getLocale() === 'id')
                        <svg class="w-5 h-3.5 rounded overflow-hidden shrink-0" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
                            <rect width="60" height="30" fill="#012169"/>
                            <line x1="0" y1="0" x2="60" y2="30" stroke="#fff" stroke-width="5"/>
                            <line x1="60" y1="0" x2="0" y2="30" stroke="#fff" stroke-width="5"/>
                            <line x1="0" y1="0" x2="60" y2="30" stroke="#C8102E" stroke-width="2"/>
                            <line x1="60" y1="0" x2="0" y2="30" stroke="#C8102E" stroke-width="2"/>
                            <rect x="26" width="8" height="30" fill="#fff"/>
                            <rect y="12" width="60" height="6" fill="#fff"/>
                            <rect x="28" width="4" height="30" fill="#C8102E"/>
                            <rect y="13" width="60" height="4" fill="#C8102E"/>
                        </svg>
                        <span>{{ __('EN') }}</span>
                    @else
                        <svg class="w-5 h-3.5 rounded overflow-hidden shrink-0" viewBox="0 0 24 16">
                            <rect width="24" height="8" fill="#CE1126" />
                            <rect y="8" width="24" height="8" fill="#fff" />
                        </svg>
                        <span>{{ __('ID') }}</span>
                    @endif
                </a>

                <a href="{{ route('contact') }}"
                    class="hidden lg:inline-flex btn-primary text-xs !px-5 !py-2.5">{{ __('Konsultasi Gratis') }}</a>

                <button id="mobile-menu-btn" class="lg:hidden p-2.5 rounded-xl hover:bg-dark-50/80 transition-colors"
                    onclick="toggleMobileMenu()">
                    <svg class="w-5 h-5 text-dark-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="hidden lg:inline-flex text-xs font-medium text-dark-100/50 hover:text-primary-600 transition-colors px-3 py-2">{{ __('Dashboard') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden lg:inline">
                        @csrf
                        <button type="submit"
                            class="text-xs font-medium text-dark-100/50 hover:text-red-500 transition-colors px-3 py-2">{{ __('Logout') }}</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden bg-white/95 backdrop-blur-xl border-t border-dark-100/5 shadow-2xl">
        <div class="px-4 py-6 space-y-1">
            <a href="{{ route('home') }}"
                class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/60 hover:bg-dark-50/50' }}">{{ __('Beranda') }}</a>
            <a href="{{ route('portfolio.index') }}"
                class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('portfolio.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/60 hover:bg-dark-50/50' }}">{{ __('Portfolio') }}</a>
            <a href="{{ route('articles.index') }}"
                class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('articles.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/60 hover:bg-dark-50/50' }}">{{ __('Artikel') }}</a>
            <a href="{{ route('about') }}"
                class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('about') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/60 hover:bg-dark-50/50' }}">{{ __('Tentang') }}</a>
            <hr class="border-dark-100/5 my-4">
            <a href="{{ route('contact') }}"
                class="btn-primary w-full text-center py-3.5">{{ __('Konsultasi Gratis') }}</a>
            <a href="{{ route('lang.switch', app()->getLocale() === 'id' ? 'en' : 'id') }}"
                class="flex items-center justify-center gap-2 py-3 text-sm font-bold text-dark-100/40 border border-dark-100/10 rounded-xl hover:bg-dark-50/50 transition-colors">
                @if (app()->getLocale() === 'id')
                    <svg class="w-5 h-3.5 rounded overflow-hidden shrink-0" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
                        <rect width="60" height="30" fill="#012169"/>
                        <line x1="0" y1="0" x2="60" y2="30" stroke="#fff" stroke-width="5"/>
                        <line x1="60" y1="0" x2="0" y2="30" stroke="#fff" stroke-width="5"/>
                        <line x1="0" y1="0" x2="60" y2="30" stroke="#C8102E" stroke-width="2"/>
                        <line x1="60" y1="0" x2="0" y2="30" stroke="#C8102E" stroke-width="2"/>
                        <rect x="26" width="8" height="30" fill="#fff"/>
                        <rect y="12" width="60" height="6" fill="#fff"/>
                        <rect x="28" width="4" height="30" fill="#C8102E"/>
                        <rect y="13" width="60" height="4" fill="#C8102E"/>
                    </svg>
                    <span>{{ __('English') }}</span>
                @else
                    <svg class="w-5 h-3.5 rounded overflow-hidden shrink-0" viewBox="0 0 24 16">
                        <rect width="24" height="8" fill="#CE1126" />
                        <rect y="8" width="24" height="8" fill="#fff" />
                    </svg>
                    <span>{{ __('Indonesia') }}</span>
                @endif
            </a>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
