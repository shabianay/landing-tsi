@extends('layouts.admin')

@section('title', 'Cari — Admin TSI')

@section('content')
@include('admin.partials.pm-head', ['title' => 'Cari', 'subtitle' => 'Proyek klien, invoice, dan data klien.'])

<form method="GET" action="{{ route('admin.search') }}" class="mb-8 flex max-w-xl gap-2" role="search">
    <input type="search" name="q" value="{{ $q }}" placeholder="Nama klien, jenis pekerjaan, atau nomor invoice" autofocus class="field" aria-label="Kata kunci">
    <button type="submit" class="btn-ink !min-h-0 !px-5">Cari</button>
</form>

@if (mb_strlen($q) >= 2)
    @php $total = $results['orders']->count() + $results['invoices']->count() + $results['clients']->count(); @endphp
    @if ($total === 0)
        <p class="border border-dashed border-dark-100/40 p-8 text-dark-100">Tidak ada hasil untuk “{{ $q }}”.</p>
    @else
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ([
                ['Proyek klien', $results['orders'], fn ($o) => [route('admin.orders.edit', $o), $o->client_name, $o->task_type . ' · ' . $o->status_label]],
                ['Invoice', $results['invoices'], fn ($i) => [route('admin.invoices.edit', $i), $i->invoice_number, $i->client_name . ' · ' . $i->status_label]],
                ['Data klien', $results['clients'], fn ($c) => [route('admin.order-clients.edit', $c), $c->name, $c->company ?: ($c->phone ?: '')]],
            ] as [$heading, $items, $map])
                <section class="border border-line bg-white">
                    <h2 class="flex items-center justify-between border-b border-line px-5 py-3 font-bold text-dark-300">{{ $heading }} <span class="num text-sm font-normal text-dark-100">{{ $items->count() }}</span></h2>
                    <ul class="divide-y divide-line">
                        @forelse ($items as $item)
                            @php [$url, $title, $meta] = $map($item); @endphp
                            <li><a href="{{ $url }}" class="block px-5 py-3 hover:bg-dark-50"><span class="block font-semibold text-dark-300">{{ $title }}</span><span class="block text-sm text-dark-100">{{ $meta }}</span></a></li>
                        @empty
                            <li class="px-5 py-6 text-sm text-dark-100">Tidak ada.</li>
                        @endforelse
                    </ul>
                </section>
            @endforeach
        </div>
    @endif
@elseif ($q !== '')
    <p class="text-dark-100">Ketik minimal 2 huruf.</p>
@endif
@endsection
