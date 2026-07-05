@extends('layouts.admin')

@section('title', __('Detail Pesan — Admin TSI'))

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.contacts.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 hover:border-primary-500/30 hover:text-primary-600 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-dark-300 tracking-tight">{{ __('Detail Pesan') }}</h1>
            <p class="text-dark-100/50 mt-1 font-medium text-sm">{{ __('Dari') }} {{ $contact->name }}</p>
        </div>
    </div>

    @if(!$contact->is_read)
        <div class="bg-primary-50 border border-primary-200/50 text-primary-700 px-5 py-3.5 rounded-xl mb-6 flex items-center gap-3 font-medium text-sm shadow-sm">
            <svg class="w-5 h-5 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            {{ __('Pesan ini belum dibaca.') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-6 md:p-8 space-y-6">
        <div class="grid sm:grid-cols-2 gap-6">
            <div>
                <span class="text-xs font-semibold text-dark-100/40 uppercase tracking-wider">{{ __('Nama') }}</span>
                <p class="font-medium text-dark-300 mt-1">{{ $contact->name }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold text-dark-100/40 uppercase tracking-wider">{{ __('Email') }}</span>
                <p class="font-medium text-dark-300 mt-1">
                    <a href="mailto:{{ $contact->email }}" class="text-primary-500 hover:underline">{{ $contact->email }}</a>
                </p>
            </div>
            @if($contact->subject)
            <div>
                <span class="text-xs font-semibold text-dark-100/40 uppercase tracking-wider">{{ __('Subjek') }}</span>
                <p class="font-medium text-dark-300 mt-1">{{ $contact->subject }}</p>
            </div>
            @endif
            <div>
                <span class="text-xs font-semibold text-dark-100/40 uppercase tracking-wider">{{ __('Tanggal') }}</span>
                <p class="font-medium text-dark-300 mt-1">{{ $contact->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold text-dark-100/40 uppercase tracking-wider">{{ __('Status') }}</span>
                <p class="mt-1.5">
                    @if($contact->is_read)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-full border border-green-200/50">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Sudah dibaca') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-primary-700 bg-primary-50 rounded-full border border-primary-200/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                            {{ __('Belum dibaca') }}
                        </span>
                    @endif
                </p>
            </div>
        </div>
        <div class="pt-4 border-t border-dark-100/5">
            <span class="text-xs font-semibold text-dark-100/40 uppercase tracking-wider">{{ __('Pesan') }}</span>
            <p class="font-medium text-dark-300 mt-2 leading-relaxed whitespace-pre-wrap">{{ $contact->message }}</p>
        </div>
        <div class="pt-4 border-t border-dark-100/5 flex justify-end">
            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('{{ __('Yakin ingin menghapus pesan ini?') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    {{ __('Hapus Pesan') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection