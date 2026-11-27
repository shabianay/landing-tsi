@extends('layouts.admin')

@section('title', 'Impor SIM-Proyek — Admin TSI')

@section('content')
@include('admin.partials.pm-head', [
    'title' => 'Impor data SIM-Proyek',
    'subtitle' => 'Memindahkan klien, proyek, invoice, log aktivitas, dan pengaturan invoice dari aplikasi SIM-Proyek.',
])

@if ($result = session('import_result'))
    <div role="status" class="mb-6 max-w-2xl border border-primary-300 bg-primary-50 p-5 text-dark-300">
        <p class="font-semibold">Impor selesai.</p>
        <ul class="num mt-2 grid grid-cols-2 gap-x-6 gap-y-1 text-sm sm:grid-cols-3">
            <li>{{ $result['clients'] }} klien</li>
            <li>{{ $result['orders'] }} proyek</li>
            <li>{{ $result['invoices'] }} invoice ({{ $result['items'] }} item)</li>
            <li>{{ $result['logs'] }} log aktivitas</li>
            <li>{{ $result['settings'] }} pengaturan</li>
            <li>{{ $result['skipped'] }} dilewati (sudah ada)</li>
        </ul>
        <p class="mt-3 text-sm"><a href="{{ route('admin.orders.index') }}" class="font-semibold underline">Buka Proyek Klien</a> · <a href="{{ route('admin.invoices.index') }}" class="font-semibold underline">Buka Invoice</a></p>
    </div>
@endif

<form method="POST" action="{{ route('admin.sim-import.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-5 border border-line bg-white p-5 sm:p-8">
    @csrf
    <div>
        <label for="file" class="field-label">File ekspor (.json)</label>
        <input id="file" type="file" name="file" accept=".json,application/json" required class="field !py-2" @error('file') aria-invalid="true" @enderror>
        @error('file')<p class="field-error">{{ $message }}</p>@enderror
    </div>
    <ul class="list-inside list-disc space-y-1 text-sm text-dark-100">
        <li>Aman dijalankan lebih dari sekali: data yang sudah ada dilewati, tidak digandakan.</li>
        <li>Tanggal dibuat dari SIM-Proyek tetap dipertahankan.</li>
        <li>Pengaturan invoice hanya diisi bila masih kosong di sini.</li>
    </ul>
    <button type="submit" class="btn-primary">Impor sekarang</button>
</form>
@endsection
