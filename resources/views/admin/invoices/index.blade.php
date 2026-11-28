@extends('layouts.admin')

@section('title', 'Invoice — Admin TSI')

@php
    $badge = ['paid' => 'bg-emerald-50 text-emerald-800', 'partial' => 'bg-amber-50 text-amber-900', 'unpaid' => 'bg-red-50 text-red-800'];
@endphp

@section('content')
@include('admin.partials.pm-head', [
    'title' => 'Invoice',
    'subtitle' => 'Buat tagihan, cetak PDF, dan catat pembayaran.',
    'actions' => '<a href="' . route('admin.invoices.settings') . '" class="btn-secondary !min-h-11 !px-4 text-sm">Pengaturan invoice</a>'
        . '<a href="' . route('admin.invoices.create') . '" class="btn-primary !min-h-11 !px-5 text-sm">+ Invoice baru</a>',
])

<dl class="mb-6 grid gap-1.5 sm:grid-cols-3">
    <div class="border border-line bg-white px-5 py-4">
        <dt class="text-sm text-dark-100">Total tagihan</dt>
        <dd class="num font-display text-2xl font-extrabold text-dark-300">@rp($totals['total'])</dd>
    </div>
    <div class="border border-line bg-white px-5 py-4">
        <dt class="text-sm text-dark-100">Sudah dibayar</dt>
        <dd class="num font-display text-2xl font-extrabold text-emerald-700">@rp($totals['paid'])</dd>
    </div>
    <div class="bg-dark-300 px-5 py-4 text-white">
        <dt class="text-sm text-white/75">Belum dibayar</dt>
        <dd class="num font-display text-2xl font-extrabold">@rp($totals['unpaid']['total'])</dd>
        <dd class="mt-1 text-xs text-white/75">{{ $totals['unpaid']['count'] }} invoice tanpa pembayaran · DP tidak dihitung</dd>
    </div>
</dl>

<form method="GET" class="mb-5 flex flex-wrap gap-2" role="search">
    <select name="status" class="field !w-auto !py-2 text-sm" aria-label="Status">
        <option value="">Semua status</option>
        <option value="unpaid" @selected(($filters['status'] ?? '') === 'unpaid')>Belum Bayar</option>
        <option value="partial" @selected(($filters['status'] ?? '') === 'partial')>DP</option>
        <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Lunas</option>
    </select>
    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nomor / klien" class="field !w-full !py-2 text-sm sm:!w-64" aria-label="Cari invoice">
    <button type="submit" class="btn-ink !min-h-10 !px-4 text-sm">Terapkan</button>
</form>

<ul class="space-y-2 md:hidden">
    @forelse ($invoices as $invoice)
        <li class="border border-line bg-white p-4" x-data="{ pay: false }">
            <div class="flex items-start justify-between gap-3">
                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="num min-w-0 font-semibold text-dark-300 [overflow-wrap:anywhere] hover:underline">{{ $invoice->invoice_number }}</a>
                <span class="shrink-0 px-2 py-0.5 text-xs font-bold {{ $badge[$invoice->status] }}">{{ $invoice->status_label }}</span>
            </div>
            <p class="text-sm text-dark-100">{{ $invoice->client_name }} · {{ $invoice->invoice_date->translatedFormat('d M Y') }}</p>
            <dl class="num mt-3 grid grid-cols-2 gap-2 text-sm">
                <div><dt class="text-xs text-dark-100">Total</dt><dd class="font-semibold text-dark-300">@rp($invoice->total)</dd></div>
                <div><dt class="text-xs text-dark-100">Dibayar</dt><dd class="text-dark-200">@rp($invoice->paid_amount)</dd></div>
            </dl>
            <form method="POST" action="{{ route('admin.invoices.payments.store', $invoice) }}" x-show="pay" x-cloak class="mt-3 flex gap-2">
                @csrf
                <input type="hidden" name="paid_at" value="{{ today()->format('Y-m-d') }}">
                <label class="sr-only" for="m-paid-{{ $invoice->id }}">Jumlah pembayaran baru (hari ini)</label>
                <input id="m-paid-{{ $invoice->id }}" type="number" name="amount" min="1" max="{{ $invoice->remaining }}" step="any" value="{{ (float) $invoice->remaining }}" class="field num !py-2">
                <button type="submit" class="btn-ink !min-h-0 !px-4 text-sm">Simpan</button>
            </form>
            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 border-t border-line pt-3 text-sm">
                <a href="{{ route('admin.invoices.pdf', $invoice) }}" target="_blank" rel="noopener" class="font-semibold text-dark-300 underline">PDF</a>
                @if ($invoice->status !== 'paid')
                    <button type="button" @click="pay = !pay" class="font-semibold text-emerald-700 underline">Catat bayar</button>
                @endif
                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="font-semibold text-primary-700 underline">Ubah</a>
            </div>
        </li>
    @empty
        <li class="border border-line bg-white px-4 py-10 text-center text-dark-100">Belum ada invoice.</li>
    @endforelse
