@props(['screens', 'interactive' => false])
{{--
    A phone drawn in the site's block language. Each screen is a small, made-up example of an app
    type (demo content, not client work). When interactive, the parent's Alpine `app` key picks the
    screen; otherwise only the first screen is shown.
--}}
<div {{ $attributes->merge(['class' => 'relative mx-auto w-full max-w-[16.5rem]']) }} aria-hidden="true">
    <div class="absolute -right-5 bottom-12 top-14 w-2/3 bg-primary-500 sm:-right-8"></div>

    <div class="relative h-[27rem] overflow-hidden rounded-[2.25rem] border-[7px] border-dark-300 bg-white shadow-2xl">
        <div class="absolute left-1/2 top-2 z-10 h-1.5 w-14 -translate-x-1/2 rounded-full bg-dark-300"></div>

        @foreach ($screens as $key => $screen)
            <div class="absolute inset-0 flex flex-col px-3 pb-3 pt-7"
                 @if ($interactive)
                     x-show="app === '{{ $key }}'" @if(! $loop->first) x-cloak @endif
                     x-transition:enter="transition duration-300 ease-out-expo" x-transition:enter-start="translate-y-3 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                 @elseif (! $loop->first)
                     hidden
                 @endif>
                <div class="flex items-center gap-2 px-1">
                    <span class="h-4 w-4 bg-primary-500"></span>
                    <span class="truncate text-xs font-bold text-dark-300">{{ $screen['app'] }}</span>
                </div>

                <div class="mt-3 bg-dark-300 p-3 text-white">
                    <p class="text-[0.6rem] text-white/70">{{ $screen['kicker'] }}</p>
                    <p class="mt-0.5 font-display text-lg font-extrabold leading-tight">{{ $screen['headline'] }}</p>
                </div>

                <ul class="mt-2 flex-1 divide-y divide-line border border-line">
                    @foreach ($screen['rows'] as [$left, $right, $state])
                        <li class="flex items-center justify-between gap-2 px-2.5 py-2 text-[0.68rem]">
                            <span class="flex min-w-0 items-center gap-2">
                                <span class="h-2 w-2 shrink-0 {{ $state === 'done' ? 'bg-primary-500' : ($state === 'warn' ? 'bg-amber-500' : 'border border-dark-100/40') }}"></span>
                                <span class="truncate text-dark-200">{{ $left }}</span>
                            </span>
                            <span class="shrink-0 font-semibold text-dark-300">{{ $right }}</span>
                        </li>
                    @endforeach
                </ul>

                <span class="mt-2 block bg-primary-500 py-2 text-center text-[0.7rem] font-bold text-dark-300">{{ $screen['action'] }}</span>

                <div class="mt-2 flex justify-around pt-1">
                    <span class="h-2.5 w-2.5 bg-dark-300"></span><span class="h-2.5 w-2.5 bg-dark-100/25"></span><span class="h-2.5 w-2.5 bg-dark-100/25"></span><span class="h-2.5 w-2.5 bg-dark-100/25"></span>
                </div>
            </div>
        @endforeach
    </div>
</div>
