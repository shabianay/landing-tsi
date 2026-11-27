@extends('layouts.admin')

@section('title', __('Dashboard — Admin TSI'))

@php $unread = \App\Models\Contact::where('is_read', false)->count(); @endphp

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="font-display text-3xl font-extrabold tracking-tight text-dark-300">{{ __('Dashboard') }}</h1>
        <p class="mt-1 text-dark-100">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 underline decoration-primary-300 hover:text-dark-300">
        {{ __('Kembali ke Website') }} <x-icon-arrow dir="out" class="h-3.5 w-3.5" />
    </a>
</div>

{{-- What needs attention --}}
<div class="mb-8 grid gap-1.5 md:grid-cols-2">
    <a href="{{ route('admin.contacts.index') }}" class="group flex items-center justify-between gap-6 p-6 transition-colors {{ $unread > 0 ? 'bg-primary-500 text-dark-300 hover:bg-primary-400' : 'border border-line bg-white text-dark-300 hover:border-dark-300' }}">
        <div>
            <p class="text-sm font-semibold">{{ __('Pesan belum dibaca') }}</p>
            <p class="num mt-2 font-display text-5xl font-extrabold leading-none">{{ $unread }}</p>
            <p class="mt-3 text-sm {{ $unread > 0 ? 'text-dark-300/85' : 'text-dark-100' }}">{{ $contactCount }} {{ __('total pesan') }}</p>
        </div>
        <x-icon-arrow class="h-6 w-6 shrink-0 transition-transform group-hover:translate-x-1" />
    </a>
    <a href="{{ route('admin.applicants.index') }}" class="group flex items-center justify-between gap-6 bg-dark-300 p-6 text-white transition-colors hover:bg-dark-200">
        <div>
            <p class="text-sm font-semibold text-white/85">{{ __('Pelamar baru (7 hari)') }}</p>
            <p class="num mt-2 font-display text-5xl font-extrabold leading-none">{{ $newApplicantCount }}</p>
            <p class="mt-3 text-sm text-white/75">{{ $applicantCount }} {{ __('total pelamar') }}</p>
        </div>
        <x-icon-arrow class="h-6 w-6 shrink-0 transition-transform group-hover:translate-x-1" />
    </a>
</div>

{{-- Client projects & money --}}
@php
    $monthLabels = ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'];
    $maxPaid = max(1, $paidByMonth->max());
@endphp
<section class="mb-8 grid gap-px border border-line bg-line xl:grid-cols-12">
    <div class="bg-white xl:col-span-5">
        <div class="flex items-center justify-between border-b border-line px-5 py-4">
            <h2 class="font-bold text-dark-300">Proyek klien</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-primary-700 hover:underline">Buka papan</a>
        </div>
        <dl class="grid grid-cols-4 gap-px bg-line">
            @foreach (\App\Models\Order::STATUS_LABELS as $status => $label)
                <div class="flex flex-col-reverse bg-white px-4 py-3">
                    <dt class="text-xs text-dark-100">{{ $label }}</dt>
                    <dd class="num font-display text-2xl font-extrabold {{ $status === 'Ongoing' ? 'text-primary-700' : 'text-dark-300' }}">{{ $orderCounts[$status] ?? 0 }}</dd>
                </div>
            @endforeach
        </dl>
        <h3 class="border-y border-line px-5 py-2.5 text-sm font-semibold text-dark-300">Deadline 7 hari ke depan</h3>
        <ul class="divide-y divide-line">
            @forelse ($dueOrders as $order)
                <li>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="flex items-center justify-between gap-3 px-5 py-2.5 text-sm hover:bg-dark-50">
                        <span class="min-w-0 truncate"><span class="font-semibold text-dark-300">{{ $order->client_name }}</span> <span class="text-dark-100">· {{ $order->task_type }}</span></span>
                        <span class="shrink-0 {{ $order->isOverdue() ? 'font-bold text-red-700' : 'text-dark-100' }}">{{ $order->isOverdue() ? 'Lewat ' : '' }}{{ $order->deadline->translatedFormat('d M') }}</span>
                    </a>
                </li>
            @empty
                <li class="px-5 py-5 text-sm text-dark-100">Tidak ada deadline dekat.</li>
            @endforelse
        </ul>
    </div>

    <div class="bg-white xl:col-span-7">
        <div class="flex items-center justify-between border-b border-line px-5 py-4">
            <h2 class="font-bold text-dark-300">Keuangan {{ now()->year }}</h2>
            <a href="{{ route('admin.financial.index') }}" class="text-sm font-semibold text-primary-700 hover:underline">Laporan lengkap</a>
        </div>
        <dl class="grid grid-cols-3 gap-px bg-line">
            <div class="flex flex-col-reverse bg-white px-5 py-3"><dt class="text-xs text-dark-100">Ditagihkan</dt><dd class="num font-display text-xl font-extrabold text-dark-300">@rp($money['billed'])</dd></div>
            <div class="flex flex-col-reverse bg-white px-5 py-3"><dt class="text-xs text-dark-100">Uang masuk</dt><dd class="num font-display text-xl font-extrabold text-emerald-700">@rp($money['paid'])</dd></div>
            <div class="flex flex-col-reverse bg-white px-5 py-3"><dt class="text-xs text-dark-100">Belum dibayar (tanpa DP)</dt><dd class="num font-display text-xl font-extrabold text-red-700">@rp($money['outstanding'])</dd></div>
        </dl>
        <div class="border-t border-line px-5 pb-4 pt-5">
            <p class="mb-3 text-xs text-dark-100">Uang masuk per bulan</p>
            <ol class="flex h-32 items-end gap-1.5">
                @foreach ($paidByMonth as $i => $amount)
                    <li class="flex h-full flex-1 flex-col items-center justify-end gap-1">
                        <span class="w-full {{ $i + 1 === now()->month ? 'bg-primary-500' : 'bg-dark-100/60' }}" style="height: {{ max($amount > 0 ? 4 : 1, round($amount / $maxPaid * 100)) }}%" title="@rp($amount)"></span>
                        <span class="text-[0.65rem] text-dark-100" aria-hidden="true">{{ $monthLabels[$i] }}</span>
                        <span class="sr-only">{{ \Illuminate\Support\Carbon::create(null, $i + 1, 1)->locale('id')->translatedFormat('F') }}: @rp($amount)</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

