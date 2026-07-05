<x-guest-layout>
    <x-auth-session-status class="mb-8" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-white/70 mb-2">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/20 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500/50 outline-none transition-all font-medium @error('email') border-red-500/50 @enderror"
                   placeholder="admin@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-white/70 mb-2">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/20 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500/50 outline-none transition-all font-medium @error('password') border-red-500/50 @enderror"
                   placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded-lg bg-white/5 border-white/10 text-primary-500 focus:ring-primary-500/30 focus:ring-offset-0">
                <span class="text-sm text-white/50 group-hover:text-white/70 transition-colors font-medium">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-primary-400 hover:text-primary-300 transition-colors">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all duration-200 text-sm tracking-wide">
            {{ __('Masuk') }}
        </button>
    </form>
</x-guest-layout>
