@extends('layouts.admin')

@php
    use App\Models\Order;
    $editing = $order->exists;
    $date = fn ($field) => old($field, $order->{$field}?->format('Y-m-d'));
    $linked = $editing && $order->invoices->isNotEmpty();
    $invoiceBadge = ['paid' => 'bg-emerald-50 text-emerald-800', 'partial' => 'bg-amber-50 text-amber-900', 'unpaid' => 'bg-red-50 text-red-800'];
@endphp

@section('title', ($editing ? 'Ubah proyek' : 'Proyek baru') . ' — Admin TSI')

@section('content')
@include('admin.partials.pm-head', [
    'title' => $editing ? 'Ubah proyek' : 'Proyek baru',
    'subtitle' => $editing
        ? 'Status yang Anda pilih dipertahankan. Bila tanggal DP atau lunas diubah, status menyesuaikan otomatis.'
        : 'Status terisi otomatis: ada tanggal DP berarti Dikerjakan, ada tanggal lunas berarti Selesai.',
])

<form method="POST" action="{{ $editing ? route('admin.orders.update', [$order, 'return' => request('return')]) : route('admin.orders.store') }}" class="max-w-3xl space-y-6 border border-line bg-white p-5 sm:p-8">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label for="order_client_id" class="field-label">Klien *</label>
        @if ($clients->isEmpty())
            <p class="border border-dashed border-dark-100/40 p-4 text-sm text-dark-100">
                Belum ada data klien. <a href="{{ route('admin.order-clients.create', ['return' => 'order']) }}" class="font-semibold text-primary-700 underline">Tambah klien dulu</a>.
            </p>
        @else
            <div class="flex gap-2">
                <select id="order_client_id" name="order_client_id" required class="field" @error('order_client_id') aria-invalid="true" @enderror>
                    <option value="">Pilih klien</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected((int) old('order_client_id', $selectedClient) === $client->id)>{{ $client->name }}{{ $client->company ? ' — ' . $client->company : '' }}</option>
                    @endforeach
                </select>
                <a href="{{ route('admin.order-clients.create', ['return' => 'order']) }}" class="btn-secondary !min-h-0 shrink-0 !px-4 text-sm">+ Klien</a>
            </div>
        @endif
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="task_type" class="field-label">Jenis pekerjaan *</label>
            <input id="task_type" name="task_type" value="{{ old('task_type', $order->task_type) }}" required maxlength="255" list="task-types" placeholder="Contoh: Website, Aplikasi Android" class="field" @error('task_type') aria-invalid="true" @enderror>
            <datalist id="task-types">
                <option value="Website"><option value="Aplikasi Android"><option value="Landing Page"><option value="Company Profile"><option value="Toko Online"><option value="Tugas Kuliah/Skripsi">
            </datalist>
        </div>
        <div>
            <label for="deadline" class="field-label">Deadline *</label>
            <input id="deadline" type="date" name="deadline" value="{{ $date('deadline') }}" required class="field" @error('deadline') aria-invalid="true" @enderror>
        </div>
    </div>

    <div>
        <label for="description" class="field-label">Deskripsi</label>
        <textarea id="description" name="description" rows="3" maxlength="5000" class="field">{{ old('description', $order->description) }}</textarea>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="price" class="field-label">Harga (Rp) *</label>
            <input id="price" type="number" name="price" min="0" step="1000" inputmode="numeric" value="{{ old('price', $order->price !== null ? (int) $order->price : '') }}" required class="field num" @error('price') aria-invalid="true" @enderror>
        </div>
        <div>
            <label for="payment_method" class="field-label">Metode pembayaran *</label>
            <select id="payment_method" name="payment_method" required class="field" @error('payment_method') aria-invalid="true" @enderror>
                <option value="">Pilih metode</option>
                @foreach (Order::PAYMENT_METHODS as $method)
                    <option value="{{ $method }}" @selected(old('payment_method', $order->payment_method) === $method)>{{ $method }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="payment_date" class="field-label">Tanggal DP</label>
            <input id="payment_date" type="date" name="payment_date" value="{{ $date('payment_date') }}" class="field" @if ($linked) readonly aria-describedby="dates-from-invoice" @endif>
        </div>
        <div>
            <label for="payment_lunas_date" class="field-label">Tanggal lunas</label>
            <input id="payment_lunas_date" type="date" name="payment_lunas_date" value="{{ $date('payment_lunas_date') }}" class="field" @if ($linked) readonly aria-describedby="dates-from-invoice" @endif>
        </div>
        @if ($linked)
            <p id="dates-from-invoice" class="field-hint sm:col-span-2">Tanggal DP dan lunas terisi otomatis dari pembayaran invoice proyek ini.</p>
        @endif
        @if ($editing)
            <div>
                <label for="status" class="field-label">Status</label>
                <select id="status" name="status" class="field">
                    @foreach (Order::STATUS_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $order->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-line pt-6">
        <button type="submit" class="btn-primary">{{ $editing ? 'Simpan perubahan' : 'Simpan proyek' }}</button>
        <a href="{{ route('admin.orders.index', request('return') === 'list' ? ['view' => 'list'] : []) }}" class="btn-secondary">Batal</a>
    </div>
</form>

@if ($editing)
    <section class="mt-6 max-w-3xl border border-line bg-white" aria-labelledby="order-invoices">
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-5 py-4 sm:px-8">
            <h2 id="order-invoices" class="font-bold text-dark-300">Invoice proyek ini</h2>
            <a href="{{ route('admin.invoices.create', ['order' => $order->id]) }}" class="btn-primary !min-h-10 !px-4 text-sm">+ Buat invoice</a>
        </header>
        @if ($order->invoices->isEmpty())
            <p class="px-5 py-6 text-sm text-dark-100 sm:px-8">Belum ada invoice untuk proyek ini. Invoice yang dibuat dari sini otomatis terisi klien, pekerjaan, dan harganya.</p>
        @else
            <ul class="divide-y divide-line">
                @foreach ($order->invoices as $invoice)
                    <li class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1 px-5 py-3 sm:px-8">
                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="num font-semibold text-dark-300 [overflow-wrap:anywhere] hover:underline">{{ $invoice->invoice_number }}</a>
                        <span class="num text-sm text-dark-100">@rp($invoice->paid_amount) / @rp($invoice->total)</span>
                        <span class="px-2 py-0.5 text-xs font-bold {{ $invoiceBadge[$invoice->status] }}">{{ $invoice->status_label }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endif

@if ($editing)
    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" data-confirm="Hapus proyek {{ $order->client_name }}? Tindakan ini tidak bisa dibatalkan." class="mt-4 max-w-3xl text-right">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Hapus proyek ini</button>
    </form>
@endif
@endsection
