@extends('layouts.admin')

@section('title', 'Pelamar Sales — Admin TSI')

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-dark-300 md:text-3xl">Pelamar Sales</h1>
        <p class="mt-1 font-medium text-dark-100">Kelola pendaftaran partner freelance.</p>
    </div>
    <a href="{{ route('admin.applicants.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-500/20 transition hover:bg-primary-700">
        Ekspor CSV
    </a>
</div>

@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-3.5 text-sm font-medium text-green-700">{{ session('success') }}</div>
@endif

<div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm"><p class="text-sm text-dark-100">Total pelamar</p><p class="mt-1 text-2xl font-bold text-dark-300">{{ $totalApplicants }}</p></div>
    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm"><p class="text-sm text-dark-100">7 hari terakhir</p><p class="mt-1 text-2xl font-bold text-dark-300">{{ $newThisWeek }}</p></div>
    @foreach (\App\Models\Applicant::STATUSES as $status => $label)
        <div class="rounded-2xl border border-line bg-white p-5 shadow-sm"><p class="text-sm text-dark-100">{{ $label }}</p><p class="mt-1 text-2xl font-bold text-dark-300">{{ $statusCounts[$status] ?? 0 }}</p></div>
    @endforeach
</div>

<form method="GET" action="{{ route('admin.applicants.index') }}" class="mb-6 grid gap-3 rounded-2xl border border-line bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
    <label class="sm:col-span-2"><span class="mb-1 block text-xs font-semibold text-dark-100">Nama atau WhatsApp</span><input name="q" value="{{ $filters['q'] ?? '' }}" class="w-full rounded-lg border-slate-200 text-sm" placeholder="Cari pelamar"></label>
    <label><span class="mb-1 block text-xs font-semibold text-dark-100">Status</span><select name="status" class="w-full rounded-lg border-slate-200 text-sm"><option value="">Semua status</option>@foreach (\App\Models\Applicant::STATUSES as $value => $label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>
    <label><span class="mb-1 block text-xs font-semibold text-dark-100">Domisili</span><input name="domicile" value="{{ $filters['domicile'] ?? '' }}" class="w-full rounded-lg border-slate-200 text-sm" placeholder="Kota/kecamatan"></label>
    <label><span class="mb-1 block text-xs font-semibold text-dark-100">Dari tanggal</span><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="w-full rounded-lg border-slate-200 text-sm"></label>
    <label><span class="mb-1 block text-xs font-semibold text-dark-100">Sampai tanggal</span><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="w-full rounded-lg border-slate-200 text-sm"></label>
    <div class="flex items-end gap-2 lg:col-span-6">
        <button class="rounded-lg bg-dark-300 px-4 py-2 text-sm font-semibold text-white hover:bg-dark-200">Terapkan filter</button>
        <a href="{{ route('admin.applicants.index') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-dark-100 hover:bg-dark-50">Reset</a>
    </div>
</form>

<div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-dark-50/50 text-dark-100"><tr><th class="px-5 py-4 font-semibold">Nama</th><th class="px-5 py-4 font-semibold">WhatsApp</th><th class="px-5 py-4 font-semibold">Domisili</th><th class="px-5 py-4 font-semibold">Kegiatan</th><th class="px-5 py-4 font-semibold">Status</th><th class="px-5 py-4 font-semibold">Tanggal daftar</th></tr></thead>
            <tbody class="divide-y divide-line">
                @forelse ($applicants as $applicant)
                    <tr class="hover:bg-dark-50/30">
                        <td class="px-5 py-4"><a href="{{ route('admin.applicants.show', $applicant) }}" class="font-semibold text-primary-600 hover:underline">{{ $applicant->full_name }}</a></td>
                        <td class="whitespace-nowrap px-5 py-4 text-dark-100">{{ $applicant->whatsapp }}</td>
                        <td class="px-5 py-4">{{ $applicant->domicile }}</td>
                        <td class="px-5 py-4">{{ \App\Models\Applicant::ACTIVITIES[$applicant->current_activity] }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ \App\Models\Applicant::STATUSES[$applicant->status] }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-dark-100">{{ $applicant->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-14 text-center text-dark-100">Belum ada pelamar yang sesuai dengan filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($applicants->hasPages())<div class="border-t border-line px-5 py-4">{{ $applicants->links() }}</div>@endif
</div>
@endsection
