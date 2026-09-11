@extends('layouts.app')

@section('title', 'Undanganku - Undangan Digital Modern & Elegan')

@section('content')

    {{-- NAVBAR --}}
    {{-- NAVBAR --}}
    <header class="w-full sticky top-0 z-50 bg-white">
        <nav class="w-full flex items-center justify-between px-4 md:px-18 py-3">

            {{-- Logo kiri --}}
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">
                Undanganku
            </a>

            {{-- Menu + Login + CTA, semua digabung di kanan --}}
            <div class="flex items-center gap-8">
                <a href="#" class="hidden md:inline text-sm font-medium text-gray-600 hover:text-gray-900">Beranda</a>
                <a href="#themes" class="hidden md:inline text-sm font-medium text-gray-600 hover:text-gray-900">Tema</a>
                <a href="#features" class="hidden md:inline text-sm font-medium text-gray-600 hover:text-gray-900">Fitur</a>
                <a href="#pricing" class="hidden md:inline text-sm font-medium text-gray-600 hover:text-gray-900">Harga</a>
                <a href="#faq" class="hidden md:inline text-sm font-medium text-gray-600 hover:text-gray-900">FAQ</a>

                <a href="#" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Login</a>
                <a href="#"
                    class="text-sm font-semibold bg-gray-900 text-white px-4 py-2 rounded-full hover:bg-gray-700">
                    Buat Undangan
                </a>
            </div>

        </nav>
    </header>
    {{-- HERO --}}
    <section class="w-full px-4 md:px-18 py-8 md:py-10 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        {{-- Kiri: Text --}}
        <div>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 leading-tight"> Undangan Digital Modern &
                Elegan, Siap Dibagikan Dalam Hitungan Menit
            </h1>

            <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                Buat undangan digital yang cantik, cepat dan mudah diedit — lengkap dengan RSVP online,
                galeri foto & video, musik, dan berbagai tema pilihan.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#"
                    class="text-sm font-semibold bg-gray-900 text-white px-6 py-3 rounded-full hover:bg-gray-700">
                    Buat Undangan Gratis
                </a>
                <a href="#themes"
                    class="text-sm font-semibold text-gray-700 border border-gray-300 px-6 py-3 rounded-full hover:bg-gray-50">
                    Lihat Demo
                </a>
            </div>

            <div class="mt-10 flex flex-wrap gap-8">
                <div>
                    <p class="text-2xl font-bold text-gray-900">10.000+</p>
                    <p class="text-sm text-gray-500">Pasangan</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">500.000+</p>
                    <p class="text-sm text-gray-500">Tamu Undangan</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">100.000+</p>
                    <p class="text-sm text-gray-500">Ucapan & Doa</p>
                </div>
            </div>
        </div>

        {{-- Kanan: Phone mockup dengan slider --}}
        <div class="flex justify-center" x-data="{
            slide: 0,
            images: [
                'https://placehold.co/300x620/1f2937/fff?text=Tema+1',
                'https://placehold.co/300x620/374151/fff?text=Tema+2',
                'https://placehold.co/300x620/4b5563/fff?text=Tema+3',
            ],
            init() {
                setInterval(() => {
                    this.slide = (this.slide + 1) % this.images.length;
                }, 3000);
            }
        }">
            {{-- Frame HP --}}
            <div class="relative w-[300px] h-[620px] bg-gray-900 rounded-[3rem] p-3 shadow-2xl">
                {{-- Notch --}}
                <div class="absolute top-3 left-1/2 -translate-x-1/2 w-32 h-6 bg-gray-900 rounded-b-2xl z-10"></div>

                {{-- Layar --}}
                <div class="relative w-full h-full rounded-[2.25rem] overflow-hidden bg-white">
                    <template x-for="(img, index) in images" :key="index">
                        <img :src="img" x-show="slide === index"
                            x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" class="absolute inset-0 w-full h-full object-cover">
                    </template>
                </div>
            </div>
        </div>

    </section>






    {{-- TEMA --}}
    <section id="themes" class="w-full px-4 md:px-18 py-16 md:py-24">

        {{-- Text tengah --}}
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900">
                Pilihan Tema Undangan yang Siap Dipakai
            </h2>
            <p class="mt-4 text-gray-600">
                Banyak pilihan tema premium dan langsung bisa digunakan tanpa ribet.
            </p>
        </div>

        {{-- Carousel --}}
        <div class="mt-12" x-data="{
            scrollLeft() { this.$refs.track.scrollBy({ left: -280, behavior: 'smooth' }) },
                scrollRight() { this.$refs.track.scrollBy({ left: 280, behavior: 'smooth' }) }
        }">

            {{-- Track tema --}}
            <div x-ref="track" class="flex gap-6 overflow-x-auto scroll-smooth px-2 py-2 no-scrollbar">
                @php
                    $themes = [
                        'Timeless Snapshot',
                        'Mildness',
                        'Visual Journey',
                        'Snap Photo',
                        'Elegant Light',
                        'Serein',
                    ];
                @endphp

                @foreach ($themes as $theme)
                    <div class="flex-shrink-0 w-56">
                        <div class="w-full h-80 rounded-2xl overflow-hidden bg-gray-100">
                            <img src="https://placehold.co/300x450/e5e7eb/6b7280?text={{ urlencode($theme) }}"
                                class="w-full h-full object-cover">
                        </div>
                        <p class="mt-3 text-center font-medium text-gray-900">{{ $theme }}</p>
                        <div class="mt-2 text-center">
                            <a href="#" class="text-sm text-gray-600 underline hover:text-gray-900">Lihat Demo</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol kiri-kanan di bawah track --}}
            <div class="flex justify-center gap-4 mt-6">
                <button @click="scrollLeft()"
                    class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="scrollRight()"
                    class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

        </div>

        {{-- Lihat semua tema --}}
        <div class="text-center mt-10">
            <a href="#"
                class="inline-block text-sm font-semibold text-gray-900 border border-gray-300 px-6 py-3 rounded-full hover:bg-gray-50">
                Lihat Semua Tema →
            </a>
        </div>

    </section>

    {{-- FITUR --}}
    <section id="features" class="w-full px-4 md:px-18 py-16 md:py-24">

        {{-- Text tengah --}}
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900">
                Fitur Lengkap untuk Semua Kebutuhan Undangan
            </h2>
            <p class="mt-4 text-gray-600">
                Fitur lengkap yang praktis, modern, dan interaktif — siap membantumu membuat undangan yang menarik dan
                membagikannya kapan saja.
            </p>
        </div>

        {{-- Grid 2 kolom, tiap fitur punya icon sendiri --}}
        <div class="mt-16 max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
            @php
                $features = [
                    [
                        'title' => 'Aktif Selamanya',
                        'desc' =>
                            'Website undangan digitalmu tetap aktif tanpa batas waktu. Bisa dibuka kapan saja oleh tamu.',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'title' => 'Atur Tampilan Undangan',
                        'desc' =>
                            'Edit tampilan undangan online sesuai gaya dan tema pernikahanmu, langsung dari dashboard.',
                        'icon' => 'M4 6h16M4 12h16M4 18h7',
                    ],
                    [
                        'title' => 'Music',
                        'desc' =>
                            'Tambahkan musik latar yang membuat undangan digital kamu terasa lebih hidup dan berkesan.',
                        'icon' =>
                            'M9 19V6l12-2v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-2c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z',
                    ],
                    [
                        'title' => "Ucapan & Do'a",
                        'desc' => 'Terima ucapan dan doa dari tamu langsung melalui halaman undangan online kamu.',
                        'icon' =>
                            'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.05 0-2.056-.16-2.99-.456L3 21l1.549-4.65C3.564 15.09 3 13.6 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                    ],
                    [
                        'title' => 'Kado',
                        'desc' =>
                            'Terima kado cashless atau hadiah lainnya dengan mudah melalui fitur pemberian kado digital.',
                        'icon' =>
                            'M20 7h-3.5a2.5 2.5 0 100-5C14 2 12 7 12 7m8 0h-8m8 0v13a1 1 0 01-1 1H5a1 1 0 01-1-1V7m8 0H4m8 0V22M4 7h3.5a2.5 2.5 0 110-5C10 2 12 7 12 7',
                    ],
                    [
                        'title' => 'Galeri Foto & Video',
                        'desc' =>
                            'Tampilkan foto prewedding dan video kenangan terbaik langsung di halaman undangan digital.',
                        'icon' =>
                            'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1z',
                    ],
                    [
                        'title' => 'Live Streaming',
                        'desc' =>
                            'Bagikan link live streaming agar tamu yang tidak hadir tetap bisa mengikuti acara pernikahanmu.',
                        'icon' =>
                            'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
                    ],
                    [
                        'title' => 'Kirim WA',
                        'desc' =>
                            'Kirim undangan digital kamu ke WhatsApp tamu untuk lebih personal dan dekat dengan tamu.',
                        'icon' =>
                            'M8 12h.01M12 12h.01M16 12h.01M12 21c-4.97 0-9-3.582-9-8s4.03-8 9-8 9 3.582 9 8c0 1.55-.394 3.01-1.084 4.282L21 21l-4.718-1.916A9.05 9.05 0 0112 21z',
                    ],
                ];
            @endphp

            @foreach ($features as $feature)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $feature['title'] }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </section>




    {{-- HARGA --}}
    <section id="pricing" class="w-full px-4 md:px-18 py-16 md:py-24 bg-gray-50">

        {{-- Text tengah --}}
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900">
                Satu Harga, Semua Tema
            </h2>
            <p class="mt-4 text-gray-600">
                Coba dulu tampilannya secara gratis, aktifkan kapan pun kamu siap membagikannya ke tamu.
            </p>
        </div>

        {{-- 2 card --}}
        <div class="mt-12 max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Card Gratis --}}
