@extends('layouts.admin')

@section('title', __('Proyek — Admin TSI'))

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-dark-300 tracking-tight">{{ __('Proyek') }}</h1>
        <p class="text-dark-100/50 mt-1 font-medium">{{ __('Kelola portofolio proyek') }}</p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('Tambah Proyek') }}
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200/50 text-green-700 px-5 py-3.5 rounded-xl mb-6 flex items-center gap-3 font-medium text-sm shadow-sm">
        <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-dark-50/50 border-b border-dark-100/5">
                    <th class="text-left font-semibold text-dark-300 px-5 py-4">{{ __('Judul') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden sm:table-cell">{{ __('Klien') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden md:table-cell">{{ __('Status') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden lg:table-cell">{{ __('Tanggal') }}</th>
                    <th class="text-right font-semibold text-dark-300 px-5 py-4">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/5">
                @forelse($projects as $project)
                <tr class="hover:bg-dark-50/30 transition-colors">
                    <td class="px-5 py-4">
                        <span class="font-medium text-dark-300">{{ $project->trans('title') }}</span>
                    </td>
                    <td class="px-5 py-4 text-dark-100/60 hidden sm:table-cell">{{ $project->client->name ?? '—' }}</td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ ($project->status ?? 'completed') === 'completed' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ ($project->status ?? 'completed') === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                            {{ $project->status === 'in_progress' ? __('In Progress') : __('Completed') }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-dark-100/60 hidden lg:table-cell">{{ $project->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('portfolio.show', $project->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-dark-100/50 bg-dark-50 rounded-lg hover:bg-dark-100/10 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ __('Lihat') }}
                            </a>
                            <a href="{{ route('admin.projects.edit', $project) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                {{ __('Edit') }}
                            </a>
                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('{{ __('Yakin ingin menghapus proyek ini?') }}')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    {{ __('Hapus') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center">
                        <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <p class="text-dark-100/60 font-medium">{{ __('Belum ada proyek.') }}</p>
                        <a href="{{ route('admin.projects.create') }}" class="text-primary-500 font-semibold text-sm hover:underline mt-2 inline-block">{{ __('Tambah proyek pertama') }}</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection