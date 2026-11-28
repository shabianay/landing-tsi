@extends('layouts.admin')

@section('title', 'Laporan Keuangan — Admin TSI')

@php
    $monthNames = collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => \Illuminate\Support\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F')]);
    $period = $month ? $monthNames[$month] . ' ' . $year : 'Tahun ' . $year;
    $badge = ['paid' => 'bg-emerald-50 text-emerald-800', 'partial' => 'bg-amber-50 text-amber-900', 'unpaid' => 'bg-red-50 text-red-800'];
@endphp

@section('content')
@include('admin.partials.pm-head', ['title' => 'Laporan Keuangan', 'subtitle' => 'Tagihan menurut tanggal invoice, uang masuk menurut tanggal pembayaran. Periode: ' . $period . '.'])

<form method="GET" class="mb-6 flex flex-wrap items-end gap-2">
    <div>
        <label for="year" class="field-label !mb-1 text-xs">Tahun</label>
        <select id="year" name="year" class="field !w-auto !py-2 text-sm">
            @foreach ($years as $y)
                <option value="{{ $y }}" @selected($y === $year)>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="month" class="field-label !mb-1 text-xs">Bulan</label>
        <select id="month" name="month" class="field !w-auto !py-2 text-sm">
            <option value="">Semua bulan</option>
            @foreach ($monthNames as $m => $name)
                <option value="{{ $m }}" @selected($m === $month)>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-ink !min-h-10 !px-4 text-sm">Tampilkan</button>
</form>

<dl class="mb-8 grid gap-1.5 sm:grid-cols-3">
    <div class="border border-line bg-white px-5 py-4">
        <dt class="text-sm text-dark-100">Total tagihan</dt>
        <dd class="num font-display text-3xl font-extrabold text-dark-300">@rp($totalRevenue)</dd>
        <dd class="mt-1 text-sm text-dark-100">{{ $invoices->count() }} invoice</dd>
    </div>
    <div class="bg-primary-500 px-5 py-4 text-dark-300">
        <dt class="text-sm font-semibold">Uang masuk</dt>
        <dd class="num font-display text-3xl font-extrabold">@rp($totalPaid)</dd>
    </div>
    <div class="bg-dark-300 px-5 py-4 text-white">
        <dt class="text-sm text-white/75">Belum dibayar</dt>
        <dd class="num font-display text-3xl font-extrabold">@rp($unpaid['total'])</dd>
        <dd class="mt-1 text-sm text-white/75">{{ $unpaid['count'] }} invoice tanpa pembayaran · DP tidak dihitung</dd>
    </div>
</dl>

<section class="mb-8 border border-line bg-white">
    <h2 class="border-b border-line px-5 py-4 font-bold text-dark-300">Per bulan, {{ $year }}</h2>
    <ol class="divide-y divide-line">
        @foreach ($months as $row)
            <li class="grid grid-cols-[5.5rem_1fr_auto] items-center gap-4 px-5 py-2.5 text-sm {{ $month === $row->month ? 'bg-primary-50' : '' }}">
                <a href="{{ route('admin.financial.index', ['year' => $year, 'month' => $row->month]) }}" class="font-medium text-dark-300 hover:underline">{{ $monthNames[$row->month] }}</a>
                <div class="relative h-4 bg-dark-50" role="img" aria-label="Tagihan @rp($row->total), dibayar @rp($row->paid)">
                    <span class="absolute inset-y-0 left-0 bg-dark-100/25" style="width: {{ round($row->total / $maxMonth * 100, 1) }}%"></span>
                    <span class="absolute inset-y-0 left-0 bg-primary-500" style="width: {{ round($row->paid / $maxMonth * 100, 1) }}%"></span>
                </div>
                <span class="num w-36 text-right font-semibold text-dark-300">@rp($row->total) <span class="font-normal text-dark-100">· {{ $row->count }}</span></span>
            </li>
        @endforeach
    </ol>
    <p class="flex flex-wrap gap-x-5 gap-y-1 border-t border-line px-5 py-3 text-xs text-dark-100">
        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 bg-primary-500"></span>Dibayar</span>
        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 bg-dark-100/25"></span>Total tagihan</span>
        <span>Angka di kanan: total tagihan · jumlah invoice</span>
    </p>
</section>

<section class="border border-line bg-white">
    <h2 class="border-b border-line px-5 py-4 font-bold text-dark-300">Invoice {{ strtolower($period) }}</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[44rem] text-sm">
            <thead class="border-b border-line bg-dark-50 text-left text-dark-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                    <th class="px-4 py-3 font-semibold">Nomor</th>
                    <th class="px-4 py-3 font-semibold">Klien</th>
                    <th class="px-4 py-3 text-right font-semibold">Total</th>
                    <th class="px-4 py-3 text-right font-semibold">Dibayar</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($invoices as $invoice)
                    <tr>
                        <td class="px-4 py-2.5 text-dark-100">{{ $invoice->invoice_date->translatedFormat('d M Y') }}</td>
                        <td class="num px-4 py-2.5"><a href="{{ route('admin.invoices.edit', $invoice) }}" class="font-semibold text-dark-300 hover:underline">{{ $invoice->invoice_number }}</a></td>
                        <td class="px-4 py-2.5 text-dark-200">{{ $invoice->client_name }}</td>
                        <td class="num px-4 py-2.5 text-right font-semibold">@rp($invoice->document_total)</td>
                        <td class="num px-4 py-2.5 text-right">@rp($invoice->paid_amount)</td>
                        <td class="px-4 py-2.5"><span class="px-2 py-0.5 text-xs font-bold {{ $badge[$invoice->document_status] }}">{{ $invoice->status_label }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-dark-100">Tidak ada invoice pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
