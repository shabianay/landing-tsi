@extends('layouts.admin')

@php
    use App\Models\Invoice;
    $editing = $invoice->exists;
    $items = old('items', $invoice->relationLoaded('items') && $invoice->items->isNotEmpty()
        ? $invoice->items->map(fn ($i) => ['name' => $i->item_name, 'qty' => $i->item_qty, 'price' => (int) $i->item_price])->all()
        : [['name' => '', 'qty' => 1, 'price' => 0]]);
    $usePpn = (bool) old('use_ppn', $editing ? (float) $invoice->ppn_amount > 0 : false);
    $projectOptions = $orders->map(fn ($o) => [
        'id' => $o->id,
        'client' => $o->order_client_id,
        'label' => $o->task_type . ' — deadline ' . $o->deadline?->translatedFormat('d M Y'),
    ])->values();
@endphp

@section('title', ($editing ? 'Ubah invoice' : 'Invoice baru') . ' — Admin TSI')

@section('content')
@include('admin.partials.pm-head', [
    'title' => $editing ? 'Ubah invoice ' . $invoice->invoice_number : 'Invoice baru',
])

<form method="POST" action="{{ $editing ? route('admin.invoices.update', $invoice) : route('admin.invoices.store') }}"
      x-data="{
          items: @js(array_values($items)),
          ppn: @js($usePpn),
          discount: @js((float) old('discount_amount', $invoice->discount_amount ?? 0)),
          discountType: @js(old('discount_type', $invoice->discount_type ?? 'fixed')),
          paid: @js((float) old('paid_amount', $invoice->paid_amount ?? 0)),
          ppnRate: @js((float) $ppnRate),
          client: @js((string) old('order_client_id', $selectedClient ?? '')),
          orderId: @js((string) old('order_id', $invoice->order_id ?? '')),
          projects: @js($projectOptions),
          get clientProjects() { return this.projects.filter(p => String(p.client) === this.client); },
          rp(n) { return 'Rp' + Math.round(n || 0).toLocaleString('id-ID'); },
          get subtotal() { return this.items.reduce((s, i) => s + (Number(i.qty) || 0) * (Number(i.price) || 0), 0); },
          get discountValue() { const d = Number(this.discount) || 0; return this.discountType === 'percent' ? this.subtotal * Math.min(d, 100) / 100 : d; },
          get ppnValue() { return this.ppn ? Math.max(0, this.subtotal - this.discountValue) * this.ppnRate / 100 : 0; },
          get total() { return Math.max(0, this.subtotal - this.discountValue) + this.ppnValue; },
          get status() { if (this.subtotal <= 0) return '—'; if (this.total <= 0 || this.paid >= this.total) return 'Lunas'; return this.paid > 0 ? 'DP' : 'Belum Bayar'; },
      }"
      class="grid gap-6 xl:grid-cols-12">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="space-y-6 xl:col-span-8">
        <section class="space-y-6 border border-line bg-white p-5 sm:p-8">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="invoice_number" class="field-label">Nomor invoice</label>
                    @if ($editing)
                        <p class="num field bg-dark-50">{{ $invoice->invoice_number }}</p>
                    @else
                        <input id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" maxlength="50" placeholder="Otomatis, mis. {{ Invoice::nextNumber(today()) }}" class="field num" @error('invoice_number') aria-invalid="true" @enderror>
                        <p class="field-hint">Kosongkan agar nomor dibuat otomatis sesuai bulan tanggal invoice.</p>
                    @endif
                </div>
                <div>
                    <label for="invoice_date" class="field-label">Tanggal *</label>
                    <input id="invoice_date" type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date?->format('Y-m-d')) }}" required class="field">
                </div>
            </div>
            <div>
                <label for="order_client_id" class="field-label">Ditagihkan kepada *</label>
                @if ($clients->isEmpty())
                    <p class="border border-dashed border-dark-100/40 p-4 text-sm text-dark-100">Belum ada data klien. <a href="{{ route('admin.order-clients.create') }}" class="font-semibold text-primary-700 underline">Tambah klien dulu</a>.</p>
                @else
                    <select id="order_client_id" name="order_client_id" required x-model="client" @change="orderId = ''" class="field" @error('order_client_id') aria-invalid="true" @enderror>
                        <option value="">Pilih klien</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected((int) old('order_client_id', $selectedClient) === $client->id)>{{ $client->name }}{{ $client->company ? ' — ' . $client->company : '' }}</option>
                        @endforeach
                    </select>
                    <p class="field-hint">Alamat diambil dari data klien.</p>
                @endif
            </div>
            <div>
                <label for="order_id" class="field-label">Untuk proyek</label>
                <select id="order_id" name="order_id" x-model="orderId" class="field" :disabled="clientProjects.length === 0" @error('order_id') aria-invalid="true" @enderror>
                    <option value="">Tidak dihubungkan</option>
                    <template x-for="p in clientProjects" :key="p.id">
                        <option :value="String(p.id)" x-text="p.label" :selected="String(p.id) === orderId"></option>
                    </template>
                </select>
                <p class="field-hint" x-show="clientProjects.length === 0">Klien ini belum punya proyek.</p>
                <p class="field-hint" x-show="clientProjects.length > 0">Tanggal DP dan lunas proyek akan mengikuti pembayaran invoice ini.</p>
                @error('order_id')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </section>

        <section class="border border-line bg-white">
            <h2 class="border-b border-line px-5 py-4 font-bold text-dark-300 sm:px-8">Item tagihan</h2>
            <div class="divide-y divide-line">
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-3 px-5 py-4 sm:px-8">
                        <div class="col-span-12 sm:col-span-6">
                            <label class="field-label !mb-1 text-xs" :for="'item-name-' + index">Keterangan</label>
                            <input :id="'item-name-' + index" :name="'items[' + index + '][name]'" x-model="item.name" required maxlength="255" class="field !py-2">
                        </div>
                        <div class="col-span-3 sm:col-span-2">
                            <label class="field-label !mb-1 text-xs" :for="'item-qty-' + index">Jumlah</label>
                            <input :id="'item-qty-' + index" type="number" min="1" :name="'items[' + index + '][qty]'" x-model.number="item.qty" required class="field num !py-2">
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="field-label !mb-1 text-xs" :for="'item-price-' + index">Harga (Rp)</label>
                            <input :id="'item-price-' + index" type="number" min="0" step="1000" :name="'items[' + index + '][price]'" x-model.number="item.price" required class="field num !py-2">
                        </div>
                        <div class="col-span-3 flex items-end justify-end sm:col-span-1">
                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="flex h-10 w-10 items-center justify-center text-red-700 hover:bg-red-50" aria-label="Hapus item">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                            </button>
                        </div>
                        <p class="num col-span-12 -mt-1 text-right text-xs text-dark-100" x-text="'Subtotal item: ' + rp((Number(item.qty) || 0) * (Number(item.price) || 0))"></p>
                    </div>
                </template>
            </div>
            <div class="border-t border-line px-5 py-4 sm:px-8">
                <button type="button" @click="items.push({ name: '', qty: 1, price: 0 })" class="font-semibold text-primary-700 hover:underline">+ Tambah item</button>
            </div>
        </section>

        <section class="border border-line bg-white p-5 sm:p-8">
            <label for="notes" class="field-label">Catatan</label>
            <textarea id="notes" name="notes" rows="2" maxlength="2000" class="field">{{ old('notes', $invoice->notes) }}</textarea>
        </section>
    </div>

    {{-- Summary --}}
    <aside class="xl:col-span-4">
        <div class="space-y-5 border border-line bg-white p-5 sm:p-6 xl:sticky xl:top-6">
            <label class="flex items-center gap-3">
                <input type="hidden" name="use_ppn" value="0">
                <input type="checkbox" name="use_ppn" value="1" x-model="ppn" class="h-5 w-5 rounded-sm border-dark-100/50 text-primary-600">
                <span class="font-semibold text-dark-300">Kenakan PPN {{ rtrim(rtrim(number_format($ppnRate, 2, ",", "."), "0"), ",") }}%</span>
            </label>
            <div>
                <span class="field-label">Diskon</span>
                <div class="flex">
                    <input type="number" name="discount_amount" min="0" step="any" x-model.number="discount" class="field num !rounded-r-none" aria-label="Nilai diskon">
                    <select name="discount_type" x-model="discountType" class="field !w-24 !rounded-l-none border-l-0" aria-label="Jenis diskon">
                        <option value="fixed">Rp</option>
                        <option value="percent">%</option>
                    </select>
                </div>
            </div>
            @if ($editing)
                <div class="border-t border-line pt-4 text-sm">
                    <p class="flex justify-between"><span class="text-dark-100">Sudah dibayar</span><span class="num font-semibold text-dark-300" x-text="rp(paid)"></span></p>
                    <a href="#pembayaran" class="mt-1 inline-block font-semibold text-primary-700 underline">Lihat / catat pembayaran</a>
                </div>
            @else
                <div class="grid grid-cols-5 gap-2">
                    <div class="col-span-3">
                        <label for="paid_amount" class="field-label">Sudah dibayar (Rp)</label>
                        <input id="paid_amount" type="number" name="paid_amount" min="0" step="any" :max="total" x-model.number="paid" class="field num" @error('paid_amount') aria-invalid="true" @enderror>
                    </div>
                    <div class="col-span-2">
                        <label for="payment_method" class="field-label">Metode</label>
                        <select id="payment_method" name="payment_method" class="field">
                            <option value="">—</option>
                            @foreach (\App\Models\Order::PAYMENT_METHODS as $method)
                                <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="field-hint col-span-5" x-show="paid > total" x-cloak>Melebihi total invoice.</p>
                    <p class="field-hint col-span-5" x-show="!(paid > total)">Isi bila klien sudah membayar (mis. DP). Tercatat sebagai pembayaran pertama pada tanggal invoice.</p>
                </div>
            @endif

            <dl class="num space-y-1.5 border-t border-line pt-4 text-sm">
                <div class="flex justify-between"><dt class="text-dark-100">Subtotal</dt><dd x-text="rp(subtotal)"></dd></div>
                <div class="flex justify-between"><dt class="text-dark-100">Diskon</dt><dd x-text="'−' + rp(discountValue)"></dd></div>
                <div class="flex justify-between"><dt class="text-dark-100">PPN (dari harga setelah diskon)</dt><dd x-text="rp(ppnValue)"></dd></div>
                <div class="flex items-baseline justify-between border-t border-line pt-3 text-base"><dt class="font-bold text-dark-300">Total</dt><dd class="font-display text-2xl font-extrabold text-dark-300" x-text="rp(total)"></dd></div>
                <div class="flex justify-between"><dt class="text-dark-100">Status</dt><dd class="font-semibold text-dark-300" x-text="status"></dd></div>
            </dl>

            <button type="submit" class="btn-primary w-full">{{ $editing ? 'Simpan perubahan' : 'Simpan invoice' }}</button>
            <a href="{{ route('admin.invoices.index') }}" class="btn-secondary w-full">{{ $editing ? 'Kembali ke daftar' : 'Batal' }}</a>
            @if ($editing)
                <a href="{{ route('admin.invoices.pdf', $invoice) }}" target="_blank" rel="noopener" class="block text-center text-sm font-semibold text-dark-300 underline">Buka PDF</a>
            @endif
        </div>
    </aside>
</form>

@if ($editing)
    @include('admin.invoices.partials.payments')
@endif
@endsection
