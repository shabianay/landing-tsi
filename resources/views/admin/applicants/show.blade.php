@extends('layouts.admin')

@section('title', $applicant->full_name.' — Pelamar Sales')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.applicants.index') }}" class="text-sm font-semibold text-primary-600 hover:underline">← Kembali ke daftar pelamar</a>
    <div class="mt-4 flex flex-wrap items-start justify-between gap-4">
        <div><h1 class="text-2xl font-bold text-dark-300 md:text-3xl">{{ $applicant->full_name }}</h1><p class="mt-1 text-sm text-dark-100/50">Mendaftar {{ $applicant->created_at->format('d/m/Y H:i') }}</p></div>
        <a href="https://wa.me/{{ $applicant->whatsapp }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700">Chat WhatsApp</a>
    </div>
</div>

@if (session('success'))<div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-3.5 text-sm font-medium text-green-700">{{ session('success') }}</div>@endif

<div class="grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
    <section class="rounded-2xl border border-dark-100/5 bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-lg font-bold text-dark-300">Jawaban pelamar</h2>
        <dl class="mt-5 grid gap-5 sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Nomor WhatsApp</dt><dd class="mt-1 font-medium text-dark-300">{{ $applicant->whatsapp }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Domisili</dt><dd class="mt-1 font-medium text-dark-300">{{ $applicant->domicile }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Tanggal lahir</dt><dd class="mt-1 font-medium text-dark-300">{{ $applicant->birth_date?->format('d/m/Y') ?? 'Belum tercatat' }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Kegiatan saat ini</dt><dd class="mt-1 font-medium text-dark-300">{{ \App\Models\Applicant::ACTIVITIES[$applicant->current_activity] }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Persetujuan komisi</dt><dd class="mt-1 font-medium text-dark-300">{{ $applicant->agreed_commission ? 'Disetujui' : 'Belum disetujui' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Pengalaman jualan atau relasi</dt><dd class="mt-1 whitespace-pre-line text-dark-300">{{ $applicant->experience ?: '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Alasan tertarik</dt><dd class="mt-1 whitespace-pre-line text-dark-300">{{ $applicant->motivation }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-dark-100/50">Media sosial</dt><dd class="mt-1"><a href="{{ $applicant->social_link }}" target="_blank" rel="noopener noreferrer" class="break-all font-medium text-primary-600 underline">{{ $applicant->social_link }}</a></dd></div>
        </dl>
    </section>

    <div class="space-y-6">
        @if ($applicant->partner_code)
            <section class="rounded-2xl border border-primary-100 bg-primary-50/50 p-5 shadow-sm sm:p-7">
                <h2 class="text-lg font-bold text-dark-300">Kode partner &amp; pencatatan klien</h2>
                <p class="mt-2 text-2xl font-bold text-primary-700">{{ $applicant->partner_code }}</p>
                <p class="mt-3 text-sm leading-6 text-dark-100/70">Mitra mengirim data calon klien ke WhatsApp The Solution ID sebelum menawarkan layanan. Minta klien menyebutkan kode partner ini saat menghubungi TSI. Pencatatan lead di dashboard termasuk tahap 2.</p>
                @php($contactWhatsapp = preg_replace('/\D+/', '', (string) \App\Models\Setting::get('contact_whatsapp', '')))
                @if ($contactWhatsapp !== '')
                    <a href="https://wa.me/{{ $contactWhatsapp }}?text={{ urlencode('Halo The Solution ID, saya ingin melaporkan calon klien. Kode partner: '.$applicant->partner_code) }}"
                        target="_blank" rel="noopener noreferrer"
                        class="mt-4 inline-flex rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700">WhatsApp The Solution ID</a>
                @endif
            </section>
        @endif

        <form method="POST" action="{{ route('admin.applicants.update', $applicant) }}" class="rounded-2xl border border-dark-100/5 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-bold text-dark-300">Status dan catatan internal</h2>
            <label for="status" class="mb-1.5 mt-5 block text-sm font-semibold text-dark-300">Status</label>
            <select id="status" name="status" required class="w-full rounded-xl border-slate-200 text-sm">
                @foreach (\App\Models\Applicant::STATUSES as $value => $label)<option value="{{ $value }}" @selected(old('status', $applicant->status) === $value)>{{ $label }}</option>@endforeach
            </select>
            @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            <label for="agreement_version" class="mb-1.5 mt-5 block text-sm font-semibold text-dark-300">Versi perjanjian <span class="font-normal text-dark-100/50">(wajib saat menandai ditandatangani)</span></label>
            <input id="agreement_version" name="agreement_version" value="{{ old('agreement_version', $applicant->agreement_version) }}" maxlength="20" class="w-full rounded-xl border-slate-200 text-sm">
            @error('agreement_version')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            @if ($applicant->agreement_signed_at)
                <p class="mt-2 text-xs text-dark-100/60">Disetujui {{ $applicant->agreement_signed_at->format('d/m/Y H:i') }}</p>
            @endif
            <label for="admin_notes" class="mb-1.5 mt-5 block text-sm font-semibold text-dark-300">Catatan admin</label>
            <textarea id="admin_notes" name="admin_notes" rows="6" maxlength="10000" class="w-full rounded-xl border-slate-200 text-sm">{{ old('admin_notes', $applicant->admin_notes) }}</textarea>
            @error('admin_notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            <button class="mt-4 w-full rounded-xl bg-primary-500 px-4 py-3 text-sm font-semibold text-white hover:bg-primary-600">Simpan perubahan</button>
        </form>

        <section class="rounded-2xl border border-dark-100/5 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-lg font-bold text-dark-300">Riwayat status</h2>
            <ol class="mt-4 space-y-4">
                @foreach ($applicant->statusLogs as $log)
                    <li class="border-l-2 border-primary-200 pl-4">
                        <p class="text-sm font-semibold text-dark-300">{{ $log->from_status ? \App\Models\Applicant::STATUSES[$log->from_status] : 'Pendaftaran diterima' }}{{ $log->from_status ? ' → '.\App\Models\Applicant::STATUSES[$log->to_status] : '' }}</p>
                        <time class="mt-1 block text-xs text-dark-100/50">{{ $log->created_at->format('d/m/Y H:i') }}</time>
                    </li>
                @endforeach
            </ol>
        </section>

        <form method="POST" action="{{ route('admin.applicants.destroy', $applicant) }}" data-confirm="Sembunyikan pelamar ini dari daftar? Data tetap disimpan." class="text-right">
            @csrf
            @method('DELETE')
            <button class="text-sm font-medium text-red-600 hover:underline">Sembunyikan pelamar</button>
        </form>
    </div>
</div>
@endsection
