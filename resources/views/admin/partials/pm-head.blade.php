{{-- Page header + flash messages for the project-management pages. --}}
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="font-display text-3xl font-extrabold tracking-tight text-dark-300">{{ $title }}</h1>
        @isset($subtitle)
            <p class="mt-1 text-dark-100">{{ $subtitle }}</p>
        @endisset
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{!! $actions !!}</div>
    @endisset
</div>

@if (session('success'))
    <div role="status" class="mb-6 flex flex-wrap items-center gap-x-4 gap-y-2 border border-primary-300 bg-primary-50 px-5 py-3.5 text-sm font-medium text-dark-300">
        <span class="h-2.5 w-2.5 shrink-0 bg-primary-500" aria-hidden="true"></span>
        <span class="flex-1">{{ session('success') }}</span>
        @if (session('pdf_url'))
            <a href="{{ session('pdf_url') }}" target="_blank" rel="noopener" class="font-semibold text-primary-700 underline">Buka PDF</a>
        @endif
    </div>
@endif

@if ($errors->any())
    <div role="alert" class="mb-6 border border-red-300 bg-red-50 px-5 py-4 text-sm text-red-900">
        <p class="font-semibold">Periksa kembali isian:</p>
        <ul class="mt-1.5 list-inside list-disc space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
