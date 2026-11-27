@extends('layouts.admin')

@section('title', 'Proyek Klien — Admin TSI')

@php
    use App\Models\Order;
    $tone = [
        'Pending' => 'border-t-dark-100/40',
        'Ongoing' => 'border-t-primary-500',
        'Completed' => 'border-t-emerald-600',
        'Cancelled' => 'border-t-red-400',
    ];
@endphp

@section('content')
@include('admin.partials.pm-head', [
    'title' => 'Proyek Klien',
    'subtitle' => 'Pesanan dari klien, dari menunggu sampai selesai.',
    'actions' => '<a href="' . route('admin.orders.create') . '" class="btn-primary !min-h-11 !px-5 text-sm">+ Proyek baru</a>',
])

<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div class="inline-flex border border-line bg-white p-1" role="tablist" aria-label="Tampilan">
        <a href="{{ route('admin.orders.index', array_filter(['q' => $filters['q'] ?? null])) }}" role="tab" aria-selected="{{ $view === 'board' ? 'true' : 'false' }}"
           class="px-4 py-2 text-sm font-semibold {{ $view === 'board' ? 'bg-dark-300 text-white' : 'text-dark-100 hover:text-dark-300' }}">Papan</a>
        <a href="{{ route('admin.orders.index', array_filter(['view' => 'list', 'q' => $filters['q'] ?? null])) }}" role="tab" aria-selected="{{ $view === 'list' ? 'true' : 'false' }}"
           class="px-4 py-2 text-sm font-semibold {{ $view === 'list' ? 'bg-dark-300 text-white' : 'text-dark-100 hover:text-dark-300' }}">Daftar</a>
    </div>

    <form method="GET" class="flex w-full flex-wrap items-end gap-2 sm:w-auto" role="search">
        @if ($view === 'list')
            <input type="hidden" name="view" value="list">
            <select name="status" class="field !w-auto !py-2 text-sm" aria-label="Status">
                <option value="">Semua status</option>
                @foreach (Order::STATUS_LABELS as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="field !w-auto !py-2 text-sm" aria-label="Dari tanggal">
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="field !w-auto !py-2 text-sm" aria-label="Sampai tanggal">
        @endif
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari klien / pekerjaan" class="field !w-full !py-2 text-sm sm:!w-56" aria-label="Cari">
        <button type="submit" class="btn-ink !min-h-10 !px-4 text-sm">Terapkan</button>
    </form>
</div>

@if ($view === 'board')
    <p class="mb-3 text-sm text-dark-100"><span class="md:hidden">Ubah status lewat pilihan di setiap kartu.</span><span class="hidden md:inline">Seret kartu ke kolom lain untuk mengubah status, atau pakai pilihan status di kartu.</span></p>
    <div data-kanban class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        @foreach (Order::STATUSES as $status)
            @php $items = $columns->get($status, collect()); @endphp
            <section data-kanban-column data-status="{{ $status }}" class="flex min-w-0 flex-col border border-line border-t-4 bg-white {{ $tone[$status] }}" aria-labelledby="col-{{ $status }}">
                <header class="flex items-center justify-between border-b border-line px-4 py-3">
                    <h2 id="col-{{ $status }}" class="font-bold text-dark-300">{{ Order::STATUS_LABELS[$status] }}</h2>
                    <span data-kanban-count class="num min-w-7 bg-dark-50 px-2 py-0.5 text-center text-xs font-bold text-dark-300">{{ $items->count() }}</span>
                </header>
                <div data-kanban-drop class="flex min-h-24 flex-1 flex-col gap-2 p-2 transition-colors">
                    @foreach ($items as $order)
                        <article data-order-card data-order-id="{{ $order->id }}" data-status="{{ $order->status }}" data-status-url="{{ route('admin.orders.status', $order) }}" draggable="true"
                                 class="cursor-grab border border-line bg-white p-3 shadow-sm transition-shadow hover:shadow-md active:cursor-grabbing">
                            <div class="flex items-start justify-between gap-2">
                                <a href="{{ route('admin.orders.edit', $order) }}" class="min-w-0 font-semibold leading-snug text-dark-300 hover:underline">{{ $order->client_name }}</a>
                                <span class="num shrink-0 text-sm font-semibold text-dark-300">@rp($order->price)</span>
                            </div>
                            <p class="mt-0.5 line-clamp-2 text-sm text-dark-100">{{ $order->task_type }}</p>
                            <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs">
                                <span class="{{ $order->isOverdue() ? 'font-bold text-red-700' : 'text-dark-100' }}">
                                    {{ $order->isOverdue() ? 'Lewat deadline' : 'Deadline' }} {{ $order->deadline->translatedFormat('d M Y') }}
                                </span>
                                @if ($order->payment_method)
                                    <span class="bg-dark-50 px-1.5 py-0.5 font-semibold text-dark-100">{{ $order->payment_method }}</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-3 border-t border-line pt-2.5">
                                @csrf
                                @method('PATCH')
                                <label class="sr-only" for="status-{{ $order->id }}">Pindahkan ke</label>
                                <select id="status-{{ $order->id }}" name="status" data-autosubmit class="w-full border-line py-1.5 text-xs">
                                    @foreach (Order::STATUS_LABELS as $value => $label)
                                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <noscript><button type="submit" class="mt-1 text-xs font-semibold underline">Simpan</button></noscript>
                            </form>
                        </article>
                    @endforeach
                    <p data-kanban-empty class="{{ $items->isEmpty() ? '' : 'hidden' }} m-auto px-4 py-8 text-center text-sm text-dark-100">Belum ada proyek.</p>
                </div>
            </section>
        @endforeach
    </div>
@else
    <ul class="space-y-2 md:hidden">
        @forelse ($orders as $order)
            <li class="border border-line bg-white p-4">
                <div class="flex items-start justify-between gap-3">
                    <a href="{{ route('admin.orders.edit', [$order, 'return' => 'list']) }}" class="font-semibold text-dark-300 hover:underline">{{ $order->client_name }}</a>
                    <span class="tag shrink-0">{{ $order->status_label }}</span>
                </div>
                <p class="text-sm text-dark-100">{{ $order->task_type }}</p>
                <p class="mt-2 flex justify-between text-sm"><span class="{{ $order->isOverdue() ? 'font-semibold text-red-700' : 'text-dark-100' }}">{{ $order->deadline->translatedFormat('d M Y') }}</span><span class="num font-semibold text-dark-300">@rp($order->price)</span></p>
            </li>
        @empty
            <li class="border border-line bg-white px-4 py-10 text-center text-dark-100">Tidak ada proyek yang cocok.</li>
        @endforelse
    </ul>
    <div class="hidden overflow-x-auto border border-line bg-white md:block">
        <table class="w-full text-sm">
            <thead class="border-b border-line bg-dark-50 text-left text-dark-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Klien</th>
                    <th class="px-4 py-3 font-semibold">Pekerjaan</th>
                    <th class="px-4 py-3 font-semibold">Deadline</th>
                    <th class="px-4 py-3 text-right font-semibold">Harga</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3"><span class="sr-only">Aksi</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($orders as $order)
                    <tr class="hover:bg-dark-50/60">
                        <td class="px-4 py-3 font-semibold text-dark-300">{{ $order->client_name }}</td>
                        <td class="px-4 py-3 text-dark-200">{{ $order->task_type }}</td>
                        <td class="px-4 py-3 {{ $order->isOverdue() ? 'font-semibold text-red-700' : 'text-dark-100' }}">{{ $order->deadline->translatedFormat('d M Y') }}</td>
                        <td class="num px-4 py-3 text-right font-semibold text-dark-300">@rp($order->price)</td>
                        <td class="px-4 py-3"><span class="tag">{{ $order->status_label }}</span></td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.orders.edit', [$order, 'return' => 'list']) }}" class="font-semibold text-primary-700 hover:underline">Ubah</a>
                                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" data-confirm="Hapus proyek {{ $order->client_name }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-700 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-dark-100">Tidak ada proyek yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>
@endif
@endsection