</ul>
<div class="hidden overflow-x-auto border border-line bg-white md:block">
    <table class="w-full text-sm">
        <thead class="border-b border-line bg-dark-50 text-left text-dark-300">
            <tr>
                <th class="px-4 py-3 font-semibold">Nomor</th>
                <th class="px-4 py-3 font-semibold">Klien</th>
                <th class="px-4 py-3 font-semibold">Tanggal</th>
                <th class="px-4 py-3 text-right font-semibold">Total</th>
                <th class="px-4 py-3 text-right font-semibold">Dibayar</th>
                <th class="px-4 py-3 font-semibold">Status</th>
                <th class="px-4 py-3"><span class="sr-only">Aksi</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            @forelse ($invoices as $invoice)
                <tr class="align-top hover:bg-dark-50/60" x-data="{ pay: false }">
                    <td class="num px-4 py-3 font-semibold text-dark-300">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3 text-dark-200">{{ $invoice->client_name }}</td>
                    <td class="px-4 py-3 text-dark-100">{{ $invoice->invoice_date->translatedFormat('d M Y') }}</td>
                    <td class="num px-4 py-3 text-right font-semibold text-dark-300">@rp($invoice->total)</td>
                    <td class="num px-4 py-3 text-right text-dark-200">
                        @rp($invoice->paid_amount)
                        <form method="POST" action="{{ route('admin.invoices.payments.store', $invoice) }}" x-show="pay" x-cloak class="mt-2 flex justify-end gap-1">
                            @csrf
                            <input type="hidden" name="paid_at" value="{{ today()->format('Y-m-d') }}">
                            <label class="sr-only" for="paid-{{ $invoice->id }}">Jumlah pembayaran baru (hari ini)</label>
                            <input id="paid-{{ $invoice->id }}" type="number" name="amount" min="1" max="{{ $invoice->remaining }}" step="any" value="{{ (float) $invoice->remaining }}" class="w-32 border-line py-1 text-right text-sm">
                            <button type="submit" class="bg-dark-300 px-2.5 text-xs font-semibold text-white">Simpan</button>
                        </form>
                    </td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs font-bold {{ $badge[$invoice->status] }}">{{ $invoice->status_label }}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap justify-end gap-x-3 gap-y-1">
                            <a href="{{ route('admin.invoices.pdf', $invoice) }}" target="_blank" rel="noopener" class="font-semibold text-dark-300 hover:underline">PDF</a>
                            @if ($invoice->status !== 'paid')
                                <button type="button" @click="pay = !pay" class="font-semibold text-emerald-700 hover:underline" :aria-expanded="pay.toString()">Catat bayar</button>
                            @endif
                            <a href="{{ route('admin.invoices.edit', $invoice) }}" class="font-semibold text-primary-700 hover:underline">Ubah</a>
                            <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" data-confirm="Hapus invoice {{ $invoice->invoice_number }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-700 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-dark-100">Belum ada invoice.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $invoices->links() }}</div>
@endsection
