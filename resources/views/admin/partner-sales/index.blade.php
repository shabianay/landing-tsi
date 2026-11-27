@extends('layouts.admin')

@section('title', 'Klien Partner & Komisi — Admin TSI')

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-dark-300 md:text-3xl">Klien Partner &amp; Komisi</h1>
        <p class="mt-1 font-medium text-dark-100">Catat calon klien dari WhatsApp, deal, pembayaran, dan komisi partner.</p>
    </div>
    <a href="{{ route('admin.partner-sales.create') }}" class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">Catat calon klien</a>
</div>

@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-3.5 text-sm font-medium text-green-700">{{ session('success') }}</div>
@endif

<div class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm"><p class="text-sm text-dark-100">Total calon klien tercatat</p><p class="mt-1 text-2xl font-bold text-dark-300">{{ $leadCount }}</p></div>
    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm"><p class="text-sm text-dark-100">Pembayaran diterima · {{ $month->translatedFormat('F Y') }}</p><p class="mt-1 text-2xl font-bold text-dark-300">Rp{{ number_format($paymentsReceived, 0, ',', '.') }}</p></div>
    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm"><p class="text-sm text-dark-100">Komisi belum dibayar</p><p class="mt-1 text-2xl font-bold text-dark-300">Rp{{ number_format($commissionOutstanding, 0, ',', '.') }}</p></div>
    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm"><p class="text-sm text-dark-100">Komisi sudah dibayar</p><p class="mt-1 text-2xl font-bold text-dark-300">Rp{{ number_format($commissionPaid, 0, ',', '.') }}</p></div>
</div>

<section class="mb-6 rounded-2xl border border-line bg-white p-5 shadow-sm">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-dark-300">Bonus bulanan partner</h2>
            <p class="mt-1 text-sm leading-6 text-dark-100">Rp500.000 per 3 klien berbeda dengan deal minimal Rp2.000.000 yang membayar DP pada bulan tersebut. Klien dihitung sekali per bulan.</p>
        </div>
        <form method="GET" action="{{ route('admin.partner-sales.index') }}" class="flex items-end gap-2">
            <label class="text-xs font-semibold text-dark-100">Bulan
                <input type="month" name="month" value="{{ $month->format('Y-m') }}" class="mt-1 block rounded-lg border-slate-200 text-sm">
            </label>
            <button class="rounded-lg bg-dark-300 px-4 py-2 text-sm font-semibold text-white">Lihat</button>
        </form>
    </div>
    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-dark-100"><tr><th class="py-3 pr-4 font-semibold">Partner</th><th class="py-3 pr-4 font-semibold">Klien memenuhi syarat</th><th class="py-3 pr-4 font-semibold">Bonus</th><th class="py-3 pr-4 font-semibold">Status</th><th class="py-3 font-semibold">Aksi</th></tr></thead>
            <tbody class="divide-y divide-line">
                @forelse ($monthlyBonuses as $bonus)
                    <tr>
                        <td class="py-3 pr-4"><span class="font-semibold">{{ $bonus->partner->partner_code }}</span> · {{ $bonus->partner->full_name }}</td>
                        <td class="py-3 pr-4">{{ $bonus->eligible_client_count }}</td>
                        <td class="py-3 pr-4 font-semibold">Rp{{ number_format($bonus->amount, 0, ',', '.') }}</td>
                        <td class="py-3 pr-4">
                            @if ($bonus->paid_amount >= $bonus->amount && $bonus->amount > 0)
                                Dibayar Rp{{ number_format($bonus->paid_amount, 0, ',', '.') }}{{ $bonus->paid_at ? ' · '.$bonus->paid_at->format('d/m/Y') : '' }}
                            @elseif ($bonus->amount > $bonus->paid_amount)
                                Kurang bayar Rp{{ number_format($bonus->amount - $bonus->paid_amount, 0, ',', '.') }}
                            @else
                                Belum memenuhi syarat
                            @endif
                        </td>
                        <td class="py-3">
                            @if ($bonus->amount > $bonus->paid_amount)
                                <form method="POST" action="{{ route('admin.partner-sales.bonuses.paid', $bonus) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Tandai dibayar</button>
                                </form>
                            @else
                                <span class="text-dark-100">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-5 text-center text-dark-100">Belum ada pembayaran DP partner pada bulan ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
    <div class="border-b border-line px-5 py-4"><h2 class="font-bold text-dark-300">Daftar calon klien</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-dark-50/50 text-dark-100"><tr><th class="px-5 py-4 font-semibold">Klien</th><th class="px-5 py-4 font-semibold">Partner</th><th class="px-5 py-4 font-semibold">Layanan</th><th class="px-5 py-4 font-semibold">Deal</th><th class="px-5 py-4 font-semibold">Dicatat</th></tr></thead>
            <tbody class="divide-y divide-line">
                @forelse ($leads as $lead)
                    <tr class="hover:bg-dark-50/30">
                        <td class="px-5 py-4"><a href="{{ route('admin.partner-sales.show', $lead) }}" class="font-semibold text-primary-600 hover:underline">{{ $lead->full_name }}</a><span class="mt-1 block text-xs text-dark-100">{{ $lead->whatsapp }}</span></td>
                        <td class="whitespace-nowrap px-5 py-4"><span class="font-semibold">{{ $lead->partner->partner_code }}</span><span class="mt-1 block text-xs text-dark-100">{{ $lead->partner->full_name }}</span></td>
                        <td class="px-5 py-4">{{ $lead->service }}</td>
                        <td class="px-5 py-4">{{ $lead->deals_count }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $lead->reported_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-dark-100">Belum ada calon klien. Catat klien yang dilaporkan melalui WhatsApp sebelum layanan ditawarkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($leads->hasPages())<div class="border-t border-line px-5 py-4">{{ $leads->links() }}</div>@endif
</section>
@endsection
