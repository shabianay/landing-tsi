@extends('layouts.admin')

@section('title', 'Sambungkan DP & pelunasan — Admin TSI')

@section('content')
@include('admin.partials.pm-head', [
    'title' => 'Sambungkan DP & pelunasan',
    'subtitle' => 'Pasangan invoice lama untuk deal yang sama. Belum ada yang diubah: centang yang benar, lalu simpan.',
    'actions' => '<a href="' . route('admin.invoices.index') . '" class="btn-secondary !min-h-11 !px-4 text-sm">Kembali ke Invoice</a>',
])

@if ($pairs->isEmpty())
    <p class="border border-line bg-white px-5 py-10 text-center text-dark-100">Tidak ada pasangan yang perlu disambungkan.</p>
@else
    <form method="POST" action="{{ route('admin.invoices.pairs.link') }}" data-confirm="Sambungkan pasangan yang dicentang? Pembayaran invoice pelunasan disesuaikan seperti yang ditampilkan." class="space-y-3">
        @csrf
        <p class="max-w-3xl text-sm text-dark-100">
            Dicocokkan dari klien dan total yang sama: invoice DP lebih dulu, lalu invoice yang lunas. Setelah disambungkan,
            invoice kedua menjadi <strong>invoice pelunasan</strong> yang hanya menagih sisa setelah DP, jadi pembayarannya
            disesuaikan seperti yang ditampilkan di bawah, dan deal dihitung sekali di laporan.
        </p>

        <ul class="space-y-2">
            @foreach ($pairs as $pair)
                @php($key = $pair['dp']->id . '-' . $pair['settlement']->id)
                <li>
                    <label class="grid cursor-pointer gap-3 border border-line bg-white p-4 hover:border-dark-300 sm:grid-cols-[auto_1fr_1fr_1fr] sm:items-center sm:gap-5 sm:p-5">
                        <input type="checkbox" name="pairs[]" value="{{ $key }}" checked class="h-5 w-5 rounded-sm border-dark-100/50 text-primary-600">
                        <div class="min-w-0">
                            <p class="font-semibold text-dark-300">{{ $pair['dp']->client_name }}</p>
                            <p class="num text-sm text-dark-100">Deal @rp($pair['dp']->document_total)</p>
                        </div>
                        <div class="num min-w-0 text-sm">
                            <p class="font-semibold text-dark-300 [overflow-wrap:anywhere]">{{ $pair['dp']->invoice_number }} <span class="font-normal text-dark-100">· DP</span></p>
                            <p class="text-dark-100">{{ $pair['dp']->invoice_date->translatedFormat('d M Y') }} · dibayar @rp($pair['dp']->paid_amount)</p>
                        </div>
                        <div class="num min-w-0 text-sm">
                            <p class="font-semibold text-dark-300 [overflow-wrap:anywhere]">{{ $pair['settlement']->invoice_number }} <span class="font-normal text-dark-100">· pelunasan</span></p>
                            <p class="text-dark-100">{{ $pair['settlement']->invoice_date->translatedFormat('d M Y') }} · dibayar
                                @if (abs($pair['paid_before'] - $pair['paid_after']) > 0.01)
                                    <s>@rp($pair['paid_before'])</s> → <strong class="text-dark-300">@rp($pair['paid_after'])</strong>
                                @else
                                    @rp($pair['paid_after'])
                                @endif
                            </p>
                        </div>
                    </label>
                </li>
            @endforeach
        </ul>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">Sambungkan yang dicentang</button>
            <a href="{{ route('admin.invoices.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
@endif
@endsection
