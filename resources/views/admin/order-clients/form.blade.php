@extends('layouts.admin')

@php $editing = $client->exists; @endphp

@section('title', ($editing ? 'Ubah klien' : 'Klien baru') . ' — Admin TSI')

@section('content')
@include('admin.partials.pm-head', ['title' => $editing ? 'Ubah klien' : 'Klien baru'])

<form method="POST" action="{{ $editing ? route('admin.order-clients.update', $client) : route('admin.order-clients.store', ['return' => request('return')]) }}" class="max-w-3xl space-y-6 border border-line bg-white p-5 sm:p-8">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="name" class="field-label">Nama *</label>
            <input id="name" name="name" value="{{ old('name', $client->name) }}" required maxlength="255" autocomplete="off" class="field" @error('name') aria-invalid="true" @enderror>
        </div>
        <div>
            <label for="company" class="field-label">Perusahaan / instansi</label>
            <input id="company" name="company" value="{{ old('company', $client->company) }}" maxlength="255" class="field">
        </div>
        <div>
            <label for="phone" class="field-label">Nomor HP / WhatsApp</label>
            <input id="phone" type="tel" inputmode="tel" name="phone" value="{{ old('phone', $client->phone) }}" maxlength="30" class="field num" @error('phone') aria-invalid="true" @enderror>
        </div>
        <div>
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $client->email) }}" maxlength="255" class="field" @error('email') aria-invalid="true" @enderror>
        </div>
    </div>
    <div>
        <label for="address" class="field-label">Alamat</label>
        <textarea id="address" name="address" rows="2" maxlength="2000" class="field">{{ old('address', $client->address) }}</textarea>
        <p class="field-hint">Dicetak di invoice sebagai alamat penagihan.</p>
    </div>
    <div>
        <label for="notes" class="field-label">Catatan</label>
        <textarea id="notes" name="notes" rows="3" maxlength="5000" class="field">{{ old('notes', $client->notes) }}</textarea>
    </div>

    <div class="flex flex-wrap gap-3 border-t border-line pt-6">
        <button type="submit" class="btn-primary">{{ $editing ? 'Simpan perubahan' : 'Simpan klien' }}</button>
        <a href="{{ request('return') === 'order' ? route('admin.orders.create') : route('admin.order-clients.index') }}" class="btn-secondary">Batal</a>
    </div>
</form>
@endsection
