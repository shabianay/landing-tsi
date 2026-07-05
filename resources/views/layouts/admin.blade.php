<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    @php use App\Models\Setting; @endphp
    <link rel="icon" type="image/webp" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">
    <link rel="apple-touch-icon" href="{{ asset(Setting::get('logo', 'logo.webp')) }}">
    <title>@yield('title', 'Admin — TSI')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-dark-50/50" x-data="{ pageLoading: true }" x-init="setTimeout(() => pageLoading = false, 350)">
    {{-- Page Loading Spinner --}}
    <div x-show="pageLoading" x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-white page-loader"
         style="transition: opacity 0.4s ease-out">
        <div class="flex flex-col items-center gap-4">
            <div class="w-10 h-10 border-[3px] border-primary-100 border-t-primary-500 rounded-full" style="animation: spin-smooth 0.8s linear infinite"></div>
            <span class="text-sm font-semibold text-dark-100/50">{{ __('Memuat...') }}</span>
        </div>
    </div>
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed lg:sticky top-0 left-0 z-40 w-64 h-screen bg-white border-r border-dark-100/5 shadow-sm shrink-0 hidden lg:flex flex-col">
            <div class="p-6 border-b border-dark-100/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-md shadow-primary-500/30">
                        <span class="text-white font-bold text-[10px]">TSI</span>
                    </div>
                    <div>
                        <span class="font-bold text-dark-300 text-lg tracking-tight block leading-none">TSI</span>
                        <span class="text-[11px] text-dark-100/40 font-medium tracking-wider uppercase">{{ __('Admin Panel') }}</span>
                    </div>
                </a>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto" aria-label="{{ __('Admin Navigation') }}">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('admin.clients.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.clients.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Klien') }}
                </a>
                <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    {{ __('Proyek') }}
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    {{ __('Testimonial') }}
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.faqs.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    FAQ
                </a>
                <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.articles.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Artikel
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.services.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('Layanan') }}
                </a>
                <a href="{{ route('admin.partners.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.partners.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Partner') }}
                </a>
                <a href="{{ route('admin.popups.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.popups.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Popup
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.contacts.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Pesan
                    @php $unreadCount = \App\Models\Contact::where('is_read', false)->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold text-white bg-primary-500 rounded-full">{{ min($unreadCount, 99) }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('Pengaturan') }}
                </a>
            </nav>
            <div class="p-4 border-t border-dark-100/5">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-dark-100/40 hover:text-primary-600 hover:bg-dark-50/80 transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Kembali ke Website') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-dark-100/40 hover:text-red-500 hover:bg-red-50/80 transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile header --}}
        <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-dark-100/5 shadow-sm">
            <div class="flex items-center justify-between px-4 h-14">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-lg flex items-center justify-center shadow-md shadow-primary-500/30">
                        <span class="text-white font-bold text-[10px]">TSI</span>
                    </div>
                    <span class="font-bold text-dark-300">TSI</span>
                </a>
                <button id="admin-mobile-toggle" class="p-2 rounded-lg hover:bg-dark-50/80 transition-colors" onclick="toggleAdminSidebar()">
                    <svg class="w-5 h-5 text-dark-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile sidebar overlay --}}
        <div id="admin-sidebar-overlay" class="lg:hidden fixed inset-0 z-30 bg-dark-300/50 hidden" onclick="toggleAdminSidebar()"></div>

        {{-- Mobile sidebar --}}
        <aside id="admin-sidebar" class="lg:hidden fixed top-0 left-0 z-40 w-64 h-full bg-white shadow-2xl border-r border-dark-100/5 hidden flex-col">
            <div class="flex items-center justify-between p-4 border-b border-dark-100/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-lg flex items-center justify-center shadow-md shadow-primary-500/30">
                        <span class="text-white font-bold text-[10px]">TSI</span>
                    </div>
                    <div>
                        <span class="font-bold text-dark-300 text-base leading-none block">TSI</span>
                        <span class="text-[10px] text-dark-100/40 font-medium tracking-wider uppercase">Admin Panel</span>
                    </div>
                </a>
                <button onclick="toggleAdminSidebar()" class="p-1.5 rounded-lg hover:bg-dark-50/80 transition-colors">
                    <svg class="w-5 h-5 text-dark-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto" aria-label="{{ __('Admin Navigation') }}">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('admin.clients.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.clients.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Klien') }}
                </a>
                <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    {{ __('Proyek') }}
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    {{ __('Testimonial') }}
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.faqs.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    FAQ
                </a>
                <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.articles.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Artikel
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.services.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('Layanan') }}
                </a>
                <a href="{{ route('admin.partners.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.partners.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Partner') }}
                </a>
                <a href="{{ route('admin.popups.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.popups.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Popup
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.contacts.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Pesan
                    @php $unreadCount = \App\Models\Contact::where('is_read', false)->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold text-white bg-primary-500 rounded-full">{{ min($unreadCount, 99) }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-primary-50 text-primary-600' : 'text-dark-100/50 hover:bg-dark-50/80 hover:text-dark-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('Pengaturan') }}
                </a>
            </nav>
            <div class="p-4 border-t border-dark-100/5">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-dark-100/40 hover:text-primary-600 hover:bg-dark-50/80 transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Kembali ke Website') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-dark-100/40 hover:text-red-500 hover:bg-red-50/80 transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 lg:pl-0">
            <div class="lg:hidden h-14"></div>
            <div class="p-4 md:p-8 max-w-8xl mx-auto">
                @yield('content')
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script>
        function toggleAdminSidebar() {
            document.getElementById('admin-sidebar').classList.toggle('hidden');
            document.getElementById('admin-sidebar-overlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>