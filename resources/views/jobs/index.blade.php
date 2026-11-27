@extends('layouts.master')

@section('title', __('Freelance Sales & Marketing — The Solution ID'))
@section('meta_description', __('Bergabung sebagai partner freelance sales & marketing The Solution ID. Dapatkan komisi
    10% dan bonus bulanan.'))

@section('content')
    <x-page-header
        :title="'Freelance Sales & Marketing ' . __('(Partner Komisi)')"
        :lede="__('Bantu bisnis menemukan solusi digital yang tepat, bangun relasi, dan dapatkan komisi dari setiap deal yang berhasil.')"
        :crumbs="[['label' => __('Karier')]]">
        <div class="flex flex-col items-start gap-4 lg:items-end">
            <span class="tag">{{ __('Freelance · Kerja Fleksibel') }}</span>
            <a href="#daftar" class="btn-primary w-full sm:w-auto">
                {{ __('Daftar sekarang') }} <x-icon-arrow dir="down" />
            </a>
        </div>
    </x-page-header>

    {{-- The offer, in two blocks. --}}
    <section class="border-b border-line">
        <div class="wrap grid gap-1.5 py-10 sm:grid-cols-2 sm:gap-2 md:py-14">
            <div class="bg-primary-500 p-6 text-dark-300 md:p-8">
                <h2 class="text-sm font-semibold">{{ __('Komisi transparan') }}</h2>
                <p class="num mt-4 font-display text-6xl font-extrabold leading-none">10%</p>
                <p class="mt-5 max-w-[46ch]">{{ __('Dari harga deal final setelah diskon. Komisi dibayarkan seiring pembayaran klien diterima.') }}</p>
            </div>
            <div class="bg-dark-100 p-6 text-white md:p-8">
                <h2 class="text-sm font-semibold text-white/85">{{ __('Bonus bulanan') }}</h2>
                <p class="num mt-4 font-display text-5xl font-extrabold leading-none md:text-6xl">Rp500.000</p>
                <p class="mt-5 max-w-[52ch] text-white/90">{{ __('Jika berhasil membawa 3 klien dalam 1 bulan, kamu mendapat bonus Rp500.000. Bonus berlaku kelipatan: 6 klien = Rp1.000.000, 9 klien = Rp1.500.000, dan seterusnya.') }}</p>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-20">
        <div class="wrap grid gap-12 lg:grid-cols-12">
            {{-- Role details --}}
            <div class="space-y-12 lg:col-span-6">
                <div>
                    <h2 class="display-3">{{ __('Hubungkan kebutuhan bisnis dengan solusi digital') }}</h2>
                    <p class="mt-4 text-dark-100">
                        {{ __('The Solution ID membantu bisnis berkembang lewat pembuatan website, game, dan multimedia pembelajaran interaktif (MPI). Sebagai partner, kamu mencari calon klien yang membutuhkan layanan tersebut dan menghubungkannya dengan tim kami.') }}
                    </p>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-dark-300">{{ __('Yang akan kamu lakukan') }}</h2>
                    <ul class="mt-4 divide-y divide-line border-y border-line">
                        @foreach ([
                            __('Mencari dan mengenali calon klien yang membutuhkan solusi digital.'),
                            __('Membangun komunikasi awal dan menjelaskan layanan kami.'),
                            __('Menghubungkan calon klien dengan tim untuk konsultasi dan presentasi.'),
                        ] as $task)
                            <li class="flex gap-4 py-4 text-dark-200"><span class="mt-2 h-2 w-2 shrink-0 bg-primary-500" aria-hidden="true"></span>{{ $task }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-dark-300">{{ __('Kriteria yang kami cari') }}</h2>
                    <ul class="cells mt-4 sm:grid-cols-2">
                        @foreach ([
                            __('Komunikatif dan nyaman membangun relasi.'),
                            __('Tertarik pada sales, marketing, dan solusi digital.'),
                            __('Mandiri, proaktif, dan bertanggung jawab.'),
                            __('Bersedia bekerja secara freelance berbasis komisi.'),
                        ] as $criterion)
                            <li class="p-4 text-dark-200">{{ $criterion }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-dark-300">{{ __('Dukungan dari tim') }}</h2>
                    <ul class="mt-4 flex flex-wrap gap-2">
                        @foreach (['Company profile', 'Portofolio', 'Katalog harga', 'Template penawaran', 'Pendampingan presentasi'] as $support)
                            <li class="tag-blue !px-3 !py-1.5 !text-sm">{{ __($support) }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-dark-300">{{ __('Alur seleksi') }}</h2>
                    @php $selection = ['Kirim formulir pendaftaran', 'Tim menghubungi melalui WhatsApp', 'Masa percobaan selama 1 bulan', 'Menjadi partner aktif']; @endphp
                    <ol class="mt-4 space-y-3">
                        @foreach ($selection as $step)
                            <li class="flex items-center gap-4">
                                <span class="flex items-end gap-0.5" aria-hidden="true">
                                    @for ($b = 1; $b <= count($selection); $b++)
                                        <span class="h-2.5 w-2.5 {{ $b < $loop->iteration ? 'bg-dark-100' : ($b === $loop->iteration ? 'bg-primary-500' : 'border border-dark-100/30') }}"></span>
                                    @endfor
                                </span>
                                <span class="text-dark-200">{{ __($step) }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            {{-- Application form --}}
            <div id="daftar" class="scroll-mt-24 lg:col-span-5 lg:col-start-8">
                <div class="border border-dark-300/15 bg-white p-5 shadow-lg sm:p-8 lg:sticky lg:top-24">
                    <h2 class="display-3">{{ __('Formulir pendaftaran') }}</h2>
                    <p class="mt-3 font-semibold text-dark-300">{{ __('Ceritakan tentang dirimu') }}</p>
                    <p class="mt-1 text-sm text-dark-100">{{ __('Kolom bertanda * wajib diisi. Pengisian biasanya memerlukan 3–5 menit.') }}</p>

                    @if ($errors->any())
                        <div class="mt-6 border border-red-300 bg-red-50 p-4 text-sm text-red-800" role="alert">
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
                            <label for="full_name" class="field-label">{{ __('Nama lengkap') }} *</label>
                            <input id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="100" autocomplete="name"
                                   class="field" @error('full_name') aria-invalid="true" @enderror>
                            @error('full_name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="whatsapp" class="field-label">{{ __('Nomor WhatsApp') }} *</label>
                            <input id="whatsapp" name="whatsapp" type="tel" inputmode="tel" value="{{ old('whatsapp') }}" required maxlength="20" autocomplete="tel"
                                   placeholder="08xxxxxxxxxx" aria-describedby="whatsapp-hint" class="field" @error('whatsapp') aria-invalid="true" @enderror>
                            <p id="whatsapp-hint" class="field-hint">{{ __('Gunakan nomor aktif, diawali 08 atau 628.') }}</p>
                            @error('whatsapp')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="domicile" class="field-label">{{ __('Domisili (kota/kecamatan)') }} *</label>
                            <input id="domicile" name="domicile" value="{{ old('domicile') }}" required maxlength="100" autocomplete="address-level2"
                                   class="field" @error('domicile') aria-invalid="true" @enderror>
                            @error('domicile')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="birth_date" class="field-label">{{ __('Tanggal lahir') }} *</label>
                            <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}" max="{{ now()->subYears(18)->toDateString() }}"
                                   required autocomplete="bday" aria-describedby="birth-hint" class="field" @error('birth_date') aria-invalid="true" @enderror>
                            <p id="birth-hint" class="field-hint">{{ __('Pendaftar harus berusia minimal 18 tahun.') }}</p>
                            @error('birth_date')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="current_activity" class="field-label">{{ __('Kegiatan saat ini') }} *</label>
                            <select id="current_activity" name="current_activity" required class="field" @error('current_activity') aria-invalid="true" @enderror>
                                <option value="">{{ __('Pilih kegiatan') }}</option>
                                @foreach (\App\Models\Applicant::ACTIVITIES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('current_activity') === $value)>{{ __($label) }}</option>
                                @endforeach
                            </select>
                            @error('current_activity')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="experience" class="field-label">{{ __('Pengalaman jualan atau relasi') }} <span class="font-normal text-dark-100">({{ __('opsional') }})</span></label>
                            <textarea id="experience" name="experience" rows="3" maxlength="5000" class="field">{{ old('experience') }}</textarea>
                            @error('experience')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="motivation" class="field-label">{{ __('Mengapa tertarik bergabung?') }} *</label>
                            <textarea id="motivation" name="motivation" rows="4" minlength="20" maxlength="2000" required aria-describedby="motivation-hint"
                                      class="field" @error('motivation') aria-invalid="true" @enderror>{{ old('motivation') }}</textarea>
                            <p id="motivation-hint" class="field-hint">{{ __('Maksimal 2.000 karakter.') }}</p>
                            @error('motivation')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="social_link" class="field-label">{{ __('Link Instagram atau LinkedIn') }} *</label>
                            <input id="social_link" name="social_link" type="url" value="{{ old('social_link') }}" required maxlength="255" placeholder="https://instagram.com/..."
                                   class="field" @error('social_link') aria-invalid="true" @enderror>
                            @error('social_link')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="flex items-start gap-3 text-sm leading-6 text-dark-200">
                                <input type="checkbox" name="agreed_commission" value="1" required @checked(old('agreed_commission'))
                                       class="mt-1 h-5 w-5 rounded-sm border-dark-100/50 text-primary-600 focus:ring-primary-500">
                                <span>{{ __('Saya memahami dan menyetujui skema komisi 10% dari pembayaran yang diterima serta bonus sesuai ketentuan di atas.') }} *</span>
                            </label>
                            @error('agreed_commission')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <p class="text-sm leading-relaxed text-dark-100">
                            {{ __('Data kontak dan jawabanmu hanya digunakan oleh admin The Solution ID untuk proses seleksi dan akan dihubungi melalui WhatsApp. Data tidak ditampilkan kepada publik.') }}
                        </p>
                        <button type="submit" class="btn-primary w-full">{{ __('Kirim pendaftaran') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
