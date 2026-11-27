@extends('layouts.admin')

@section('title', $lead->full_name.' — Klien Partner')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.partner-sales.index') }}" class="text-sm font-semibold text-primary-600 hover:underline">← Kembali ke pencatatan klien</a>
    <div class="mt-4 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-dark-300 md:text-3xl">{{ $lead->full_name }}</h1>
            <p class="mt-1 text-sm text-dark-100/60">{{ $lead->partner->partner_code }} · {{ $lead->partner->full_name }} · Dilaporkan {{ $lead->reported_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="https://wa.me/{{ $lead->whatsapp }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700">Chat calon klien</a>
    </div>
</div>

@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-3.5 text-sm font-medium text-green-700">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<section class="mb-6 rounded-2xl border border-dark-100/5 bg-white p-5 shadow-sm sm:p-7">
    <h2 class="text-lg font-bold text-dark-300">Data calon klien</h2>
    <dl class="mt-4 grid gap-4 sm:grid-cols-2">
        <div><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">WhatsApp</dt><dd class="mt-1 font-medium text-dark-300">{{ $lead->whatsapp }}</dd></div>
        <div><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Layanan yang ditawarkan</dt><dd class="mt-1 font-medium text-dark-300">{{ $lead->service }}</dd></div>
        <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Catatan</dt><dd class="mt-1 whitespace-pre-line text-dark-300">{{ $lead->notes ?: '—' }}</dd></div>
    </dl>
</section>

<div class="mb-4 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold text-dark-300">Deal &amp; pembayaran</h2>
        <p class="mt-1 text-sm text-dark-100/60">Komisi 10% dihitung dari setiap pembayaran yang diterima; nilai deal adalah harga setelah diskon.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.partner-sales.deals.store', $lead) }}" class="mb-6 grid gap-4 rounded-2xl border border-dark-100/5 bg-white p-5 shadow-sm sm:grid-cols-2 sm:p-7">
    @csrf
    <h3 class="text-base font-bold text-dark-300 sm:col-span-2">Catat deal baru</h3>
    <label class="block text-sm font-semibold text-dark-300">Nama/uraian deal *
        <input name="description" value="{{ old('description') }}" required maxlength="150" placeholder="Contoh: Website company profile" class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
    </label>
    <label class="block text-sm font-semibold text-dark-300">Harga sebelum diskon (Rp) *
        <input name="deal_amount" type="number" min="1" step="1" value="{{ old('deal_amount') }}" required class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
    </label>
    <label class="block text-sm font-semibold text-dark-300">Diskon (Rp) *
        <input name="discount_amount" type="number" min="0" step="1" value="{{ old('discount_amount', 0) }}" required class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
    </label>
    <div class="flex items-end sm:justify-end"><button class="w-full rounded-xl bg-primary-500 px-4 py-3 text-sm font-semibold text-white hover:bg-primary-600 sm:w-auto">Simpan deal</button></div>
</form>

<div class="space-y-6">
    @forelse ($lead->deals as $deal)
        @php
            $paidAmount = (int) $deal->payments->sum('amount');
            $remainingAmount = max(0, $deal->final_amount - $paidAmount);
        @endphp
        <section class="overflow-hidden rounded-2xl border border-dark-100/5 bg-white shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-dark-100/5 p-5 sm:p-7">
                <div>
                    <h3 class="text-lg font-bold text-dark-300">{{ $deal->description }}</h3>
                    <p class="mt-1 text-sm text-dark-100/60">Deal {{ $deal->created_at->format('d/m/Y') }} · Sebelum diskon Rp{{ number_format($deal->deal_amount, 0, ',', '.') }} · Diskon Rp{{ number_format($deal->discount_amount, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Nilai deal final</p>
                    <p class="text-xl font-bold text-dark-300">Rp{{ number_format($deal->final_amount, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-dark-100/60">Diterima Rp{{ number_format($paidAmount, 0, ',', '.') }} · Sisa Rp{{ number_format($remainingAmount, 0, ',', '.') }}</p>
                    @if ($deal->payments->isEmpty())
                        <form method="POST" action="{{ route('admin.partner-sales.deals.destroy', $deal) }}" data-confirm="Hapus deal yang salah input ini?" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs font-semibold text-red-600 hover:underline">Hapus deal</button>
                        </form>
                    @endif
                </div>
            </div>

            @if ($remainingAmount > 0)
                <form method="POST" action="{{ route('admin.partner-sales.payments.store', $deal) }}" class="grid gap-3 border-b border-dark-100/5 bg-dark-50/30 p-5 sm:grid-cols-4 sm:p-6">
                    @csrf
                    <label class="text-xs font-semibold text-dark-100/60">Jenis pembayaran
                        <select name="payment_type" required class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                            <option value="dp">DP</option>
                            <option value="cicilan">Cicilan</option>
                            <option value="pelunasan">Pelunasan</option>
                        </select>
                    </label>
                    <label class="text-xs font-semibold text-dark-100/60">Jumlah diterima (Rp)
                        <input name="amount" type="number" min="1" max="{{ $remainingAmount }}" step="1" required class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                    </label>
                    <label class="text-xs font-semibold text-dark-100/60">Tanggal diterima
                        <input name="received_at" type="datetime-local" value="{{ now()->format('Y-m-d\TH:i') }}" required class="mt-1 block w-full rounded-lg border-slate-200 text-sm">
                    </label>
                    <div class="flex items-end"><button class="w-full rounded-lg bg-dark-300 px-4 py-2.5 text-sm font-semibold text-white hover:bg-dark-200">Catat pembayaran</button></div>
                </form>
            @endif

            <div class="overflow-x-auto p-5 sm:p-6">
                <table class="w-full text-left text-sm">
                    <thead class="text-dark-100/60"><tr><th class="py-2 pr-4 font-semibold">Jenis</th><th class="py-2 pr-4 font-semibold">Pembayaran diterima</th><th class="py-2 pr-4 font-semibold">Komisi 10%</th><th class="py-2 pr-4 font-semibold">Status komisi</th><th class="py-2 font-semibold">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-dark-100/5">
                        @forelse ($deal->payments as $payment)
                            <tr>
                                <td class="py-3 pr-4">{{ ['dp' => 'DP', 'cicilan' => 'Cicilan', 'pelunasan' => 'Pelunasan'][$payment->payment_type] }}</td>
                                <td class="py-3 pr-4">Rp{{ number_format($payment->amount, 0, ',', '.') }}<span class="mt-1 block text-xs text-dark-100/50">{{ $payment->received_at->format('d/m/Y H:i') }}</span></td>
                                <td class="py-3 pr-4 font-semibold">Rp{{ number_format($payment->commission_amount, 0, ',', '.') }}</td>
                                <td class="py-3 pr-4">{{ $payment->commission_paid_at ? 'Dibayar '.$payment->commission_paid_at->format('d/m/Y') : 'Belum dibayar' }}</td>
                                <td class="py-3">
                                    @if (! $payment->commission_paid_at)
                                        <div class="flex flex-wrap gap-2">
                                            <form method="POST" action="{{ route('admin.partner-sales.commissions.paid', $payment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Tandai dibayar</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.partner-sales.payments.destroy', $payment) }}" data-confirm="Hapus pembayaran yang salah input ini? Komisi dan bonus akan dihitung ulang.">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-dark-100/40">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-5 text-center text-dark-100/60">Belum ada pembayaran masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @empty
        <div class="rounded-2xl border border-dashed border-dark-100/20 bg-white p-10 text-center text-sm text-dark-100/60">Deal belum dicatat. Simpan nilai deal final setelah diskon untuk mulai mencatat pembayaran.</div>
    @endforelse
</div>
@endsection
