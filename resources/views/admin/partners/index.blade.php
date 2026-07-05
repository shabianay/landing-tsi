@extends('layouts.admin')

@section('title', __('Partner — Admin TSI'))

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-dark-300 tracking-tight">{{ __('Partner') }}</h1>
        <p class="text-dark-100/50 mt-1 font-medium">{{ __('Kelola data partner') }}</p>
    </div>
    <a href="{{ route('admin.partners.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('Tambah Partner') }}
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
                    <th class="text-left font-semibold text-dark-300 px-5 py-4">{{ __('Logo') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4">{{ __('Nama') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden sm:table-cell">{{ __('Website') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden md:table-cell">{{ __('Sort Order') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden lg:table-cell">{{ __('Status') }}</th>
                    <th class="text-right font-semibold text-dark-300 px-5 py-4">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/5">
                @forelse($partners as $partner)
                <tr class="hover:bg-dark-50/30 transition-colors">
                    <td class="px-5 py-4">
                        @if($partner->logo)
                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->trans('name') }}" class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center">
                                <span class="text-sm font-bold text-primary-500">{{ strtoupper(substr($partner->trans('name'), 0, 2)) }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="font-medium text-dark-300">{{ $partner->trans('name') }}</span>
                        @if($partner->trans('description'))
                            <p class="text-xs text-dark-100/50 mt-0.5">{{ $partner->trans('description') }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-dark-100/60 hidden sm:table-cell max-w-[200px] truncate">{{ $partner->website_url ?? '—' }}</td>
                    <td class="px-5 py-4 text-dark-100/60 hidden md:table-cell">{{ $partner->sort_order }}</td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $partner->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $partner->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $partner->is_active ? __('Aktif') : __('Nonaktif') }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.partners.edit', $partner) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                {{ __('Edit') }}
                            </a>
                            <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" onsubmit="return confirm('{{ __('Yakin ingin menghapus partner ini?') }}')" class="inline">
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
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="text-dark-100/60 font-medium">{{ __('Belum ada partner.') }}</p>
                        <a href="{{ route('admin.partners.create') }}" class="text-primary-500 font-semibold text-sm hover:underline mt-2 inline-block">{{ __('Tambah partner pertama') }}</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
