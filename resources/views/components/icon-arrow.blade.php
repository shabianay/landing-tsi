@props(['dir' => 'right'])
@php
    $paths = [
        'right' => 'M5 12h14m-6-6 6 6-6 6',
        'left' => 'M19 12H5m6 6-6-6 6-6',
        'down' => 'M12 5v14m6-6-6 6-6-6',
        'out' => 'M7 17 17 7M9 7h8v8',
    ];
@endphp
<svg {{ $attributes->has('class') ? $attributes : $attributes->merge(['class' => 'h-4 w-4']) }} fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $paths[$dir] ?? $paths['right'] }}"/></svg>
