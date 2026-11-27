@extends('layouts.admin')

@section('title', 'Akun Saya — Admin TSI')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-dark-300 md:text-3xl">Akun Saya</h1>
    <p class="mt-1 text-sm text-dark-100/50">Ubah nama, email login, dan password admin.</p>
</div>

@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-3.5 text-sm font-medium text-green-700">{{ session('success') }}</div>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <section class="rounded-2xl border border-dark-100/5 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="profile-heading">
        <h2 id="profile-heading" class="text-lg font-bold text-dark-300">Nama &amp; email</h2>
        <p class="mt-1 text-sm text-dark-100/60">Email ini dipakai untuk login ke panel admin.</p>

        <form method="POST" action="{{ route('admin.account.profile.update') }}" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-dark-300">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name"
                       class="w-full rounded-xl border-slate-200 text-sm" aria-invalid="{{ $errors->profile->has('name') ? 'true' : 'false' }}">
                @error('name', 'profile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-dark-300">Email login</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email"
                       class="w-full rounded-xl border-slate-200 text-sm" aria-invalid="{{ $errors->profile->has('email') ? 'true' : 'false' }}">
                @error('email', 'profile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="profile_current_password" class="mb-1.5 block text-sm font-semibold text-dark-300">Password saat ini</label>
                <input id="profile_current_password" name="current_password" type="password" required autocomplete="current-password"
                       class="w-full rounded-xl border-slate-200 text-sm" aria-describedby="profile-password-hint" aria-invalid="{{ $errors->profile->has('current_password') ? 'true' : 'false' }}">
                <p id="profile-password-hint" class="mt-1 text-xs text-dark-100/50">Wajib diisi untuk mengonfirmasi perubahan.</p>
                @error('current_password', 'profile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="rounded-xl bg-primary-500 px-5 py-3 text-sm font-semibold text-white hover:bg-primary-600">Simpan nama &amp; email</button>
        </form>
    </section>

    <section class="rounded-2xl border border-dark-100/5 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="password-heading">
        <h2 id="password-heading" class="text-lg font-bold text-dark-300">Ganti password</h2>
        <p class="mt-1 text-sm text-dark-100/60">Setelah diganti, semua perangkat lain yang login dengan akun ini akan dikeluarkan.</p>

        <form method="POST" action="{{ route('admin.account.password.update') }}" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="password_current_password" class="mb-1.5 block text-sm font-semibold text-dark-300">Password saat ini</label>
                <input id="password_current_password" name="current_password" type="password" required autocomplete="current-password"
                       class="w-full rounded-xl border-slate-200 text-sm" aria-invalid="{{ $errors->password->has('current_password') ? 'true' : 'false' }}">
                @error('current_password', 'password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-semibold text-dark-300">Password baru</label>
                <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password"
                       class="w-full rounded-xl border-slate-200 text-sm" aria-describedby="password-rules" aria-invalid="{{ $errors->password->has('password') ? 'true' : 'false' }}">
                <p id="password-rules" class="mt-1 text-xs text-dark-100/50">Minimal 12 karakter, berisi huruf besar, huruf kecil, dan angka.</p>
                @error('password', 'password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-dark-300">Ulangi password baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password"
                       class="w-full rounded-xl border-slate-200 text-sm">
            </div>

            <button type="submit" class="rounded-xl bg-dark-300 px-5 py-3 text-sm font-semibold text-white hover:bg-dark-200">Ganti password</button>
        </form>
    </section>
</div>
@endsection
