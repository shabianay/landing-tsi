@props(['popups'])
@if($popups->isNotEmpty())
<div x-data="{
    open: false,
    active: 0,
    total: {{ $popups->count() }},
    autoTimer: null,
    init() {
        let delay = {{ $popups->first()->display_delay * 1000 }};
        setTimeout(() => { this.open = true; this.startAutoSlide(); }, delay);
    },
    startAutoSlide() {
        if (this.total <= 1) return;
        this.autoTimer = setInterval(() => {
            this.next();
        }, 5000);
    },
    stopAutoSlide() {
        if (this.autoTimer) clearInterval(this.autoTimer);
    },
    next() {
        this.active = (this.active + 1) % this.total;
    },
    prev() {
        this.active = (this.active - 1 + this.total) % this.total;
    },
    goTo(index) {
        this.active = index;
        this.stopAutoSlide();
        this.startAutoSlide();
    }
}"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="open = false"
     class="fixed inset-0 z-[9998] flex items-center justify-center p-4 sm:p-6"
     role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-dark-300/60 backdrop-blur-sm" @click="open = false"></div>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         @click.away="open = false"
         class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden">
        <button type="button" @click="open = false" class="absolute top-4 right-4 z-10 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center text-dark-100/50 hover:text-dark-300 hover:bg-white transition-all shadow-lg" aria-label="Tutup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="p-8 md:p-10 relative">
            {{-- Slides --}}
            @foreach($popups as $i => $popup)
            <div x-show="active === {{ $i }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-8">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl flex items-center justify-center mb-5 border border-primary-200/50">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-dark-300 tracking-tight mb-3">{{ $popup->trans('title') }}</h3>
                <p class="text-dark-100/60 leading-relaxed">{{ $popup->trans('content') }}</p>
                @if($popup->trans('button_text'))
                <div class="mt-8">
                    <a href="{{ $popup->button_url ?: '#' }}" {{ $popup->button_url ? 'target=_blank rel=noopener' : '' }} class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 text-white font-bold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                        {{ $popup->trans('button_text') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                @endif
            </div>
            @endforeach

            {{-- Dots nav --}}
            @if($popups->count() > 1)
            <div class="flex items-center justify-center gap-2 mt-8">
                @foreach($popups as $i => $popup)
                <button type="button" @click="goTo({{ $i }})" :class="active === {{ $i }} ? 'bg-primary-500 w-6' : 'bg-dark-100/20 w-2'" class="h-2 rounded-full transition-all duration-300" :aria-label="'Slide ' + ({{ $i }} + 1)"></button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endif
