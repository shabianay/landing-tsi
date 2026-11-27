@props(['title', 'lede' => null, 'crumbs' => []])
{{-- Inner-page header: breadcrumb, title, lede. Compact so content starts in the first mobile viewport. --}}
<header class="border-b border-line bg-white">
    <div class="wrap pb-10 pt-8 md:pb-14 md:pt-12">
        <nav aria-label="Breadcrumb" class="mb-6 md:mb-8">
            <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-dark-100">
                <li><a href="{{ route('home') }}" class="hover:text-dark-300 hover:underline">{{ __('Beranda') }}</a></li>
                @foreach ($crumbs as $crumb)
                    <li class="flex items-center gap-2" @if($loop->last) aria-current="page" @endif>
                        <span class="h-1 w-1 bg-dark-100/60" aria-hidden="true"></span>
                        @if (! empty($crumb['url']) && ! $loop->last)
                            <a href="{{ $crumb['url'] }}" class="hover:text-dark-300 hover:underline">{{ $crumb['label'] }}</a>
                        @else
                            <span class="line-clamp-1 font-medium text-dark-300">{{ $crumb['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
        <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-8">
                <h1 class="display-1 max-w-[18ch]">{{ $title }}</h1>
                @if ($lede)
                    <p class="lede mt-5 md:mt-6">{{ $lede }}</p>
                @endif
            </div>
            @if (trim($slot))
                <div class="lg:col-span-4">{{ $slot }}</div>
            @endif
        </div>
    </div>
</header>
