@php
    $unreadCount = \App\Models\Contact::where('is_read', false)->count();
    // [route, active pattern, label, icon path, badge]
    $groups = [
        __('Ringkasan') => [
            ['admin.dashboard', 'admin.dashboard', __('Dashboard'), 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['admin.contacts.index', 'admin.contacts.*', __('Pesan'), 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', $unreadCount],
        ],
        __('Proyek & Keuangan') => [
            ['admin.orders.index', 'admin.orders.*', __('Proyek Klien'), 'M4 5h4v14H4zM10 5h4v9h-4zM16 5h4v6h-4z'],
            ['admin.order-clients.index', 'admin.order-clients.*', __('Data Klien'), 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['admin.invoices.index', 'admin.invoices.*', __('Invoice'), 'M7 3h8l4 4v14H7zM15 3v4h4M10 12h6M10 16h6'],
            ['admin.financial.index', 'admin.financial.*', __('Laporan Keuangan'), 'M4 20V10M10 20V4M16 20v-7M22 20H2'],
        ],
        __('Sales') => [
            ['admin.applicants.index', 'admin.applicants.*', __('Pelamar Sales'), 'M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m16 0v-2a4 4 0 00-3-3.87M14 3.13a4 4 0 010 7.75M14 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['admin.partner-sales.index', 'admin.partner-sales.*', __('Klien Partner & Komisi'), 'M3 7h18M5 7l1.5 13h11L19 7M9 7V5a3 3 0 016 0v2m-5 5h4'],
        ],
        __('Konten') => [
            ['admin.projects.index', 'admin.projects.*', __('Portofolio'), 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['admin.clients.index', 'admin.clients.*', __('Klien Portofolio'), 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['admin.articles.index', 'admin.articles.*', __('Artikel'), 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
            ['admin.services.index', 'admin.services.*', __('Layanan'), 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['admin.process-steps.index', 'admin.process-steps.*', __('Proses'), 'M4 6h16M4 12h10M4 18h6'],
            ['admin.testimonials.index', 'admin.testimonials.*', __('Testimonial'), 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z'],
            ['admin.partners.index', 'admin.partners.*', __('Partner'), 'M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z'],
            ['admin.faqs.index', 'admin.faqs.*', 'FAQ', 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['admin.popups.index', 'admin.popups.*', 'Popup', 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
        ],
        __('Sistem') => [
            ['admin.settings.index', 'admin.settings.*', __('Pengaturan'), 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['admin.activity-logs.index', 'admin.activity-logs.*', __('Log Aktivitas'), 'M12 8v4l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['admin.account.edit', 'admin.account.*', __('Akun Saya'), 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ],
    ];
@endphp

<form method="GET" action="{{ route('admin.search') }}" role="search" class="px-3 pt-4">
    <label class="sr-only" for="admin-search-{{ $searchId ?? 'nav' }}">{{ __('Cari') }}</label>
    <input id="admin-search-{{ $searchId ?? 'nav' }}" type="search" name="q" value="{{ request()->routeIs('admin.search') ? request('q') : '' }}" placeholder="{{ __('Cari proyek, invoice, klien') }}"
           class="w-full border-white/15 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-white/50 focus:border-primary-500 focus:ring-primary-500/40">
</form>

<nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="{{ __('Admin Navigation') }}">
    @foreach ($groups as $groupLabel => $items)
        <p class="px-3 pb-1.5 text-xs font-semibold text-white/60 {{ $loop->first ? '' : 'pt-5' }}">{{ $groupLabel }}</p>
        <ul class="space-y-0.5">
            @foreach ($items as $item)
                @php $active = request()->routeIs($item[1]); @endphp
                <li>
                    <a href="{{ route($item[0]) }}" @if($active) aria-current="page" @endif
                       class="relative flex min-h-10 items-center gap-3 px-3 py-2 text-sm transition-colors {{ $active ? 'bg-white/10 font-semibold text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                        @if ($active)
                            <span class="absolute inset-y-1.5 left-0 w-1 bg-primary-500" aria-hidden="true"></span>
                        @endif
                        <svg class="h-[1.15rem] w-[1.15rem] shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $item[3] }}"/></svg>
                        <span class="flex-1 truncate">{{ $item[2] }}</span>
                        @if (! empty($item[4]))
                            <span class="num min-w-6 bg-primary-500 px-1.5 text-center text-xs font-bold leading-5 text-dark-300">{{ min($item[4], 99) }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</nav>

<div class="border-t border-white/10 px-3 py-3">
    <a href="{{ route('home') }}" class="flex min-h-10 items-center gap-3 px-3 py-2 text-sm text-white/80 hover:bg-white/5 hover:text-white">
        <x-icon-arrow dir="out" class="h-[1.15rem] w-[1.15rem]" />
        {{ __('Kembali ke Website') }}
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex min-h-10 w-full items-center gap-3 px-3 py-2 text-left text-sm text-white/80 hover:bg-white/5 hover:text-white">
            <svg class="h-[1.15rem] w-[1.15rem]" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Logout
        </button>
    </form>
</div>