{{-- Recruitment pipeline --}}
<section class="mb-8 border border-line bg-white">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-5 py-4">
        <h2 class="font-bold text-dark-300">{{ __('Rekrutmen sales') }}</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.applicants.index') }}" class="inline-flex min-h-10 items-center bg-dark-300 px-4 text-sm font-semibold text-white hover:bg-dark-200">{{ __('Kelola pelamar') }}</a>
            <a href="{{ route('admin.partner-sales.index') }}" class="inline-flex min-h-10 items-center border border-dark-300/70 px-4 text-sm font-semibold text-dark-300 hover:bg-dark-50">{{ __('Klien Partner & Komisi') }}</a>
        </div>
    </div>
    <ul class="grid grid-cols-2 gap-px bg-line sm:grid-cols-4 xl:grid-cols-8">
        @foreach (\App\Models\Applicant::STATUSES as $status => $label)
            <li class="bg-white px-5 py-4">
                <a href="{{ route('admin.applicants.index', ['status' => $status]) }}" class="block hover:underline">
                    <span class="num block font-display text-2xl font-extrabold text-dark-300">{{ $applicantStatusCounts[$status] ?? 0 }}</span>
                    <span class="mt-0.5 block text-sm text-dark-100">{{ $label }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</section>

<div class="mb-8 grid gap-8 xl:grid-cols-12">
    {{-- Traffic --}}
    <section class="border border-line bg-white xl:col-span-5">
        <h2 class="border-b border-line px-5 py-4 font-bold text-dark-300">{{ __('Pengunjung') }}</h2>
        <dl class="grid grid-cols-3 gap-px bg-line">
            @foreach ([[__('Pengunjung Hari Ini'), $viewsToday], [__('Minggu Ini'), $viewsWeek], [__('Bulan Ini'), $viewsMonth]] as [$label, $value])
                <div class="flex flex-col-reverse bg-white px-5 py-4">
                    <dt class="text-sm text-dark-100">{{ $label }}</dt>
                    <dd class="num font-display text-2xl font-extrabold text-dark-300">{{ number_format($value) }}</dd>
                </div>
            @endforeach
        </dl>
        @if ($topPages->count() > 0)
            @php $maxViews = max(1, $topPages->max('total')); @endphp
            <h3 class="border-y border-line px-5 py-3 text-sm font-semibold text-dark-300">{{ __('Halaman Terpopuler') }}</h3>
            <ol class="divide-y divide-line">
                @foreach ($topPages as $page)
                    <li class="relative px-5 py-3 text-sm">
                        <span class="absolute inset-y-0 left-0 bg-primary-50" style="width: {{ round($page->total / $maxViews * 100) }}%" aria-hidden="true"></span>
                        <span class="relative flex items-center justify-between gap-4">
                            <span class="min-w-0 truncate text-dark-200">{{ preg_replace('#^https?://[^/]+#', '', $page->url) ?: '/' }}</span>
                            <span class="num shrink-0 font-semibold text-dark-300">{{ number_format($page->total) }}</span>
                        </span>
                    </li>
                @endforeach
            </ol>
        @endif
    </section>

    {{-- Latest activity --}}
    <div class="grid gap-8 md:grid-cols-2 xl:col-span-7">
        <section class="border border-line bg-white">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h2 class="font-bold text-dark-300">{{ __('Pesan Terbaru') }}</h2>
                <a href="{{ route('admin.contacts.index') }}" class="text-sm font-semibold text-primary-700 hover:underline">{{ __('Lihat Semua') }}</a>
            </div>
            @if ($latestContacts->count() > 0)
                <ul class="divide-y divide-line">
                    @foreach ($latestContacts as $contact)
                        <li>
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="flex items-start gap-3 px-5 py-3.5 hover:bg-dark-50">
                                <span class="mt-1.5 h-2 w-2 shrink-0 {{ $contact->is_read ? 'bg-transparent' : 'bg-primary-500' }}" aria-hidden="true"></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm {{ $contact->is_read ? 'text-dark-200' : 'font-semibold text-dark-300' }}">{{ $contact->name }}</span>
                                    <span class="block truncate text-sm text-dark-100">{{ $contact->subject ?: $contact->email }}</span>
                                </span>
                                <span class="shrink-0 text-xs text-dark-100">{{ $contact->created_at->diffForHumans(null, true) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="px-5 py-8 text-sm text-dark-100">{{ __('Belum ada pesan masuk.') }}</p>
            @endif
        </section>

        <section class="border border-line bg-white">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h2 class="font-bold text-dark-300">{{ __('Artikel Terbaru') }}</h2>
                <a href="{{ route('admin.articles.index') }}" class="text-sm font-semibold text-primary-700 hover:underline">{{ __('Lihat Semua') }}</a>
            </div>
            @if ($latestArticles->count() > 0)
                <ul class="divide-y divide-line">
                    @foreach ($latestArticles as $article)
                        <li>
                            <a href="{{ route('admin.articles.edit', $article) }}" class="flex items-start gap-3 px-5 py-3.5 hover:bg-dark-50">
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-medium text-dark-300">{{ $article->title }}</span>
                                    <span class="block truncate text-sm text-dark-100">{{ $article->trans('category') }} · {{ $article->created_at->diffForHumans() }}</span>
                                </span>
                                <span class="shrink-0 px-2 py-0.5 text-xs font-semibold {{ $article->is_published ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">{{ $article->is_published ? __('Terbit') : __('Draft') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="px-5 py-8 text-sm text-dark-100">{{ __('Belum ada artikel.') }}</p>
            @endif
        </section>
    </div>
</div>

{{-- Content inventory --}}
<section class="border border-line bg-white">
    <h2 class="border-b border-line px-5 py-4 font-bold text-dark-300">{{ __('Konten website') }}</h2>
    <ul class="grid grid-cols-2 gap-px bg-line sm:grid-cols-3 lg:grid-cols-5">
        @foreach ([
            ['admin.projects.index', __('Portofolio'), $projectCount],
            ['admin.articles.index', __('Artikel'), $articleCount],
            ['admin.clients.index', __('Klien Portofolio'), $clientCount],
            ['admin.services.index', __('Layanan'), $serviceCount],
            ['admin.process-steps.index', __('Proses'), $stepCount],
            ['admin.testimonials.index', __('Testimonial'), $testimonialCount],
            ['admin.partners.index', __('Partner'), $partnerCount],
            ['admin.faqs.index', __('FAQ'), $faqCount],
            ['admin.popups.index', __('Popup'), $popupCount],
            ['admin.settings.index', __('Pengaturan'), null],
        ] as [$route, $label, $count])
            <li class="bg-white">
                <a href="{{ route($route) }}" class="group flex items-center justify-between gap-3 px-5 py-4 hover:bg-dark-50">
                    <span>
                        <span class="block text-sm text-dark-100">{{ $label }}</span>
                        <span class="num block font-display text-2xl font-extrabold text-dark-300">{{ $count ?? '—' }}</span>
                    </span>
                    <x-icon-arrow class="h-4 w-4 text-dark-100 transition-transform group-hover:translate-x-0.5 group-hover:text-dark-300" />
                </a>
            </li>
        @endforeach
    </ul>
</section>
@endsection
