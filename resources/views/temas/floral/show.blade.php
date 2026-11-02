@extends('layouts.site')

{{-- judul tab browser: nama pasangan --}}
@section('title', $undangan->nama_pria . ' & ' . $undangan->nama_wanita)

@section('content')

    {{-- font khusus tema: Great Vibes (tulisan tangan untuk nama), Cormorant Garamond (teks halus) --}}
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* kelas font khusus tema floral */
        .floral-script { font-family: 'Great Vibes', cursive; }
        .floral-serif { font-family: 'Cormorant Garamond', serif; }

        /* animasi kelopak jatuh: dari atas layar ke bawah sambil berputar dan sedikit bergeser */
        @keyframes floral-jatuh {
            0%   { transform: translate3d(0, -10vh, 0) rotate(0deg); opacity: 0; }
            10%  { opacity: 0.9; }
            100% { transform: translate3d(40px, 110vh, 0) rotate(360deg); opacity: 0.7; }
        }
        /* kelopak: diposisikan di atas, animasi diulang terus tanpa henti */
        .floral-kelopak {
            position: absolute;
            top: 0;
            animation-name: floral-jatuh;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }
    </style>

    {{-- gambar bunga, daun, dan kelopak dibuat sekali di sini, lalu dipakai berulang lewat <use> --}}
    {{-- nanti kalau sudah punya gambar PNG sendiri, bagian ini bisa diganti tag img --}}
    <svg width="0" height="0" style="position: absolute;" aria-hidden="true">
        <defs>
            {{-- bunga: 5 kelopak lonjong diputar mengelilingi pusat, plus bulatan emas di tengah --}}
            <symbol id="floral-bunga" viewBox="-50 -50 100 100">
                @foreach ([0, 72, 144, 216, 288] as $sudut)
                    <ellipse cx="0" cy="-22" rx="14" ry="24" fill="#f3c1cc" fill-opacity="0.92" transform="rotate({{ $sudut }})" />
                @endforeach
                <circle r="9" fill="#e6b450" />
            </symbol>
            {{-- daun: bentuk lonjong runcing warna hijau sage --}}
            <symbol id="floral-daun" viewBox="0 0 100 60">
                <path d="M0,30 C25,-5 75,-5 100,30 C75,65 25,65 0,30 Z" fill="#9db38a" fill-opacity="0.9" />
            </symbol>
            {{-- kelopak tunggal untuk animasi jatuh --}}
            <symbol id="floral-kelopak" viewBox="0 0 20 30">
                <ellipse cx="10" cy="15" rx="8" ry="13" fill="#f3c1cc" />
            </symbol>
        </defs>
    </svg>

    {{-- pembungkus perilaku dasar (open, kunci scroll, musik, watermark) --}}
    <x-undangan.pembungkus :undangan="$undangan">

        @php
            // daftar hiasan untuk tiap sisi tirai: posisi atas, jarak dari tepi luar, lebar, rotasi, jenis
            $hiasan = [
                ['atas' => '3%',  'tepi' => '-18%', 'lebar' => '75%', 'putar' => 0,   'jenis' => 'bunga'],
                ['atas' => '26%', 'tepi' => '-26%', 'lebar' => '60%', 'putar' => 35,  'jenis' => 'daun'],
                ['atas' => '44%', 'tepi' => '-14%', 'lebar' => '70%', 'putar' => 20,  'jenis' => 'bunga'],
                ['atas' => '70%', 'tepi' => '-24%', 'lebar' => '62%', 'putar' => -25, 'jenis' => 'daun'],
                ['atas' => '78%', 'tepi' => '-10%', 'lebar' => '68%', 'putar' => 10,  'jenis' => 'bunga'],
            ];
        @endphp

        {{-- COVER: menutup layar selama belum dibuka. Saat open jadi true: tirai bergeser keluar, lalu seluruh cover memudar --}}
        {{-- leave transition: durasi 1 detik, mulai memudar setelah jeda 300ms supaya tirai sempat bergeser dulu --}}
        <div x-show="!open"
            x-transition:leave="transition ease-in duration-1000 delay-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-hidden"
            style="background: #fbf3ee;">

            {{-- dua sisi tirai bunga: kiri dan kanan, masing-masing setengah lebar layar --}}
            @foreach (['kiri', 'kanan'] as $sisi)
                {{-- :class mengubah posisi saat open: sisi kiri geser ke kiri, sisi kanan geser ke kanan --}}
                <div class="absolute inset-y-0 w-1/2 transition-transform duration-1000 ease-in-out {{ $sisi === 'kiri' ? 'left-0' : 'right-0' }}"
                    :class="open ? '{{ $sisi === 'kiri' ? '-translate-x-full' : 'translate-x-full' }}' : 'translate-x-0'"
                    style="background: linear-gradient({{ $sisi === 'kiri' ? '90deg' : '270deg' }}, #f8dfe4, #fbf3ee);">

                    {{-- tempel semua hiasan bunga dan daun di sisi ini --}}
                    @foreach ($hiasan as $h)
                        {{-- sisi kiri pakai properti left, sisi kanan pakai right; rotasi dibalik di sisi kanan biar simetris --}}
                        <svg class="absolute"
                            style="top: {{ $h['atas'] }}; {{ $sisi === 'kiri' ? 'left' : 'right' }}: {{ $h['tepi'] }}; width: {{ $h['lebar'] }}; height: auto; aspect-ratio: {{ $h['jenis'] === 'bunga' ? '1 / 1' : '5 / 3' }}; transform: rotate({{ $sisi === 'kiri' ? $h['putar'] : -$h['putar'] }}deg);">
                            <use href="#floral-{{ $h['jenis'] }}" width="100%" height="100%" />
                        </svg>
                    @endforeach
                </div>
            @endforeach

            {{-- kelopak yang jatuh pelan, 12 buah dengan posisi, ukuran, dan kecepatan berbeda --}}
            @for ($i = 0; $i < 12; $i++)
                {{-- delay negatif supaya kelopak sudah di tengah jalan saat halaman dimuat, bukan semuanya mulai dari atas --}}
                <svg class="z-20 pointer-events-none floral-kelopak"
                    style="left: {{ $i * 8 + 2 }}%; width: {{ 12 + ($i % 3) * 4 }}px; height: {{ 18 + ($i % 3) * 6 }}px; animation-duration: {{ 7 + ($i % 5) }}s; animation-delay: -{{ $i * 0.9 }}s;">
                    <use href="#floral-kelopak" width="100%" height="100%" />
                </svg>
            @endfor

            {{-- isi tengah cover: tulisan dan tombol, memudar cepat saat undangan dibuka --}}
            <div class="relative z-10 flex items-center justify-center h-full px-4 text-center transition-opacity duration-500"
                :class="open ? 'opacity-0' : 'opacity-100'">
                <div class="max-w-xs">

                    {{-- label kecil di atas nama --}}
                    <p class="text-sm uppercase floral-serif" style="color: #9a7b6d; letter-spacing: 0.3em;">The Wedding Of</p>

                    {{-- nama pasangan, pakai font tulisan tangan --}}
                    <h1 class="mt-4 text-6xl leading-tight floral-script" style="color: #b0566f;">
                        {{ $undangan->nama_pria }}
                        <span class="block my-1 text-3xl" style="color: #c9a24a;">&</span>
                        {{ $undangan->nama_wanita }}
                    </h1>

                    {{-- tanggal akad, cuma tampil kalau diisi --}}
                    @if ($undangan->akad_tanggal)
                        <p class="mt-6 text-lg floral-serif" style="color: #7a6258;">
                            {{ \Carbon\Carbon::parse($undangan->akad_tanggal)->translatedFormat('l, d F Y') }}
                        </p>
                    @endif

                    {{-- nama tamu dari URL (?to=...), cuma tampil kalau ada --}}
                    @if (request()->query('to'))
                        <div class="mt-8 floral-serif" style="color: #9a7b6d;">
                            <p class="text-sm">Kepada Yth.</p>
                            <p class="mt-1 text-xl font-semibold" style="color: #7a6258;">{{ e(request()->query('to')) }}</p>
                        </div>
                    @endif

                    {{-- tombol buka undangan: bukaUndangan() disediakan komponen pembungkus --}}
                    <button type="button" @click="bukaUndangan()"
                        class="px-8 py-3 mt-8 text-sm transition rounded-full floral-serif hover:opacity-80"
                        style="background: #b0566f; color: #fff; letter-spacing: 0.1em;">
                        Buka Undangan
                    </button>
                </div>
            </div>
        </div>

        {{-- HERO: halaman biasa yang terlihat setelah cover terbuka dan saat scroll ke atas --}}
        <div class="flex items-center justify-center min-h-screen px-4 text-center" style="background: #fbf3ee;">
            <div class="max-w-xs">
                <p class="text-sm uppercase floral-serif" style="color: #9a7b6d; letter-spacing: 0.3em;">The Wedding Of</p>
                <h2 class="mt-4 text-6xl leading-tight floral-script" style="color: #b0566f;">
                    {{ $undangan->nama_pria }}
                    <span class="block my-1 text-3xl" style="color: #c9a24a;">&</span>
                    {{ $undangan->nama_wanita }}
                </h2>
                @if ($undangan->akad_tanggal)
                    <p class="mt-6 text-lg floral-serif" style="color: #7a6258;">
                        {{ \Carbon\Carbon::parse($undangan->akad_tanggal)->translatedFormat('l, d F Y') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- titik tujuan scroll setelah undangan dibuka (wajib ada, dipakai bukaUndangan()) --}}
        <div id="mulai"></div>

        {{-- penanda sementara: section lain (ayat, mempelai, galeri, dst) kita susun di langkah berikutnya --}}
        <div class="px-4 py-24 text-center floral-serif" style="background: #fbf3ee; color: #9a7b6d;">
            <p>Section lain untuk tema Floral menyusul.</p>
        </div>

    </x-undangan.pembungkus>

@endsection
