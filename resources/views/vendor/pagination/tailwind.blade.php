@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col-reverse items-center justify-between gap-4 sm:flex-row">
        <p class="num text-sm text-dark-100">
            {{ __('Menampilkan') }}
            @if ($paginator->firstItem())
                <span class="font-semibold text-dark-300">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
            @else
                <span class="font-semibold text-dark-300">{{ $paginator->count() }}</span>
            @endif
            {{ __('dari') }}
            <span class="font-semibold text-dark-300">{{ $paginator->total() }}</span>
        </p>

        @php $cell = 'flex h-11 min-w-11 items-center justify-center border px-3 text-sm num'; @endphp
        <ul class="flex items-center -space-x-px">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $cell }} cursor-not-allowed border-line text-dark-100/40" aria-disabled="true" aria-label="{{ __('Sebelumnya') }}">
                        <x-icon-arrow dir="left" />
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $cell }} border-line bg-white text-dark-300 hover:relative hover:z-10 hover:border-dark-300" aria-label="{{ __('Sebelumnya') }}">
                        <x-icon-arrow dir="left" />
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="{{ $cell }} hidden border-line text-dark-100 sm:flex">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @php $near = abs($page - $paginator->currentPage()) <= 1 || $page === 1 || $page === $paginator->lastPage(); @endphp
                        <li class="{{ $near ? '' : 'hidden sm:block' }}">
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="{{ $cell }} relative z-10 border-dark-300 bg-dark-300 font-semibold text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $cell }} border-line bg-white text-dark-200 hover:relative hover:z-10 hover:border-dark-300" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $cell }} border-line bg-white text-dark-300 hover:relative hover:z-10 hover:border-dark-300" aria-label="{{ __('Selanjutnya') }}">
                        <x-icon-arrow />
                    </a>
                @else
                    <span class="{{ $cell }} cursor-not-allowed border-line text-dark-100/40" aria-disabled="true" aria-label="{{ __('Selanjutnya') }}">
                        <x-icon-arrow />
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
