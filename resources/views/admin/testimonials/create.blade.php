@extends('layouts.admin')

@section('title', __('Tambah Testimonial — Admin TSI'))

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.testimonials.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 hover:border-primary-500/30 hover:text-primary-600 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-dark-300 tracking-tight">{{ __('Tambah Testimonial') }}</h1>
            <p class="text-dark-100/50 mt-1 font-medium text-sm">{{ __('Buat testimonial klien baru') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-6 md:p-8">
        <form method="POST" action="{{ route('admin.testimonials.store') }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <x-admin.locale-tabs :model="null" field="client_name" label="{{ __('Nama Klien') }}" required="true" placeholder="{{ __('Nama Klien') }}" />
                    @error('client_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-admin.locale-tabs :model="null" field="company_name" label="{{ __('Perusahaan') }}" placeholder="{{ __('Perusahaan') }}" />
                    @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rating" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Rating') }} <span class="text-red-500">*</span></label>
                    <div class="flex gap-1" x-data="{ rating: {{ old('rating', 5) }} }">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" @click="rating = {{ $i }}" class="p-0.5 transition-colors" :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-dark-100/10'">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                        @endfor
                        <input type="hidden" name="rating" x-model="rating" value="{{ old('rating', 5) }}">
                    </div>
                    @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-admin.locale-tabs :model="null" field="content" label="{{ __('Konten Testimonial') }}" type="textarea" :rows="6" required="true" placeholder="{{ __('Tulis testimonial...') }}" />
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-dark-100/5">
                <button type="submit" class="px-6 py-3 bg-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl transition-all duration-200 text-sm">{{ __('Simpan') }}</button>
                <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-3 text-dark-100/50 font-medium rounded-xl hover:bg-dark-50/80 transition-all duration-200 text-sm">{{ __('Batal') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection