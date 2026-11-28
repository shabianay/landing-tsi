{{-- Edit page, below the invoice form: payment history + sending the invoice to the client. --}}
<div class="mt-6 grid gap-6 xl:grid-cols-12 xl:items-start">
    <section id="pembayaran" class="scroll-mt-6 border border-line bg-white xl:col-span-8" aria-labelledby="pembayaran-title">
        <header class="flex flex-wrap items-baseline justify-between gap-2 border-b border-line px-5 py-4 sm:px-8">
            <h2 id="pembayaran-title" class="font-bold text-dark-300">Riwayat pembayaran</h2>
            <p class="num text-sm text-dark-100">Dibayar <strong class="text-dark-300">@rp($invoice->paid_amount)</strong> dari @rp($invoice->total) · sisa <strong class="text-dark-300">@rp($invoice->remaining)</strong></p>
        </header>

        @if ($invoice->payments->isEmpty())
            <p class="px-5 py-8 text-center text-sm text-dark-100 sm:px-8">Belum ada pembayaran.</p>
        @else
            <ol class="divide-y divide-line">
                @foreach ($invoice->payments as $payment)
                    <li class="grid gap-2 px-5 py-3.5 sm:grid-cols-[8rem_1fr_auto] sm:items-center sm:px-8">
                        <span class="text-sm text-dark-100">{{ $payment->paid_at->translatedFormat('d M Y') }}</span>
                        <div class="min-w-0">
                            <p class="num font-semibold text-dark-300">@rp($payment->amount)
                                @if ($payment->method)<span class="ml-1.5 bg-dark-50 px-1.5 py-0.5 text-xs font-semibold text-dark-100">{{ $payment->method }}</span>@endif
                            </p>
                            @if ($payment->note)<p class="text-xs text-dark-100 [overflow-wrap:anywhere]">{{ $payment->note }}</p>@endif
                        </div>
                        <div class="flex gap-4 text-sm">
                            <a href="{{ route('admin.invoices.payments.receipt', [$invoice, $payment]) }}" target="_blank" rel="noopener" class="font-semibold text-dark-300 underline">Kwitansi</a>
                            <form method="POST" action="{{ route('admin.invoices.payments.destroy', [$invoice, $payment]) }}" data-confirm="Hapus pembayaran @rp($payment->amount) tanggal {{ $payment->paid_at->format('d/m/Y') }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-700 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif

        @if ($invoice->remaining > 0)
            <form method="POST" action="{{ route('admin.invoices.payments.store', $invoice) }}" class="grid gap-3 border-t border-line bg-dark-50/60 px-5 py-5 sm:grid-cols-12 sm:items-end sm:px-8">
                @csrf
                <div class="sm:col-span-3">
                    <label for="pay-amount" class="field-label">Jumlah (Rp)</label>
                    <input id="pay-amount" type="number" name="amount" min="1" max="{{ $invoice->remaining }}" step="any" value="{{ old('amount', (float) $invoice->remaining) }}" required class="field num" @error('amount') aria-invalid="true" @enderror>
                </div>
                <div class="sm:col-span-3">
                    <label for="pay-date" class="field-label">Tanggal bayar</label>
                    <input id="pay-date" type="date" name="paid_at" value="{{ old('paid_at', today()->format('Y-m-d')) }}" max="{{ today()->addDay()->format('Y-m-d') }}" required class="field">
                </div>
                <div class="sm:col-span-2">
                    <label for="pay-method" class="field-label">Metode</label>
                    <select id="pay-method" name="method" class="field">
                        <option value="">—</option>
                        @foreach (\App\Models\Order::PAYMENT_METHODS as $method)
                            <option value="{{ $method }}" @selected(old('method') === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-4">
                    <label for="pay-note" class="field-label">Catatan</label>
                    <input id="pay-note" name="note" maxlength="255" value="{{ old('note') }}" placeholder="mis. Pelunasan" class="field">
                </div>
                @error('amount')<p class="field-error sm:col-span-12">{{ $message }}</p>@enderror
                @error('paid_at')<p class="field-error sm:col-span-12">{{ $message }}</p>@enderror
                <div class="sm:col-span-12">
                    <button type="submit" class="btn-ink">Catat pembayaran</button>
                </div>
            </form>
        @endif
    </section>

    <section class="border border-line bg-white xl:col-span-4" aria-labelledby="kirim-title">
        <h2 id="kirim-title" class="border-b border-line px-5 py-4 font-bold text-dark-300 sm:px-6">Kirim ke klien</h2>
        <div class="space-y-5 p-5 sm:p-6">
            <form method="POST" action="{{ route('admin.invoices.share.whatsapp', $invoice) }}" target="_blank">
                @csrf
                <button type="submit" class="btn-wa w-full">
                    <x-icon-whatsapp class="h-5 w-5" />
                    Kirim lewat WhatsApp
                </button>
                <p class="field-hint">
                    @if ($invoice->client?->phone)
                        Ke {{ $invoice->client->phone }}. Pesan berisi total, sisa, dan link PDF; Anda masih bisa mengubahnya sebelum dikirim.
                    @else
                        Nomor klien belum diisi, jadi Anda memilih kontak di WhatsApp.
                    @endif
                </p>
            </form>

            <form method="POST" action="{{ route('admin.invoices.share.email', $invoice) }}" class="space-y-2 border-t border-line pt-5">
                @csrf
                <label for="share-email" class="field-label">Kirim lewat email</label>
                <input id="share-email" type="email" name="email" value="{{ old('email', $invoice->client?->email) }}" required placeholder="email@klien.com" class="field" @error('email') aria-invalid="true" @enderror>
                <label for="share-message" class="sr-only">Pesan</label>
                <textarea id="share-message" name="message" rows="2" maxlength="2000" placeholder="Pesan singkat (opsional)" class="field">{{ old('message') }}</textarea>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
                <button type="submit" class="btn-secondary w-full">Kirim email + PDF</button>
            </form>

            <div class="border-t border-line pt-5 text-sm">
                <p class="field-label">Link invoice untuk klien</p>
                @if ($shareUrl)
                    <input type="text" readonly value="{{ $shareUrl }}" class="field num !py-2 text-xs" aria-label="Link invoice" data-select-on-focus>
                    <p class="field-hint">Siapa pun yang punya link ini bisa membuka PDF-nya.</p>
                    <form method="POST" action="{{ route('admin.invoices.share.reset', $invoice) }}" data-confirm="Buat link baru? Link lama tidak bisa dibuka lagi." class="mt-2">
                        @csrf
                        <button type="submit" class="font-semibold text-red-700 hover:underline">Ganti link (matikan yang lama)</button>
                    </form>
                @else
                    <p class="text-dark-100">Dibuat otomatis saat invoice pertama kali dikirim.</p>
                @endif
            </div>
        </div>
    </section>
</div>
