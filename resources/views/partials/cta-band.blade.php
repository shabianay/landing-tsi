{{-- Closing call to action: the blue block. --}}
<section class="bg-primary-500 text-dark-300">
    <div class="wrap grid gap-8 py-14 md:py-20 lg:grid-cols-12 lg:items-end">
        <div class="lg:col-span-7">
            <h2 class="display-2">{{ $title ?? __('Siap Meningkatkan Bisnis Anda?') }}</h2>
            <p class="mt-5 max-w-[56ch] text-lg text-dark-300/90">
                {{ $body ?? __('Hubungi kami sekarang untuk konsultasi gratis. Tidak ada kewajiban, hanya diskusi tentang bagaimana kami dapat membantu bisnis Anda tumbuh.') }}
            </p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row lg:col-span-5 lg:justify-end">
            <a href="https://wa.me/{{ \App\Models\Setting::get('contact_whatsapp') }}" target="_blank" rel="noopener noreferrer" class="btn-ink !min-h-14 !px-7">
                <x-icon-whatsapp class="h-5 w-5 text-[#3EDB7A]" />
                {{ __('Chat via WhatsApp') }}
            </a>
            @unless (request()->routeIs('contact'))
                <a href="{{ route('contact') }}" class="btn !min-h-14 border border-dark-300 !px-7 text-dark-300 hover:bg-dark-300 hover:text-white">
                    {{ __('Isi Form Kontak') }}
                </a>
            @endunless
        </div>
    </div>
</section>
