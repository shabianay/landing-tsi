@props(['screens', 'interactive' => false])
{{-- A website drawn in the site's block language. Demo content; the parent's Alpine `web` key picks the screen. --}}
<div {{ $attributes->merge(['class' => 'relative mx-auto w-full max-w-[34rem]']) }} aria-hidden="true">
    <div class="absolute -right-4 -bottom-4 left-10 top-10 bg-primary-500 sm:-right-6 sm:-bottom-6"></div>

    <div class="relative h-[19rem] overflow-hidden border-[3px] border-dark-300 bg-white shadow-2xl sm:h-[21rem]">
        @foreach ($screens as $key => $screen)
            <div class="absolute inset-0 flex flex-col"
                 @if ($interactive)
                     x-show="web === '{{ $key }}'" @if(! $loop->first) x-cloak @endif
                     x-transition:enter="transition duration-300 ease-out-expo" x-transition:enter-start="translate-y-3 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                 @elseif (! $loop->first)
                     hidden
                 @endif>
                {{-- browser chrome --}}
                <div class="flex items-center gap-2 border-b border-line bg-dark-50 px-3 py-2">
                    <span class="flex gap-1"><span class="h-2 w-2 bg-dark-100/30"></span><span class="h-2 w-2 bg-dark-100/30"></span><span class="h-2 w-2 bg-dark-100/30"></span></span>
                    <span class="flex min-w-0 flex-1 items-center gap-1.5 bg-white px-2 py-0.5 text-[0.65rem] text-dark-100">
                        <svg class="h-2.5 w-2.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M7 11V8a5 5 0 0 1 10 0v3M5 11h14v10H5z"/></svg>
                        <span class="truncate">{{ $screen['url'] }}</span>
                    </span>
                </div>
                {{-- site nav --}}
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="flex items-center gap-1.5 text-[0.7rem] font-bold text-dark-300"><span class="h-3 w-3 bg-primary-500"></span>{{ $screen['brand'] }}</span>
                    <span class="hidden gap-3 text-[0.6rem] text-dark-100 sm:flex"><span>Beranda</span><span>Tentang</span><span>Kontak</span></span>
                </div>
                {{-- hero --}}
                <div class="mx-4 bg-dark-300 px-4 py-5 text-white">
                    <p class="max-w-[22ch] font-display text-lg font-extrabold leading-tight sm:text-xl">{{ $screen['headline'] }}</p>
                    <p class="mt-1.5 text-[0.65rem] text-white/70">{{ $screen['sub'] }}</p>
                    <span class="mt-3 inline-block bg-primary-500 px-2.5 py-1 text-[0.65rem] font-bold text-dark-300">{{ $screen['cta'] }}</span>
                </div>
                {{-- tiles --}}
                <div class="mx-4 mt-3 grid flex-1 grid-cols-3 gap-2 pb-4">
                    @foreach ($screen['tiles'] as $tile)
                        <div class="flex flex-col justify-end bg-primary-50 p-2">
                            <span class="mb-auto h-1.5 w-1/2 bg-primary-200"></span>
                            <span class="text-[0.6rem] font-semibold text-dark-300">{{ $tile }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
