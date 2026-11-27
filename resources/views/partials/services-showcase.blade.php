@php
    use App\Models\Setting;
    use App\Support\AndroidAppExamples;
    use App\Support\WebsiteExamples;

    $wa = fn (string $text) => 'https://wa.me/' . Setting::get('contact_whatsapp') . '?text=' . rawurlencode($text);

    $kinds = [
        'website' => [
            'tab' => __('Website'),
            'tabHint' => __('Company profile, toko online, sistem informasi'),
            'lede' => __('Website yang membuat bisnis Anda terlihat profesional dan mudah ditemukan di Google, selesai dalam 1–3 minggu.'),
            'gets' => [__('Custom Design'), __('Website Responsive'), __('SEO Friendly'), __('Custom Domain'), __('Gratis SSL'), __('Gratis 2 Email Bisnis'), __('Gratis Perbaikan'), __('Gratis Konsultasi')],
            'pickerLabel' => __('Mau website seperti apa? Pilih untuk melihat contohnya.'),
            'examples' => WebsiteExamples::all(),
            'model' => 'web',
            'wa' => $wa('Halo The Solution ID! Saya ingin konsultasi pembuatan website'),
            'steps' => [
                [__('Konsultasi gratis'), __('Ceritakan bisnis dan target Anda. Kita tentukan halaman dan fitur yang dibutuhkan.')],
                [__('Desain custom'), __('Tampilan dibuat sesuai branding bisnis Anda, direvisi sampai sesuai keinginan.')],
                [__('Pengerjaan 1–3 minggu'), __('Progres dilaporkan setiap minggu, jadi Anda selalu tahu sampai mana pengerjaannya.')],
                [__('Online & didukung'), __('Domain custom, SSL dan 2 email bisnis gratis, plus perbaikan bug gratis 30 hari setelah launch.')],
            ],
        ],
        'android' => [
            'tab' => __('Aplikasi Android'),
            'tabHint' => __('Kasir, absensi, booking, e-learning'),
            'lede' => __('Kami rancang tampilannya dan kami bangun aplikasinya. Anda terima file APK dan source code lengkapnya, lalu kami bantu terbitkan di Play Store.'),
            'gets' => [__('Desain UI sesuai permintaan'), __('Source code lengkap'), __('File APK siap install'), __('Bantu upload ke Play Store'), __('Gratis Konsultasi'), __('Revisi sampai acc (S&K berlaku)')],
            'pickerLabel' => __('Mau bikin aplikasi apa? Pilih untuk melihat contohnya.'),
            'examples' => AndroidAppExamples::all(),
            'model' => 'app',
            'wa' => $wa('Halo The Solution ID! Saya ingin konsultasi pembuatan aplikasi Android'),
            'steps' => [
                [__('Ceritakan idenya'), __('Konsultasi gratis. Kita bahas siapa penggunanya, fitur apa yang wajib ada, dan apa yang bisa menyusul.')],
                [__('Desain tampilan'), __('Tampilan aplikasi dirancang sesuai permintaan Anda, dan direvisi sampai Anda setuju (S&K berlaku).')],
                [__('Dibangun jadi APK'), __('Anda menerima file APK yang siap dipasang di HP, beserta source code lengkapnya.')],
                [__('Terbit di Play Store'), __('Kami bantu proses upload aplikasi Anda ke Play Store.')],
            ],
        ],
    ];
@endphp

