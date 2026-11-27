{{-- An Android app drawn in the site's block language (from the "Aplikasi Android" poster). Decorative. --}}
<div {{ $attributes->merge(['class' => 'relative mx-auto w-full max-w-[17rem]']) }} aria-hidden="true">
    {{-- Blue block behind the phone, like the logo's blue square. --}}
    <div class="absolute -right-6 bottom-10 top-16 w-2/3 bg-primary-500"></div>

    <div class="relative rounded-[2.25rem] border-[7px] border-dark-300 bg-white p-3 pt-6 shadow-2xl">
        <div class="absolute left-1/2 top-2 h-1.5 w-14 -translate-x-1/2 rounded-full bg-dark-300"></div>
        <div class="flex items-center gap-2 px-1">
            <span class="h-4 w-4 bg-primary-500"></span>
            <span class="text-xs font-bold text-dark-300">Bisnismu</span>
        </div>
        <div class="mt-3 bg-primary-600 p-3 text-white">
            <p class="text-[0.7rem] font-bold leading-tight">{{ __('Pesan lebih mudah lewat aplikasi') }}</p>
            <span class="mt-2 inline-block bg-white px-2 py-0.5 text-[0.6rem] font-bold text-primary-700">{{ __('Mulai') }}</span>
        </div>
        <div class="mt-2 grid grid-cols-2 gap-2">
            <span class="aspect-[4/3] bg-primary-100"></span>
            <span class="aspect-[4/3] bg-primary-100"></span>
            <span class="aspect-[4/3] bg-primary-100"></span>
            <span class="aspect-[4/3] bg-dark-50"></span>
        </div>
        <div class="mt-2 h-2 w-2/3 bg-dark-50"></div>
        <div class="mt-4 flex justify-around border-t border-line pt-2">
            <span class="h-3 w-3 bg-primary-500"></span><span class="h-3 w-3 bg-dark-100/30"></span><span class="h-3 w-3 bg-dark-100/30"></span>
        </div>
    </div>

    {{-- Floating chips from the poster --}}
    <span class="absolute -left-5 -top-4 flex items-center gap-1.5 border border-line bg-white px-3 py-1.5 text-xs font-bold text-dark-300 shadow-md">
        <svg class="h-3.5 w-3.5 text-primary-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 20h14"/></svg>File APK
    </span>
    <span class="absolute -right-5 top-1/2 flex items-center gap-1.5 border border-line bg-white px-3 py-1.5 text-xs font-bold text-dark-300 shadow-md">
        <svg class="h-3.5 w-3.5 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M7 4v16l13-8z"/></svg>Play Store
    </span>
    <span class="absolute -left-6 bottom-12 flex items-center gap-1.5 border border-line bg-white px-3 py-1.5 text-xs font-bold text-dark-300 shadow-md">
        <svg class="h-3.5 w-3.5 text-primary-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4zM4 9h16M9 9v11"/></svg>UI Custom
    </span>
</div>
