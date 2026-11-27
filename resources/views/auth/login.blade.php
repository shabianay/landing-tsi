<x-guest-layout>
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="field-label">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" inputmode="email"
                   class="field" @error('email') aria-invalid="true" @enderror>
            <x-input-error :messages="$errors->get('email')" class="field-error" />
        </div>

        <div>
            <label for="password" class="field-label">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="field" @error('password') aria-invalid="true" @enderror>
            <x-input-error :messages="$errors->get('password')" class="field-error" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5">
                <input id="remember_me" type="checkbox" name="remember" class="h-5 w-5 rounded-sm border-dark-100/50 text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-200">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-primary-700 underline decoration-primary-300 hover:text-dark-300">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="btn-ink w-full">{{ __('Masuk') }}</button>
    </form>
</x-guest-layout>