{{-- One place for both services: pick Website or Android, then pick a type and the device shows an example. --}}
<section id="jasa" class="scroll-mt-20 overflow-hidden border-t border-line py-16 md:py-24"
         x-data="{ kind: 'website', web: 'profil', app: 'toko' }">
    <div class="wrap">
        <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
            <h2 class="display-2 lg:col-span-6">{{ __('Mau dibuatkan apa?') }}</h2>
            <p class="text-dark-100 lg:col-span-5 lg:col-start-8">{{ __('Website agar bisnis tampil profesional dan dicari di Google, atau aplikasi Android yang dipasang langsung di HP pelanggan dan tim Anda.') }}</p>
        </div>

        {{-- Service switch --}}
        <div class="mt-10 grid grid-cols-2 gap-1.5 md:mt-12" role="tablist" aria-label="{{ __('Pilih layanan') }}">
            @foreach ($kinds as $key => $kind)
                <button type="button" role="tab" id="tab-{{ $key }}" aria-controls="panel-{{ $key }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}" :aria-selected="(kind === '{{ $key }}').toString()"
                        @click="kind = '{{ $key }}'"
                        class="relative flex flex-col items-start gap-1 border p-4 text-left transition-colors sm:p-6"
                        :class="kind === '{{ $key }}' ? 'border-dark-300 bg-dark-300 text-white' : 'border-line bg-white text-dark-300 hover:border-dark-300'">
                    <span class="font-display text-xl font-extrabold leading-tight sm:text-3xl">{{ $kind['tab'] }}</span>
                    <span class="hidden text-sm sm:block" :class="kind === '{{ $key }}' ? 'text-white/75' : 'text-dark-100'">{{ $kind['tabHint'] }}</span>
                    <span class="absolute inset-x-0 bottom-0 h-1 bg-primary-500" x-show="kind === '{{ $key }}'" @if(! $loop->first) x-cloak @endif></span>
                </button>
            @endforeach
        </div>

        @foreach ($kinds as $key => $kind)
            <div id="panel-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}" x-show="kind === '{{ $key }}'" @if(! $loop->first) x-cloak @endif>
                <div class="grid gap-12 pt-10 md:pt-14 lg:grid-cols-12 lg:items-center">
                    <div class="min-w-0 lg:col-span-6">
                        <p class="max-w-[54ch] text-lg leading-relaxed text-dark-200">{{ $kind['lede'] }}</p>

                        <ul class="mt-6 grid gap-x-5 gap-y-2.5 sm:grid-cols-2">
                            @foreach ($kind['gets'] as $item)
                                <li class="flex items-center gap-3 text-[0.95rem] font-semibold text-dark-300">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center bg-primary-500 text-dark-300">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="square" viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 5 5L19 7"/></svg>
                                    </span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-9">
                            <p id="picker-{{ $key }}" class="text-sm font-semibold text-dark-300">{{ $kind['pickerLabel'] }}</p>
                            <div class="-mx-5 mt-3 overflow-x-auto px-5 sm:mx-0 sm:px-0" role="group" aria-labelledby="picker-{{ $key }}">
                                <ul class="flex gap-2 whitespace-nowrap sm:flex-wrap sm:whitespace-normal">
                                    @foreach ($kind['examples'] as $exKey => $example)
                                        <li>
                                            <button type="button" @click="{{ $kind['model'] }} = '{{ $exKey }}'"
                                                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}" :aria-pressed="({{ $kind['model'] }} === '{{ $exKey }}').toString()"
                                                    class="inline-flex min-h-10 items-center border px-3.5 text-sm font-semibold transition-colors"
                                                    :class="{{ $kind['model'] }} === '{{ $exKey }}' ? 'border-dark-300 bg-dark-300 text-white' : 'border-line bg-white text-dark-100 hover:border-dark-300 hover:text-dark-300'">
                                                {{ $example['label'] }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <p class="mt-3 text-xs text-dark-100">{{ __('Contoh tampilan untuk ilustrasi, bukan proyek klien.') }}</p>
                        </div>

                        <a href="{{ $kind['wa'] }}" target="_blank" rel="noopener noreferrer" class="btn-primary mt-9 !min-h-14 !px-8 text-base">
                            {{ __('Konsultasi Gratis') }} <x-icon-arrow class="h-5 w-5" />
                        </a>
                    </div>

                    <div class="lg:col-span-6 {{ $key === 'android' ? 'px-8' : 'pr-4 sm:pr-6' }}">
                        @if ($key === 'website')
                            <x-browser-mock :screens="$kind['examples']" :interactive="true" />
                        @else
                            <x-app-phone :screens="$kind['examples']" :interactive="true" />
                        @endif
                    </div>
                </div>

                <h3 class="mt-16 text-lg font-bold text-dark-300">{{ __('Alur pengerjaan') }}</h3>
                <ol class="cells mt-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($kind['steps'] as [$title, $body])
                        <li class="flex flex-col p-6">
                            <div class="flex items-end gap-1" aria-hidden="true">
                                @for ($b = 1; $b <= count($kind['steps']); $b++)
                                    <span class="h-2.5 w-2.5 {{ $b < $loop->iteration ? 'bg-dark-100' : ($b === $loop->iteration ? 'bg-primary-500' : 'border border-dark-100/30') }}"></span>
                                @endfor
                            </div>
                            <p class="num mt-5 text-sm font-semibold text-primary-700">{{ __('Langkah') }} {{ $loop->iteration }}</p>
                            <h4 class="mt-1 font-bold text-dark-300">{{ $title }}</h4>
                            <p class="mt-2 text-[0.95rem] text-dark-100">{{ $body }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endforeach
    </div>
</section>
