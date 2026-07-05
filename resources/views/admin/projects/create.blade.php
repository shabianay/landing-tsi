@extends('layouts.admin')

@section('title', __('Tambah Proyek — Admin TSI'))

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.projects.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 hover:border-primary-500/30 hover:text-primary-600 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-dark-300 tracking-tight">{{ __('Tambah Proyek') }}</h1>
            <p class="text-dark-100/50 mt-1 font-medium text-sm">{{ __('Buat portofolio proyek baru') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-6 md:p-8">
        <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div>
                    <x-admin.locale-tabs :model="null" field="title" label="{{ __('Judul') }}" required="true" placeholder="{{ __('Judul proyek') }}" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="client_id" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Klien') }} <span class="text-red-500">*</span></label>
                    <select name="client_id" id="client_id" required class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium">
                        <option value="">{{ __('Pilih klien') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-admin.locale-tabs :model="null" field="description" label="{{ __('Deskripsi') }}" type="textarea" :rows="6" placeholder="{{ __('Deskripsi proyek') }}" />
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="website_url" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Website URL') }}</label>
                    <input type="url" name="website_url" id="website_url" value="{{ old('website_url') }}" placeholder="{{ __('https://example.com') }}" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30">
                    @error('website_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="image" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Gambar') }}</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                    @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="alt_text" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Alt Text (deskripsi gambar)') }}</label>
                    <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text') }}" placeholder="{{ __('Deskripsi singkat gambar untuk SEO & aksesibilitas') }}" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30">
                </div>
                <div>
                    <label for="og_image" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('OG Image (social sharing)') }}</label>
                    <input type="file" name="og_image" id="og_image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                    <p class="text-xs text-dark-100/40 mt-1.5">{{ __('1200x630px ideal untuk thumbnail pas dishare.') }}</p>
                    @error('og_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Status') }}</label>
                    <select name="status" id="status" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium">
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-dark-100/5">
                <button type="submit" class="px-6 py-3 bg-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl transition-all duration-200 text-sm">{{ __('Simpan') }}</button>
                <a href="{{ route('admin.projects.index') }}" class="px-6 py-3 text-dark-100/50 font-medium rounded-xl hover:bg-dark-50/80 transition-all duration-200 text-sm">{{ __('Batal') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection