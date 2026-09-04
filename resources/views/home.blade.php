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

        {{-- Grid: teks kiri | icon tengah | teks kanan --}}
        <div class="mt-16 max-w-4xl mx-auto divide-y divide-gray-100">
            @php
                $features = [
                    [
                        'title' => 'Aktif Selamanya',
                        'desc' =>
                            'Website undangan digitalmu tetap aktif tanpa batas waktu. Bisa dibuka kapan saja oleh tamu.',
                    ],
                    [
                        'title' => 'Atur Tampilan Undangan',
                        'desc' =>
                            'Edit tampilan undangan online sesuai gaya dan tema pernikahanmu, langsung dari dashboard.',
                    ],
                    [
                        'title' => 'Music',
                        'desc' =>
                            'Tambahkan musik latar yang membuat undangan digital kamu terasa lebih hidup dan berkesan.',
                    ],
                    [
                        'title' => "Ucapan & Do'a",
                        'desc' => 'Terima ucapan dan doa dari tamu langsung melalui halaman undangan online kamu.',
                    ],
                    [
                        'title' => 'Kado',
                        'desc' =>
                            'Terima kado cashless atau hadiah lainnya dengan mudah melalui fitur pemberian kado digital.',
                    ],
                    [
                        'title' => 'Galeri Foto & Video',
                        'desc' =>
                            'Tampilkan foto prewedding dan video kenangan terbaik langsung di halaman undangan digital.',
                    ],
                    [
                        'title' => 'Live Streaming',
                        'desc' =>
                            'Bagikan link live streaming agar tamu yang tidak hadir tetap bisa mengikuti acara pernikahanmu.',
                    ],
                    [
                        'title' => 'Kirim WA',
                        'desc' =>
                            'Kirim undangan digital kamu ke WhatsApp tamu untuk lebih personal dan dekat dengan tamu.',
                    ],
                ];
                $rows = array_chunk($features, 2);
            @endphp

            @foreach ($rows as $row)
                <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-6 py-8">

                    {{-- Teks kiri --}}
                    <div class="text-right">
                        <h3 class="font-semibold text-gray-900">{{ $row[0]['title'] }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $row[0]['desc'] }}</p>
                    </div>

                    {{-- Icon tengah --}}
                    <div class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    {{-- Teks kanan --}}
                    <div class="text-left">
                        <h3 class="font-semibold text-gray-900">{{ $row[1]['title'] }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $row[1]['desc'] }}</p>
                    </div>

                </div>
            @endforeach
        </div>

    </section>

@endsection
