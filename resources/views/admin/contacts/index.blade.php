@extends('layouts.admin')

@section('title', __('Pesan — Admin TSI'))

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-dark-300 tracking-tight">{{ __('Pesan Masuk') }}</h1>
        <p class="text-dark-100/50 mt-1 font-medium">{{ __('Pesan dari formulir kontak') }}</p>
    </div>
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
                    <th class="text-left font-semibold text-dark-300 px-5 py-4">{{ __('Nama') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden sm:table-cell">{{ __('Email') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden md:table-cell">{{ __('Subjek') }}</th>
                    <th class="text-left font-semibold text-dark-300 px-5 py-4 hidden lg:table-cell">{{ __('Tanggal') }}</th>
                    <th class="text-right font-semibold text-dark-300 px-5 py-4">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/5">
                @forelse($contacts as $contact)
                <tr class="hover:bg-dark-50/30 transition-colors {{ !$contact->is_read ? 'bg-primary-50/40' : '' }}">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2.5">
                            @if(!$contact->is_read)
                                <span class="w-2 h-2 rounded-full bg-primary-500 shrink-0"></span>
                            @endif
                            <span class="{{ !$contact->is_read ? 'font-semibold' : 'font-medium' }} text-dark-300">{{ $contact->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-dark-100/60 hidden sm:table-cell">{{ $contact->email }}</td>
                    <td class="px-5 py-4 text-dark-100/60 hidden md:table-cell max-w-[200px] truncate">{{ $contact->subject ?? '—' }}</td>
                    <td class="px-5 py-4 text-dark-100/60 hidden lg:table-cell">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ __('Detail') }}
                            </a>
                            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('{{ __('Yakin ingin menghapus pesan ini?') }}')" class="inline">
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
                            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <p class="text-dark-100/60 font-medium">{{ __('Belum ada pesan.') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection