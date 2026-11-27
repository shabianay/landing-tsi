@extends('layouts.admin')

@php
    use App\Models\Order;
    $editing = $order->exists;
    $date = fn ($field) => old($field, $order->{$field}?->format('Y-m-d'));
@endphp

@section('title', ($editing ? 'Ubah proyek' : 'Proyek baru') . ' — Admin TSI')

@section('content')
@include('admin.partials.pm-head', [
    'title' => $editing ? 'Ubah proyek' : 'Proyek baru',
    'subtitle' => 'Status terisi otomatis: ada tanggal DP berarti Dikerjakan, ada tanggal lunas berarti Selesai.',
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
            <input id="payment_date" type="date" name="payment_date" value="{{ $date('payment_date') }}" class="field">
        </div>
        <div>
            <label for="payment_lunas_date" class="field-label">Tanggal lunas</label>
            <input id="payment_lunas_date" type="date" name="payment_lunas_date" value="{{ $date('payment_lunas_date') }}" class="field">
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-line pt-6">
        <button type="submit" class="btn-primary">{{ $editing ? 'Simpan perubahan' : 'Simpan proyek' }}</button>
        <a href="{{ route('admin.orders.index', request('return') === 'list' ? ['view' => 'list'] : []) }}" class="btn-secondary">Batal</a>
        @if ($editing)
            <a href="{{ route('admin.invoices.create', ['client' => $order->order_client_id]) }}" class="ml-auto text-sm font-semibold text-primary-700 underline">Buat invoice untuk klien ini</a>
        @endif
    </div>
</form>

@if ($editing)
    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" data-confirm="Hapus proyek {{ $order->client_name }}? Tindakan ini tidak bisa dibatalkan." class="mt-4 max-w-3xl text-right">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Hapus proyek ini</button>
    </form>
@endif
@endsection
