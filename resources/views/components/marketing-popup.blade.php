@props(['popups'])
@if($popups->isNotEmpty())
<div x-data="{
    open: false,
    active: 0,
    total: {{ $popups->count() }},
    autoTimer: null,
    init() {
        if (sessionStorage.getItem('tsi-popup-dismissed')) return;
        let delay = {{ (int) $popups->first()->display_delay * 1000 }};
        setTimeout(() => { this.open = true; this.startAutoSlide(); }, delay);
    },
    close() {
        this.open = false;
        this.stopAutoSlide();
        sessionStorage.setItem('tsi-popup-dismissed', '1');
    },
    startAutoSlide() {
        if (this.total <= 1) return;
        this.autoTimer = setInterval(() => { this.next(); }, 6000);
    },
    stopAutoSlide() {
        if (this.autoTimer) clearInterval(this.autoTimer);
    },
    next() { this.active = (this.active + 1) % this.total; },
    goTo(index) { this.active = index; this.stopAutoSlide(); this.startAutoSlide(); }
}"
     x-show="open"
     x-cloak
     @keydown.escape.window="close()"
     class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center sm:p-6"
     role="dialog" aria-modal="true" aria-labelledby="popup-title-0">
    <div class="absolute inset-0 bg-dark-300/60" @click="close()"
         x-show="open"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div x-show="open"
         x-transition:enter="transition duration-500 ease-out-expo" x-transition:enter-start="translate-y-full sm:translate-y-6 sm:opacity-0" x-transition:enter-end="translate-y-0 sm:opacity-100"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full sm:translate-y-6 sm:opacity-0"
         class="pb-safe relative w-full max-w-lg bg-white shadow-2xl">
        <div class="h-2 w-full bg-primary-500" aria-hidden="true"></div>
        <button type="button" @click="close()" class="absolute right-2 top-4 flex h-11 w-11 items-center justify-center text-dark-100 hover:text-dark-300" aria-label="{{ __('Tutup') }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>

        <div class="p-6 pr-14 sm:p-10 sm:pr-14">
            @foreach($popups as $i => $popup)
                <div x-show="active === {{ $i }}">
                    <h2 id="popup-title-{{ $i }}" class="display-3">{{ $popup->trans('title') }}</h2>
                    <p class="mt-3 text-dark-100">{{ $popup->trans('content') }}</p>
                    @if($popup->trans('button_text'))
                        <a href="{{ $popup->button_url ?: route('contact') }}" @if($popup->button_url) target="_blank" rel="noopener" @endif class="btn-primary mt-7 w-full sm:w-auto">
                            {{ $popup->trans('button_text') }} <x-icon-arrow />
                        </a>
                    @endif
                </div>
            @endforeach

            @if($popups->count() > 1)
                <div class="mt-8 flex items-center gap-1.5">
                    @foreach($popups as $i => $popup)
                        <button type="button" @click="goTo({{ $i }})" class="flex h-6 items-center" aria-label="Slide {{ $i + 1 }}">
                            <span class="block h-1.5 transition-all duration-300" :class="active === {{ $i }} ? 'w-6 bg-dark-300' : 'w-3 bg-dark-100/30'"></span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endif
