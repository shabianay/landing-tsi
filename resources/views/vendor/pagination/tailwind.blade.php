@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="flex flex-col items-center gap-4">
            {{-- Info --}}
            <p class="text-sm text-dark-100/50">
                {{ __('Menampilkan') }}
                @if ($paginator->firstItem())
                    <span class="font-semibold text-dark-300">{{ $paginator->firstItem() }}</span>
                    <span class="text-dark-100/30">&ndash;</span>
                    <span class="font-semibold text-dark-300">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                <span class="text-dark-100/30 mx-1.5">&bull;</span>
                <span class="font-semibold text-dark-300">{{ $paginator->total() }}</span>
                {{ __('proyek') }}
            </p>

            {{-- Pagination --}}
            <div class="flex items-center gap-2">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/15 bg-dark-50 border border-dark-100/5 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 shadow-sm hover:border-primary-500/30 hover:text-primary-600 hover:shadow-md hover:-translate-y-0.5 active:scale-90 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                {{-- Pages --}}
                <div class="flex items-center -space-x-px">
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="flex items-center justify-center w-10 h-10 text-xs font-semibold text-dark-100/20 select-none bg-white border border-dark-100/10">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="relative flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-br from-primary-500 to-primary-600 border border-primary-500 shadow-lg shadow-primary-500/30 z-10 before:absolute before:inset-0 before:rounded-[inherit] before:bg-white/10 before:opacity-0 before:transition-opacity before:duration-200 hover:before:opacity-100">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-dark-100/50 bg-white border border-dark-100/10 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 hover:z-10 relative transition-all duration-200" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 shadow-sm hover:border-primary-500/30 hover:text-primary-600 hover:shadow-md hover:-translate-y-0.5 active:scale-90 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/15 bg-dark-50 border border-dark-100/5 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif