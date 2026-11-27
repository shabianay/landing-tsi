@extends('layouts.admin')

@section('title', 'Catat Calon Klien — Admin TSI')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.partner-sales.index') }}" class="text-sm font-semibold text-primary-600 hover:underline">← Kembali ke pencatatan klien</a>
    <h1 class="mt-4 text-2xl font-bold text-dark-300">Catat calon klien</h1>
    <p class="mt-1 text-sm leading-6 text-dark-100">Catat laporan mitra melalui WhatsApp sebelum mitra menawarkan layanan. Klien perlu menyebutkan kode partner saat menghubungi TSI.</p>
</div>

<form method="POST" action="{{ route('admin.partner-sales.store') }}" class="max-w-2xl space-y-5 rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-7">
    @csrf
    @if ($errors->any())
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    <label class="block text-sm font-semibold text-dark-300">Partner aktif *
        <select name="applicant_id" required class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
            <option value="">Pilih partner</option>
            @foreach ($partners as $partner)
                <option value="{{ $partner->id }}" @selected(old('applicant_id') == $partner->id)>{{ $partner->partner_code }} — {{ $partner->full_name }}</option>
            @endforeach
        </select>
    </label>
    <label class="block text-sm font-semibold text-dark-300">Nama calon klien *
        <input name="full_name" value="{{ old('full_name') }}" required maxlength="150" class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
    </label>
    <label class="block text-sm font-semibold text-dark-300">WhatsApp calon klien *
        <input name="whatsapp" value="{{ old('whatsapp') }}" type="tel" inputmode="tel" required maxlength="20" placeholder="08xxxxxxxxxx" class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
    </label>
    <label class="block text-sm font-semibold text-dark-300">Layanan yang ditawarkan *
        <input name="service" value="{{ old('service') }}" required maxlength="150" placeholder="Contoh: Website company profile" class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
    </label>
    <label class="block text-sm font-semibold text-dark-300">Waktu laporan diterima *
        <input name="reported_at" type="datetime-local" value="{{ old('reported_at', now()->format('Y-m-d\TH:i')) }}" required class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">
    </label>
    <label class="block text-sm font-semibold text-dark-300">Catatan
        <textarea name="notes" rows="3" maxlength="5000" class="mt-1.5 w-full rounded-xl border-slate-200 text-sm">{{ old('notes') }}</textarea>
    </label>
    @if ($partners->isEmpty())
        <p class="rounded-xl bg-amber-50 p-4 text-sm text-amber-800">Belum ada partner aktif berkode. Aktifkan partner pada halaman Pelamar Sales terlebih dahulu.</p>
    @endif
    <button @disabled($partners->isEmpty()) class="w-full rounded-xl bg-primary-600 px-4 py-3 text-sm font-semibold text-white hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-50">Simpan calon klien</button>
</form>
@endsection