<div class="bg-white border border-gray-200 rounded-2xl p-8 flex flex-col h-full">
                <h3 class="font-semibold text-gray-900">Preview Gratis</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">Rp 0</p>
                <p class="mt-1 text-sm text-gray-500">Lihat-lihat dulu sebelum beli</p>

                <ul class="mt-6 space-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Bisa lihat semua tema
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Watermark "Preview" tampil di halaman
                    </li>
                    <li class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Belum bisa dibagikan ke tamu
                    </li>
                </ul>

        <div class="mt-auto pt-8">
    <a href="#"
        class="block text-center text-sm font-semibold text-gray-900 border border-gray-300 px-6 py-3 rounded-full hover:bg-gray-50">
        Coba Preview
    </a>
</div>
            </div>

            {{-- Card Berbayar --}}
            <div class="bg-gray-900 rounded-2xl p-8 relative">
                <span
                    class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white text-gray-900 text-xs font-semibold px-3 py-1 rounded-full">
                    Paling Favorit
                </span>

                <h3 class="font-semibold text-white">Aktifkan Undangan</h3>
                <p class="mt-2 text-3xl font-bold text-white">Rp 10.000</p>
                <p class="mt-1 text-sm text-gray-400">Sekali bayar, aktif selamanya</p>

                <ul class="mt-6 space-y-3 text-sm text-gray-300">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Semua tema, harga sama
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Tanpa watermark
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Bisa dibagikan ke tamu & RSVP aktif
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Aktif selamanya
                    </li>
                </ul>

                <a href="#"
                    class="mt-8 block text-center text-sm font-semibold bg-white text-gray-900 px-6 py-3 rounded-full hover:bg-gray-100">
                    Aktifkan Sekarang
                </a>
            </div>

        </div>

    </section>


    {{-- FAQ --}}
    <section id="faq" class="w-full px-4 md:px-18 py-16 md:py-24">

        {{-- Text tengah --}}
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900">
                Pertanyaan yang Sering Ditanyakan
            </h2>
            <p class="mt-4 text-gray-600">
                Masih ada yang bingung? Mudah-mudahan jawabannya ada di sini.
            </p>
        </div>

        {{-- Accordion --}}
        <div class="mt-12 max-w-2xl mx-auto divide-y divide-gray-200" x-data="{ open: null }">
            @php
                $faqs = [
                    ['q' => 'Apa itu undangan digital?', 'a' => 'Undangan digital adalah undangan berbentuk halaman website yang bisa dibagikan lewat link, lengkap dengan info acara, galeri foto, RSVP, dan fitur interaktif lainnya — tanpa perlu cetak undangan fisik.'],
                    ['q' => 'Berapa lama undangan aktif setelah dibeli?', 'a' => 'Selamanya. Setelah diaktifkan, undanganmu tetap bisa diakses kapan saja tanpa batas waktu.'],
                    ['q' => 'Apakah bisa custom nama, foto, dan tanggal acara?', 'a' => 'Bisa. Semua data seperti nama pasangan, tanggal, lokasi, dan foto bisa diedit langsung dari dashboard kamu.'],
                    ['q' => 'Apakah tamu perlu install aplikasi untuk buka undangan?', 'a' => 'Tidak perlu. Tamu cukup membuka link undangan lewat browser HP atau laptop, tanpa install apa pun.'],
                    ['q' => 'Bagaimana cara membagikan undangan ke tamu?', 'a' => 'Cukup bagikan link undanganmu lewat WhatsApp, Instagram, atau platform lain — tamu tinggal klik dan langsung terbuka.'],
                    ['q' => 'Apakah RSVP dan ucapan bisa langsung saya lihat?', 'a' => 'Ya, semua konfirmasi kehadiran dan ucapan dari tamu bisa kamu pantau langsung dari dashboard secara real-time.'],
                    ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Pembayaran dilakukan via transfer bank atau QRIS, lalu diverifikasi secara manual oleh kami — belum menggunakan payment gateway otomatis.'],
                ];
            @endphp

            @foreach ($faqs as $index => $faq)
                <div class="py-5">
                    <button @click="open = open === {{ $index }} ? null : {{ $index }}"
                        class="w-full flex items-center justify-between text-left">
                        <span class="font-medium text-gray-900">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-gray-400 shrink-0 transition-transform"
                            :class="open === {{ $index }} ? 'rotate-45' : ''" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                                                          <div x-show="open === {{ $index }}" x-collapse class="text-sm text-gray-600 leading-relaxed">
                        <p class="pt-3">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </section>




    {{-- FOOTER --}}
    <footer class="w-full bg-gray-900 text-gray-300 px-4 md:px-18 py-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            {{-- Logo + deskripsi --}}
            <div class="md:col-span-2">
                <a href="{{ url('/') }}" class="text-xl font-bold text-white">Undanganku</a>
                <p class="mt-4 text-sm text-gray-400 leading-relaxed max-w-sm">
                    Bikin undangan digital yang cantik, cepat, dan mudah dibagikan ke semua orang terkasih —
                    tanpa ribet, tanpa cetak.
                </p>
            </div>

            {{-- Produk --}}
            <div>
                <h4 class="text-sm font-semibold text-white">Produk</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="#themes" class="hover:text-white">Tema</a></li>
                    <li><a href="#features" class="hover:text-white">Fitur</a></li>
                    <li><a href="#pricing" class="hover:text-white">Harga</a></li>
                </ul>
            </div>

            {{-- Informasi --}}
            <div>
                <h4 class="text-sm font-semibold text-white">Informasi</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="#faq" class="hover:text-white">FAQ</a></li>
                    <li><a href="#" class="hover:text-white">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-white">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-white">Hubungi Kami</a></li>
                </ul>
            </div>

        </div>
<div class="mt-6 pt-4 border-t border-gray-800 text-sm text-gray-500 text-center">
    © {{ date('Y') }} Undanganku. All rights reserved.
</div>
    </footer>
@endsection
