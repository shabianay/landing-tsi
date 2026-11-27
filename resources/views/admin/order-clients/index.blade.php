@extends('layouts.admin')

@section('title', 'Data Klien — Admin TSI')

@section('content')
@include('admin.partials.pm-head', [
    'title' => 'Data Klien',
    'subtitle' => 'Klien yang memesan proyek. Dipakai di Proyek Klien dan Invoice.',
    'actions' => '<a href="' . route('admin.order-clients.create') . '" class="btn-primary !min-h-11 !px-5 text-sm">+ Klien baru</a>',
])

<form method="GET" class="mb-5 flex max-w-md gap-2" role="search">
    <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama, perusahaan, atau nomor HP" class="field !py-2 text-sm" aria-label="Cari klien">
    <button type="submit" class="btn-ink !min-h-10 !px-4 text-sm">Cari</button>
</form>

<ul class="space-y-2 md:hidden">
    @forelse ($clients as $client)
        <li class="border border-line bg-white p-4">
            <a href="{{ route('admin.order-clients.edit', $client) }}" class="font-semibold text-dark-300 hover:underline">{{ $client->name }}</a>
            @if ($client->company)<p class="text-sm text-dark-100">{{ $client->company }}</p>@endif
            <p class="mt-1 text-sm text-dark-100 [overflow-wrap:anywhere]">{{ collect([$client->phone, $client->email])->filter()->join(' · ') }}</p>
            <p class="mt-3 flex flex-wrap gap-x-4 gap-y-1 border-t border-line pt-3 text-sm">
                <a href="{{ route('admin.orders.index', ['view' => 'list', 'q' => $client->name]) }}" class="font-semibold text-primary-700 underline">{{ $client->orders_count }} proyek</a>
                <a href="{{ route('admin.invoices.index', ['q' => $client->name]) }}" class="font-semibold text-primary-700 underline">{{ $client->invoices_count }} invoice</a>
                <a href="{{ route('admin.orders.create', ['client' => $client->id]) }}" class="font-semibold text-dark-300 underline">+ Proyek</a>
            </p>
        </li>
    @empty
        <li class="border border-line bg-white px-4 py-10 text-center text-dark-100">{{ $q ? 'Tidak ada klien yang cocok.' : 'Belum ada data klien.' }}</li>
    @endforelse
</ul>
<div class="hidden overflow-x-auto border border-line bg-white md:block">
    <table class="w-full text-sm">
        <thead class="border-b border-line bg-dark-50 text-left text-dark-300">
            <tr>
                <th class="px-4 py-3 font-semibold">Nama</th>
                <th class="px-4 py-3 font-semibold">Kontak</th>
                <th class="px-4 py-3 text-center font-semibold">Proyek</th>
                <th class="px-4 py-3 text-center font-semibold">Invoice</th>
                <th class="px-4 py-3"><span class="sr-only">Aksi</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            @forelse ($clients as $client)
                <tr class="hover:bg-dark-50/60">
                    <td class="px-4 py-3">
                        <span class="block font-semibold text-dark-300">{{ $client->name }}</span>
                        @if ($client->company)<span class="block text-dark-100">{{ $client->company }}</span>@endif
                    </td>
                    <td class="px-4 py-3 text-dark-100">
                        @if ($client->phone)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D+/', '', $client->phone)) }}" target="_blank" rel="noopener" class="num block hover:text-dark-300 hover:underline">{{ $client->phone }}</a>
                        @endif
                        @if ($client->email)<a href="mailto:{{ $client->email }}" class="block hover:text-dark-300 hover:underline">{{ $client->email }}</a>@endif
                    </td>
                    <td class="num px-4 py-3 text-center"><a href="{{ route('admin.orders.index', ['view' => 'list', 'q' => $client->name]) }}" class="font-semibold text-primary-700 hover:underline">{{ $client->orders_count }}</a></td>
                    <td class="num px-4 py-3 text-center"><a href="{{ route('admin.invoices.index', ['q' => $client->name]) }}" class="font-semibold text-primary-700 hover:underline">{{ $client->invoices_count }}</a></td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.orders.create', ['client' => $client->id]) }}" class="font-semibold text-dark-300 hover:underline">+ Proyek</a>
                            <a href="{{ route('admin.order-clients.edit', $client) }}" class="font-semibold text-primary-700 hover:underline">Ubah</a>
                            <form method="POST" action="{{ route('admin.order-clients.destroy', $client) }}" data-confirm="Hapus klien {{ $client->name }}? Proyek dan invoice-nya tetap tersimpan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-700 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-dark-100">{{ $q ? 'Tidak ada klien yang cocok.' : 'Belum ada data klien.' }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $clients->links() }}</div>
@endsection
