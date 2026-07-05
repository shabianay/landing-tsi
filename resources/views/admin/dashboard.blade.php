@extends('layouts.admin')

@section('title', __('Dashboard — Admin TSI'))

@section('content')
<div class="mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-dark-300 tracking-tight">{{ __('Dashboard') }}</h1>
    <p class="text-dark-100/50 mt-1 font-medium">{{ __('Overview data TSI') }}</p>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-5 mb-8">
    <a href="{{ route('admin.clients.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-primary-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-lg group-hover:bg-primary-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $clientCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Klien') }}</span>
    </a>
    <a href="{{ route('admin.projects.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-emerald-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg group-hover:bg-emerald-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $projectCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Proyek') }}</span>
    </a>
    <a href="{{ route('admin.articles.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-sky-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <span class="text-xs font-semibold text-sky-600 bg-sky-50 px-2.5 py-1 rounded-lg group-hover:bg-sky-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $articleCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Artikel') }}</span>
    </a>
    <a href="{{ route('admin.services.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-indigo-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg group-hover:bg-indigo-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $serviceCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Layanan') }}</span>
    </a>
    <a href="{{ route('admin.process-steps.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-cyan-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-cyan-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-xs font-semibold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-lg group-hover:bg-cyan-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $stepCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Proses') }}</span>
    </a>
    <a href="{{ route('admin.partners.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-orange-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-lg group-hover:bg-orange-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $partnerCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Partner') }}</span>
    </a>
    <a href="{{ route('admin.testimonials.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-violet-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
            </div>
            <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-2.5 py-1 rounded-lg group-hover:bg-violet-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $testimonialCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Testimonial') }}</span>
    </a>
    <a href="{{ route('admin.popups.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-amber-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg group-hover:bg-amber-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $popupCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Popup') }}</span>
    </a>
    <a href="{{ route('admin.faqs.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-teal-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-lg group-hover:bg-teal-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $faqCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('FAQ') }}</span>
    </a>
    <a href="{{ route('admin.contacts.index') }}" class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5 hover:shadow-md hover:border-rose-500/20 transition-all duration-200 group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg group-hover:bg-rose-100 transition-colors">{{ __('Lihat') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ $contactCount }}</p>
        <span class="text-sm font-medium text-dark-100/60">{{ __('Pesan') }}</span>
    </a>
</div>

<div class="grid sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <span class="text-xs font-semibold text-dark-100/50">{{ __('Pengunjung Hari Ini') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ number_format($viewsToday) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <span class="text-xs font-semibold text-dark-100/50">{{ __('Minggu Ini') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ number_format($viewsWeek) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-xs font-semibold text-dark-100/50">{{ __('Bulan Ini') }}</span>
        </div>
        <p class="text-2xl font-bold text-dark-300 tracking-tight">{{ number_format($viewsMonth) }}</p>
    </div>
</div>

@if($topPages->count() > 0)
<div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm mb-8">
    <div class="px-6 py-5 border-b border-dark-100/5">
        <h2 class="text-lg font-bold text-dark-300 tracking-tight">{{ __('Halaman Terpopuler') }}</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-dark-50/50 border-b border-dark-100/5">
                    <th class="text-left font-semibold text-dark-300 px-6 py-3 w-10">#</th>
                    <th class="text-left font-semibold text-dark-300 px-6 py-3">{{ __('URL') }}</th>
                    <th class="text-right font-semibold text-dark-300 px-6 py-3">{{ __('Views') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/5">
                @foreach($topPages as $i => $page)
                <tr class="hover:bg-dark-50/30 transition-colors">
                    <td class="px-6 py-3 text-dark-100/40 font-medium">{{ $i + 1 }}</td>
                    <td class="px-6 py-3 text-dark-300 font-medium">{{ $page->url }}</td>
                    <td class="px-6 py-3 text-right text-dark-300 font-bold">{{ number_format($page->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm">
        <div class="px-6 py-5 border-b border-dark-100/5 flex items-center justify-between">
            <h2 class="text-lg font-bold text-dark-300 tracking-tight">{{ __('Pesan Terbaru') }}</h2>
            <a href="{{ route('admin.contacts.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors">{{ __('Lihat Semua') }} &rarr;</a>
        </div>
        @if($latestContacts->count() > 0)
            <div class="divide-y divide-dark-100/5">
                @foreach($latestContacts as $contact)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-dark-50/30 transition-colors">
                        <div class="w-9 h-9 bg-primary-50 rounded-xl flex items-center justify-center shrink-0">
                            <span class="text-primary-600 font-bold text-sm">{{ strtoupper(substr($contact->name, 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-dark-300 text-sm truncate">{{ $contact->name }}</p>
                            <p class="text-xs text-dark-100/50 truncate">{{ $contact->subject ?? $contact->email }}</p>
                        </div>
                        <span class="text-xs text-dark-100/40 shrink-0">{{ $contact->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-dark-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-dark-100/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <p class="text-dark-100/60 font-medium text-sm">{{ __('Belum ada pesan masuk.') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm">
        <div class="px-6 py-5 border-b border-dark-100/5 flex items-center justify-between">
            <h2 class="text-lg font-bold text-dark-300 tracking-tight">{{ __('Artikel Terbaru') }}</h2>
            <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors">{{ __('Lihat Semua') }} &rarr;</a>
        </div>
        @if($latestArticles->count() > 0)
            <div class="divide-y divide-dark-100/5">
                @foreach($latestArticles as $article)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-dark-50/30 transition-colors">
                        <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center shrink-0">
                            <span class="text-sky-600 font-bold text-sm">{{ strtoupper(substr($article->title, 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-dark-300 text-sm truncate">{{ $article->title }}</p>
                            <p class="text-xs text-dark-100/50 truncate">{{ $article->trans('category') }} &middot; {{ $article->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs {{ $article->is_published ? 'text-emerald-600 bg-emerald-50' : 'text-amber-600 bg-amber-50' }} font-semibold px-2.5 py-1 rounded-lg shrink-0">{{ $article->is_published ? __('Terbit') : __('Draft') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-dark-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-dark-100/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <p class="text-dark-100/60 font-medium text-sm">{{ __('Belum ada artikel.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection