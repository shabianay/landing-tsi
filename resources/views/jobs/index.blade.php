@extends('layouts.master')

@section('title', __('Freelance Sales & Marketing — The Solution ID'))
@section('meta_description', __('Bergabung sebagai partner freelance sales & marketing The Solution ID. Dapatkan komisi
    10% dan bonus bulanan.'))

@section('content')
    <section class="relative overflow-hidden pt-36 pb-16 lg:pt-52 lg:pb-24">
        <div class="absolute inset-0 z-0">
            <div class="glow top-0 -left-40 h-[500px] w-[500px] bg-primary-200/40"></div>
            <div class="glow bottom-0 -right-40 h-[500px] w-[500px] bg-blue-200/30"></div>
        </div>
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav aria-label="{{ __('Breadcrumb') }}" class="mb-8">
                <ol class="flex flex-wrap items-center gap-1.5 text-sm font-medium">
                    <li><a href="{{ route('home') }}"
                            class="text-dark-100/40 transition-colors hover:text-primary-500">Home</a></li>
                    <li class="flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-dark-100/20" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                        </svg><span class="text-dark-100/60">{{ __('Karier') }}</span></li>
                </ol>
            </nav>
            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_.9fr]">
                <div>
                    <span class="section-label mb-8 inline-flex">{{ __('Freelance · Kerja Fleksibel') }}</span>
                    <h1
                        class="mb-8 max-w-4xl text-5xl font-bold leading-[1.05] tracking-tight text-dark-300 md:text-6xl lg:text-7xl">
                        Freelance Sales &amp; Marketing
                        <span class="gradient-text block">{{ __('(Partner Komisi)') }}</span>
                    </h1>
                    <p class="section-subtitle max-w-2xl">
                        {{ __('Bantu bisnis menemukan solusi digital yang tepat, bangun relasi, dan dapatkan komisi dari setiap deal yang berhasil.') }}
                    </p>
                    <a href="#daftar" class="btn-primary group mt-8 !px-8 !py-4">
                        {{ __('Daftar sekarang') }}
                        <svg class="ml-2 h-5 w-5 transition-transform group-hover:translate-y-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v14m7-7-7 7-7-7" />
                        </svg>
                    </a>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="card !p-6 sm:!p-8">
                        <span class="section-label mb-4">{{ __('Komisi transparan') }}</span>
                        <p class="text-4xl font-bold gradient-text">10%</p>
                        <p class="mt-3 font-medium leading-relaxed text-dark-100/60">
                            {{ __('Dari harga deal final setelah diskon. Komisi dibayarkan seiring pembayaran klien diterima.') }}
                        </p>
                    </div>
                    <div class="card !p-6 sm:!p-8">
                        <span class="section-label mb-4">{{ __('Bonus bulanan') }}</span>
                        <p class="text-4xl font-bold gradient-text">Rp500.000</p>
                        <p class="mt-3 font-medium leading-relaxed text-dark-100/60">
                            {{ __('Jika berhasil membawa 3 klien dalam 1 bulan, kamu mendapat bonus Rp500.000. Bonus berlaku kelipatan: 6 klien = Rp1.000.000, 9 klien = Rp1.500.000, dan seterusnya.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-gradient relative overflow-hidden bg-dark-50 py-16 lg:py-24">
        <div class="relative z-10 mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1.1fr_.9fr] lg:px-8">
            <div class="space-y-6">
                <div class="card !p-6 sm:!p-8">
                    <span class="section-label mb-5">{{ __('Tentang peran ini') }}</span>
                    <h2 class="section-title !text-2xl sm:!text-3xl">
                        {{ __('Hubungkan kebutuhan bisnis dengan solusi digital') }}</h2>
                    <p class="mt-4 font-medium leading-7 text-dark-100/60">
                        {{ __('The Solution ID membantu bisnis berkembang lewat pembuatan website, game, dan multimedia pembelajaran interaktif (MPI). Sebagai partner, kamu mencari calon klien yang membutuhkan layanan tersebut dan menghubungkannya dengan tim kami.') }}
                    </p>
                </div>

                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-xl font-bold text-dark-300">{{ __('Yang akan kamu lakukan') }}</h2>
                    <ul class="mt-5 space-y-4 font-medium leading-7 text-dark-100/60">
                        <li class="flex gap-3"><span
                                class="font-bold text-primary-500">01</span><span>{{ __('Mencari dan mengenali calon klien yang membutuhkan solusi digital.') }}</span>
                        </li>
                        <li class="flex gap-3"><span
                                class="font-bold text-primary-500">02</span><span>{{ __('Membangun komunikasi awal dan menjelaskan layanan kami.') }}</span>
                        </li>
                        <li class="flex gap-3"><span
                                class="font-bold text-primary-500">03</span><span>{{ __('Menghubungkan calon klien dengan tim untuk konsultasi dan presentasi.') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-xl font-bold text-dark-300">{{ __('Kriteria yang kami cari') }}</h2>
                    <ul class="mt-5 grid gap-3 font-medium text-dark-100/60 sm:grid-cols-2">
                        <li class="rounded-xl border border-dark-100/5 bg-dark-50/50 p-4 font-medium text-dark-300">
                            {{ __('Komunikatif dan nyaman membangun relasi.') }}</li>
                        <li class="rounded-xl border border-dark-100/5 bg-dark-50/50 p-4 font-medium text-dark-300">
                            {{ __('Tertarik pada sales, marketing, dan solusi digital.') }}</li>
                        <li class="rounded-xl border border-dark-100/5 bg-dark-50/50 p-4 font-medium text-dark-300">
                            {{ __('Mandiri, proaktif, dan bertanggung jawab.') }}</li>
                        <li class="rounded-xl border border-dark-100/5 bg-dark-50/50 p-4 font-medium text-dark-300">
                            {{ __('Bersedia bekerja secara freelance berbasis komisi.') }}</li>
                    </ul>
                </div>

                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-xl font-bold text-dark-300">{{ __('Dukungan dari tim') }}</h2>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach (['Company profile', 'Portofolio', 'Katalog harga', 'Template penawaran', 'Pendampingan presentasi'] as $support)
                            <span
                                class="rounded-full border border-primary-200/50 bg-primary-50/50 px-4 py-2 text-sm font-semibold text-primary-700">{{ __($support) }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-xl font-bold text-dark-300">{{ __('Alur seleksi') }}</h2>
                    <ol class="mt-5 space-y-4 font-medium text-dark-100/60">
                        @foreach (['Kirim formulir pendaftaran', 'Tim menghubungi melalui WhatsApp', 'Masa percobaan selama 1 bulan', 'Menjadi partner aktif'] as $step)
                            <li class="flex items-center gap-3"><span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 text-sm font-bold text-primary-700">{{ $loop->iteration }}</span>{{ __($step) }}
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            <div id="daftar" class="scroll-mt-24">
                <div class="card !p-5 sm:!p-8">
                    <span class="section-label mb-5">{{ __('Formulir pendaftaran') }}</span>
                    <h2 class="text-2xl font-bold text-dark-300">{{ __('Ceritakan tentang dirimu') }}</h2>
                    <p class="mt-2 text-sm font-medium leading-6 text-dark-100/50">
                        {{ __('Kolom bertanda * wajib diisi. Pengisian biasanya memerlukan 3–5 menit.') }}</p>

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"
                            role="alert">
                            <p class="font-semibold">{{ __('Periksa kembali formulir:') }}</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('jobs.apply') }}" class="mt-6 space-y-5">
                        @csrf
                        <div class="absolute -left-[10000px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                            <label for="website">{{ __('Jangan isi kolom ini') }}</label>
                            <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div>
                            <label for="full_name"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Nama lengkap') }} *</label>
                            <input id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="100"
                                autocomplete="name"
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="whatsapp"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Nomor WhatsApp') }}
                                *</label>
                            <input id="whatsapp" name="whatsapp" type="tel" inputmode="tel"
                                value="{{ old('whatsapp') }}" required maxlength="20" autocomplete="tel"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs font-medium text-dark-100/50">
                                {{ __('Gunakan nomor aktif, diawali 08 atau 628.') }}</p>
                            @error('whatsapp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="domicile"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Domisili (kota/kecamatan)') }}
                                *</label>
                            <input id="domicile" name="domicile" value="{{ old('domicile') }}" required
                                maxlength="100" autocomplete="address-level2"
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('domicile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="birth_date"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Tanggal lahir') }} *</label>
                            <input id="birth_date" name="birth_date" type="date"
                                value="{{ old('birth_date') }}" max="{{ now()->subYears(18)->toDateString() }}"
                                required autocomplete="bday"
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-xs font-medium text-dark-100/50">{{ __('Pendaftar harus berusia minimal 18 tahun.') }}</p>
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="current_activity"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Kegiatan saat ini') }}
                                *</label>
                            <select id="current_activity" name="current_activity" required
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">{{ __('Pilih kegiatan') }}</option>
                                @foreach (\App\Models\Applicant::ACTIVITIES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('current_activity') === $value)>{{ __($label) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('current_activity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="experience"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Pengalaman jualan atau relasi') }}
                                <span class="font-normal text-dark-100/40">({{ __('opsional') }})</span></label>
                            <textarea id="experience" name="experience" rows="3" maxlength="5000"
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('experience') }}</textarea>
                            @error('experience')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="motivation"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Mengapa tertarik bergabung?') }}
                                *</label>
                            <textarea id="motivation" name="motivation" rows="4" minlength="20" maxlength="2000" required
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('motivation') }}</textarea>
                            <p class="mt-1 text-xs font-medium text-dark-100/50">{{ __('Maksimal 2.000 karakter.') }}</p>
                            @error('motivation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="social_link"
                                class="mb-1.5 block text-sm font-semibold text-dark-300">{{ __('Link Instagram atau LinkedIn') }}
                                *</label>
                            <input id="social_link" name="social_link" type="url" value="{{ old('social_link') }}"
                                required maxlength="255" placeholder="https://instagram.com/..."
                                class="w-full rounded-xl border-dark-100/10 text-dark-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('social_link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="flex items-start gap-3 text-sm font-medium leading-6 text-dark-100/60">
                                <input type="checkbox" name="agreed_commission" value="1" required
                                    @checked(old('agreed_commission'))
                                    class="mt-1 rounded border-dark-100/20 text-primary-600 focus:ring-primary-500">
                                <span>{{ __('Saya memahami dan menyetujui skema komisi 10% dari pembayaran yang diterima serta bonus sesuai ketentuan di atas.') }}
                                    *</span>
                            </label>
                            @error('agreed_commission')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="text-xs font-medium leading-5 text-dark-100/50">
                            {{ __('Data kontak dan jawabanmu hanya digunakan oleh admin The Solution ID untuk proses seleksi dan akan dihubungi melalui WhatsApp. Data tidak ditampilkan kepada publik.') }}
                        </p>
                        <button type="submit"
                            class="btn-primary min-h-12 w-full focus:outline-none focus:ring-4 focus:ring-primary-300">
                            {{ __('Kirim pendaftaran') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
