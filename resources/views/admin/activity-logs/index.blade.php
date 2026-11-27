@extends('layouts.admin')

@section('title', 'Log Aktivitas — Admin TSI')

@php use App\Models\ActivityLog; @endphp

@section('content')
@include('admin.partials.pm-head', ['title' => 'Log Aktivitas', 'subtitle' => 'Catatan perubahan pada proyek klien, data klien, dan invoice.'])

<form method="GET" class="mb-5 flex flex-wrap gap-2">
    <select name="action" class="field !w-auto !py-2 text-sm" aria-label="Aksi">
        <option value="">Semua aksi</option>
        @foreach (ActivityLog::ACTION_LABELS as $value => $label)
            <option value="{{ $value }}" @selected(($filters['action'] ?? '') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="type" class="field !w-auto !py-2 text-sm" aria-label="Jenis data">
        <option value="">Semua data</option>
        @foreach ($types as $type)
            <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $type }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-ink !min-h-10 !px-4 text-sm">Terapkan</button>
</form>

<ol class="divide-y divide-line border border-line bg-white">
    @forelse ($logs as $log)
        <li class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-1 px-5 py-3 text-sm md:flex md:items-baseline">
            <time class="num shrink-0 text-dark-100 md:w-36" datetime="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</time>
            <span class="shrink-0 text-xs font-bold md:w-20 uppercase tracking-wide {{ $log->action === 'deleted' ? 'text-red-700' : ($log->action === 'created' ? 'text-emerald-700' : 'text-primary-700') }}">{{ ActivityLog::ACTION_LABELS[$log->action] ?? $log->action }}</span>
            <span class="col-span-2 min-w-0 flex-1 text-dark-200 [overflow-wrap:anywhere]">{{ $log->description }}</span>
            <span class="col-span-2 text-xs text-dark-100">{{ $log->user?->name ?? 'Sistem' }}</span>
        </li>
    @empty
        <li class="px-5 py-10 text-center text-dark-100">Belum ada aktivitas.</li>
    @endforelse
</ol>
<div class="mt-6">{{ $logs->links() }}</div>
@endsection
