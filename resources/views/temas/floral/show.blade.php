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



{{-- nanti kalau sudah punya gambar PNG sendiri, bagian ini bisa diganti tag img --}}
<svg width="0" height="0" style="position: absolute;" aria-hidden="true">
    <defs>
        <symbol id="floral-bunga" viewBox="-50 -50 100 100">
            @foreach ([0, 72, 144, 216, 288] as $sudut)
                <ellipse cx="0" cy="-22" rx="14" ry="24" fill="#f3c1cc" fill-opacity="0.92" transform="rotate({{ $sudut }})" />
            @endforeach
            <circle r="9" fill="#e6b450" />
        </symbol>
        <symbol id="floral-daun" viewBox="0 0 100 60">
            <path d="M0,30 C25,-5 75,-5 100,30 C75,65 25,65 0,30 Z" fill="#9db38a" fill-opacity="0.9" />
        </symbol>
        <symbol id="floral-kelopak" viewBox="0 0 20 30">
            <ellipse cx="10" cy="15" rx="8" ry="13" fill="#f3c1cc" />
        </symbol>
    </defs>
</svg>

<x-undangan.pembungkus :undangan="$undangan">

@php
    // Grid halus (banyak sel kecil) + jitter besar → posisi terasa random,
    // tapi tetap anti-tabrakan karena 1 sel = maksimal 1 bunga.
    $kolom = 5;    // 5 kolom → sel 20%
    $baris = 6;    // 6 baris → sel ~16.7%

    // bikin daftar semua sel
    $sel = [];
    for ($b = 0; $b < $baris; $b++) {
        for ($k = 0; $k < $kolom; $k++) {
            $sel[] = ['baris' => $b, 'kolom' => $k];
        }
    }
    shuffle($sel);

    $jumlahBunga = 16;            // 16 dari 30 sel → padat tapi masih ada celah
    $sel = array_slice($sel, 0, $jumlahBunga);

    $lebarSel  = 100 / $kolom;    // 20%
    $tinggiSel = 100 / $baris;    // 16.7%

    $hiasan = [];
    foreach ($sel as $s) {
        // ukuran bervariasi per bunga → kesan random lebih kuat
        $lebarBunga = rand(10, 18);

        // jitter besar: geser sampai ±40% dari ukuran sel, tapi dijaga
        // supaya bunga (setengah lebarnya) nggak keluar sel → anti-nabrak
        $jitterX = rand(-8, 8);
        $jitterY = rand(-5, 5);

        $atas = round($s['baris'] * $tinggiSel + $tinggiSel/2 + $jitterY - $lebarBunga/4, 1);
        $kiri = round($s['kolom'] * $lebarSel  + $lebarSel/2  + $jitterX - $lebarBunga/2, 1);

        // clamp biar nggak keluar area 0–100%
        $atas = max(0, min(100 - $lebarBunga, $atas));
        $kiri = max(0, min(100 - $lebarBunga, $kiri));

        // rotasi lebih variatif
        $putar = rand(-70, 70);

        // opacity bervariasi → ada yang "di belakang", ada yang "di depan"
        $opasitas = round(rand(60, 100) / 100, 2);

        // nilai 'tepi' dihitung dari 'kiri' (tetap ada biar markup lama aman)
        $tepiKiri  = $kiri;
        $tepiKanan = -(100 - $kiri);

        $hiasan[] = [
            'atas'       => $atas . '%',
            'kiri'       => $kiri . '%',
            'tepi'       => $tepiKiri . '%',
            'tepi_kanan' => round($tepiKanan, 1) . '%',
            'lebar'      => $lebarBunga . '%',
            'putar'      => $putar,
            'opasitas'   => $opasitas,
            'jenis'      => 'bunga',
        ];
    }

    // acak urutan render → z-index natural ikut random (yang belakangan nimpa)
    shuffle($hiasan);
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
                {{-- warna section sebelumnya (awalnya warna hero), dipakai gelombang pemisah. Tiap section memperbarui nilai ini di akhirnya --}}
        @php $warnaSebelumnya = '#fbf3ee'; @endphp


        {{-- SECTION AYAT: hijau sage tua, kartu lebar berbingkai emas dengan bunga di keempat sudut --}}
        @if ($undangan->ayat_teks)
            {{-- relative + overflow-hidden: tempat gelombang menempel dan bunga sudut tidak bikin layar bisa digeser ke samping --}}
            <div class="relative px-6 pb-24 overflow-hidden pt-28" style="background: #4f6b4a;">

                {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
                @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

                {{-- kartu ayat: max-w-3xl jauh lebih lebar dari kolom HP milik Klasik; outline + border membentuk bingkai ganda --}}
                <div class="relative max-w-3xl px-8 mx-auto text-center py-14 md:px-16"
                    style="border: 1px solid #c9a24a; outline: 1px solid rgba(201, 162, 74, 0.45); outline-offset: 8px; background: rgba(255, 255, 255, 0.05);">

                    {{-- empat bunga di sudut kartu: posisi tiap sudut dan rotasinya berbeda supaya tidak terlihat seperti stempel --}}
                    <svg class="absolute w-14 h-14 -top-7 -left-7" style="transform: rotate(-15deg);"><use href="#floral-bunga" width="100%" height="100%" /></svg>
                    <svg class="absolute w-14 h-14 -top-7 -right-7" style="transform: rotate(20deg);"><use href="#floral-bunga" width="100%" height="100%" /></svg>
                    <svg class="absolute w-14 h-14 -bottom-7 -left-7" style="transform: rotate(35deg);"><use href="#floral-bunga" width="100%" height="100%" /></svg>
                    <svg class="absolute w-14 h-14 -bottom-7 -right-7" style="transform: rotate(-30deg);"><use href="#floral-bunga" width="100%" height="100%" /></svg>

                    {{-- judul kecil di atas ayat --}}
                    <p class="text-sm uppercase floral-serif" style="color: #c9a24a; letter-spacing: 0.35em;">Dengan Menyebut Nama-Nya</p>

                    {{-- teks ayat: jauh lebih besar dari Klasik (text-2xl, text-3xl di layar lebar), warna krem --}}
                    <p class="mt-6 text-2xl italic leading-relaxed md:text-3xl floral-serif" style="color: #fbf3ee;">
                        "{{ $undangan->ayat_teks }}"
                    </p>

                    {{-- garis emas pendek sebagai pemisah antara ayat dan artinya --}}
                    <div class="w-16 mx-auto mt-8" style="border-top: 1px solid #c9a24a;"></div>

                    {{-- arti atau terjemahan, cuma tampil kalau diisi --}}
                    @if ($undangan->ayat_arti)
                        <p class="mt-6 text-lg leading-relaxed floral-serif" style="color: #e6ecdf;">
                            {{ $undangan->ayat_arti }}
                        </p>
                    @endif

                    {{-- sumber ayat, cuma tampil kalau diisi --}}
                    @if ($undangan->ayat_sumber)
                        <p class="mt-4 text-sm font-semibold floral-serif" style="color: #c9a24a; letter-spacing: 0.2em;">
                            {{ $undangan->ayat_sumber }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
            @php $warnaSebelumnya = '#4f6b4a'; @endphp
        @endif
        {{-- SECTION MEMPELAI: tampil kalau salah satu nama lengkap diisi --}}
        {{-- SECTION MEMPELAI: blush, foto lengkung berbingkai emas, dua mempelai berdampingan dan bertingkat --}}
        @if ($undangan->nama_lengkap_pria || $undangan->nama_lengkap_wanita)
            @php
                // true kalau kedua mempelai diisi: menentukan apakah layar lebar dibagi 2 kolom atau 1 kolom saja
                $duaMempelai = $undangan->nama_lengkap_pria && $undangan->nama_lengkap_wanita;
            @endphp

            <div class="relative px-6 pb-24 overflow-hidden pt-28" style="background: #f8dfe4;">

                {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
                @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

                <h2 class="text-6xl text-center mb-14 floral-script" style="color: #4f6b4a;">Mempelai</h2>

                {{-- grid: 1 kolom di HP, 2 kolom di layar lebar kalau mempelai ada dua. Lebar max-w-4xl, jauh lebih lebar dari kolom HP milik Klasik --}}
                <div class="grid max-w-4xl gap-12 mx-auto {{ $duaMempelai ? 'md:grid-cols-2 md:gap-20' : '' }}">

                    {{-- MEMPELAI PRIA: di HP rata kiri (justify-self-start), di layar lebar di kolom kiri --}}
                    @if ($undangan->nama_lengkap_pria)
                        <div class="relative justify-self-start md:justify-self-center">

                            {{-- foto berbentuk lengkung: sudut atas dibulatkan penuh (9999px), sudut bawah tegak --}}
                            <div class="relative w-56 h-72 md:w-64 md:h-80">
                                @if ($undangan->foto_pria)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($undangan->foto_pria) }}"
                                        class="object-cover w-full h-full"
                                        style="border-radius: 9999px 9999px 0 0; border: 5px solid #fff; box-shadow: 0 0 0 2px #c9a24a;">
                                @else
                                    {{-- placeholder kalau foto belum diupload: lengkung kosong berisi bunga --}}
                                    <div class="flex items-center justify-center w-full h-full"
                                        style="border-radius: 9999px 9999px 0 0; background: #f3c1cc; border: 5px solid #fff; box-shadow: 0 0 0 2px #c9a24a;">
                                        <svg class="w-20 h-20"><use href="#floral-bunga" width="100%" height="100%" /></svg>
                                    </div>
                                @endif

                                {{-- bunga kecil menempel di pojok bawah foto, menimpa tepi bingkai --}}
                                <svg class="absolute w-16 h-16 -bottom-5 -right-5" style="transform: rotate(25deg);"><use href="#floral-bunga" width="100%" height="100%" /></svg>
                            </div>

                            {{-- nama dan keterangan, ditaruh di bawah foto dengan lebar mengikuti foto --}}
                            <div class="w-56 mt-8 md:w-64">
                                <h3 class="text-4xl leading-tight floral-script" style="color: #b0566f;">{{ $undangan->nama_lengkap_pria }}</h3>

                                @if ($undangan->anak_ke_pria)
                                    <p class="mt-2 text-lg floral-serif" style="color: #4f6b4a;">{{ $undangan->anak_ke_pria }}</p>
                                @endif

                                @if ($undangan->orang_tua_pria)
                                    <p class="text-base floral-serif" style="color: #7a6258;">{{ $undangan->orang_tua_pria }}</p>
                                @endif

                                {{-- &#64; = simbol @ yang aman di Blade; ltrim membuang @ kalau user sudah mengetiknya --}}
                                @if ($undangan->instagram_pria)
                                    <a href="https://instagram.com/{{ ltrim($undangan->instagram_pria, '@') }}" target="_blank"
                                        class="inline-block mt-2 text-sm underline floral-serif" style="color: #b0566f;">
                                        &#64;{{ ltrim($undangan->instagram_pria, '@') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- tanda & : di HP berada di antara dua kartu (md:hidden), di layar lebar ditaruh melayang di tengah dua kolom --}}
                    @if ($duaMempelai)
                        <p class="text-6xl text-center md:hidden floral-script" style="color: #c9a24a;">&</p>
                        <p class="absolute hidden text-8xl md:block floral-script"
                            style="color: #c9a24a; left: 50%; top: 58%; transform: translate(-50%, -50%);">&</p>
                    @endif

                    {{-- MEMPELAI WANITA: di HP rata kanan (justify-self-end), di layar lebar turun 64px (md:mt-16) supaya bertingkat --}}
                    @if ($undangan->nama_lengkap_wanita)
                        <div class="relative justify-self-end md:justify-self-center {{ $duaMempelai ? 'md:mt-16' : '' }}">

                            <div class="relative w-56 h-72 md:w-64 md:h-80">
                                @if ($undangan->foto_wanita)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($undangan->foto_wanita) }}"
                                        class="object-cover w-full h-full"
                                        style="border-radius: 9999px 9999px 0 0; border: 5px solid #fff; box-shadow: 0 0 0 2px #c9a24a;">
                                @else
                                    <div class="flex items-center justify-center w-full h-full"
                                        style="border-radius: 9999px 9999px 0 0; background: #f3c1cc; border: 5px solid #fff; box-shadow: 0 0 0 2px #c9a24a;">
                                        <svg class="w-20 h-20"><use href="#floral-bunga" width="100%" height="100%" /></svg>
                                    </div>
                                @endif

                                {{-- bunga kecil di pojok bawah-kiri (kebalikan sisi pria) --}}
                                <svg class="absolute w-16 h-16 -bottom-5 -left-5" style="transform: rotate(-25deg);"><use href="#floral-bunga" width="100%" height="100%" /></svg>
                            </div>

                            <div class="w-56 mt-8 md:w-64">
                                <h3 class="text-4xl leading-tight floral-script" style="color: #b0566f;">{{ $undangan->nama_lengkap_wanita }}</h3>

                                @if ($undangan->anak_ke_wanita)
                                    <p class="mt-2 text-lg floral-serif" style="color: #4f6b4a;">{{ $undangan->anak_ke_wanita }}</p>
                                @endif

                                @if ($undangan->orang_tua_wanita)
                                    <p class="text-base floral-serif" style="color: #7a6258;">{{ $undangan->orang_tua_wanita }}</p>
                                @endif

                                @if ($undangan->instagram_wanita)
                                    <a href="https://instagram.com/{{ ltrim($undangan->instagram_wanita, '@') }}" target="_blank"
                                        class="inline-block mt-2 text-sm underline floral-serif" style="color: #b0566f;">
                                        &#64;{{ ltrim($undangan->instagram_wanita, '@') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
            @php $warnaSebelumnya = '#f8dfe4'; @endphp
        @endif


                {{-- SECTION LOVE STORY: krem, timeline tengah dengan kartu gantian kiri dan kanan --}}
        @if ($undangan->loveStories->count() > 0)
            <div class="relative px-6 pb-24 overflow-hidden pt-28" style="background: #fbf3ee;">

                {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
                @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

                <h2 class="text-6xl text-center mb-14 floral-script" style="color: #4f6b4a;">Love Story</h2>

                {{-- wadah timeline: lebar max-w-4xl, relative supaya garis tengah bisa diposisikan di dalamnya --}}
                <div class="relative max-w-4xl mx-auto">

                    {{-- garis vertikal: di HP di kiri (left-4), di layar lebar di tengah (md:left-1/2); -translate-x-1/2 memusatkan garisnya tepat di titik itu --}}
                    <div class="absolute top-0 bottom-0 w-px -translate-x-1/2 left-4 md:left-1/2" style="background: #c9a24a;"></div>

                    <div class="space-y-12">
                        @foreach ($undangan->loveStories as $momen)
                            @php
                                // momen ke-1, 3, 5, dst (index genap) di kiri; momen ke-2, 4, dst di kanan
                                $dikiri = $loop->index % 2 === 0;
                            @endphp

                            {{-- baris satu momen. HP: lebar penuh dengan jarak kiri untuk garis. Layar lebar: setengah lebar; kanan didorong ke sisi kanan (md:ml-auto) --}}
                            <div class="relative pl-12 md:w-1/2 {{ $dikiri ? 'md:pl-0 md:pr-12' : 'md:ml-auto md:pl-12' }}">

                                {{-- titik penanda di garis. Posisi mengikuti sisi kartu: kiri-kartu menempel di tepi kanannya, kanan-kartu di tepi kirinya --}}
                                <span class="absolute w-5 h-5 -translate-x-1/2 rounded-full top-8 left-4 {{ $dikiri ? 'md:left-auto md:right-0 md:translate-x-1/2' : 'md:left-0' }}"
                                    style="background: #b0566f; border: 4px solid #fbf3ee; box-shadow: 0 0 0 2px #c9a24a;"></span>

                                {{-- kartu momen: putih, sudut bulat, bayangan lembut. Di layar lebar teks kartu kiri rata kanan supaya menghadap garis --}}
                                <div class="relative p-6 bg-white rounded-3xl {{ $dikiri ? 'md:text-right' : '' }}"
                                    style="border: 1px solid #f3c1cc; box-shadow: 0 6px 20px rgba(176, 86, 111, 0.12);">

                                    {{-- bunga kecil di pojok atas kartu, sisi pojoknya bergantian mengikuti sisi kartu --}}
                                    <svg class="absolute w-12 h-12 -top-5 {{ $dikiri ? '-left-4' : '-right-4' }}"
                                        style="transform: rotate({{ $dikiri ? '-20' : '20' }}deg);">
                                        <use href="#floral-bunga" width="100%" height="100%" />
                                    </svg>

                                    {{-- waktu momen, cuma tampil kalau diisi --}}
                                    @if ($momen->waktu)
                                        <p class="text-sm uppercase floral-serif" style="color: #c9a24a; letter-spacing: 0.2em;">{{ $momen->waktu }}</p>
                                    @endif

                                    <h3 class="mt-1 text-4xl leading-tight floral-script" style="color: #b0566f;">{{ $momen->judul }}</h3>

                                    {{-- foto momen, cuma tampil kalau ada. Sudut dibulatkan dan diberi bingkai putih tipis --}}
                                    @if ($momen->foto)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($momen->foto) }}"
                                            class="object-cover w-full mt-4 h-52 rounded-2xl">
                                    @endif

                                    {{-- cerita momen, cuma tampil kalau diisi --}}
                                    @if ($momen->cerita)
                                        <p class="mt-4 text-base leading-relaxed floral-serif" style="color: #7a6258;">{{ $momen->cerita }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
            @php $warnaSebelumnya = '#fbf3ee'; @endphp
        @endif



                {{-- SECTION GALERI: hijau sage tua, kolase foto dengan ukuran bervariasi --}}
        @if ($undangan->fotos->count() > 0)
            <div class="relative px-6 pb-24 overflow-hidden pt-28" style="background: #4f6b4a;">

                {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
                @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

                <h2 class="text-6xl text-center floral-script" style="color: #fbf3ee;">Galeri</h2>
                <p class="mt-2 mb-12 text-lg text-center floral-serif" style="color: #c9a24a; letter-spacing: 0.2em;">Potret Kenangan Kami</p>

                {{-- grid kolase: 2 kolom di HP, 3 kolom di layar lebar; tinggi tiap baris dikunci supaya foto bisa memanjang ke bawah (row-span) --}}
                {{-- grid-flow-dense: kotak kosong otomatis diisi foto berikutnya, jadi susunan tetap rapat tanpa celah --}}
                <div class="grid max-w-5xl grid-flow-dense grid-cols-2 gap-3 mx-auto md:grid-cols-3 md:gap-4 auto-rows-[9rem] md:auto-rows-[13rem]">
                    @foreach ($undangan->fotos->sortBy('urutan') as $foto)
                        @php
                            // pola ukuran berulang tiap 5 foto, supaya kolase terasa acak tapi tetap teratur
                            $pola = $loop->index % 5;
                            // 0 = besar (2 kolom x 2 baris), 3 = tinggi (1 kolom x 2 baris), sisanya kotak kecil biasa
                            $ukuran = match ($pola) {
                                0 => 'col-span-2 row-span-2',
                                3 => 'row-span-2',
                                default => '',
                            };
                            // foto tinggi dibuat lengkung seperti jendela, foto lain sudut membulat biasa
                            $bentuk = $pola === 3 ? '9999px 9999px 1rem 1rem' : '1rem';
                        @endphp

                        {{-- w-full h-full: foto mengisi penuh kotaknya; object-cover memotong foto agar tidak gepeng --}}
                        <div class="overflow-hidden {{ $ukuran }}"
                            style="border-radius: {{ $bentuk }}; border: 3px solid rgba(251, 243, 238, 0.9); box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path) }}"
                                class="object-cover w-full h-full transition duration-500 hover:scale-105" alt="Foto galeri">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
            @php $warnaSebelumnya = '#4f6b4a'; @endphp
        @endif
        
        
        {{-- SECTION ACARA: blush, kartu Akad dan Resepsi berdampingan dengan tanggal besar --}}
        @php
            // kumpulkan dua acara dalam satu daftar supaya kartunya cukup ditulis sekali lewat perulangan
            $acaras = collect([
                [
                    'judul' => 'Akad Nikah',
                    'tanggal' => $undangan->akad_tanggal,
                    'mulai' => $undangan->akad_waktu_mulai,
                    'selesai' => $undangan->akad_waktu_selesai,
                    'lokasi' => $undangan->akad_lokasi,
                    'alamat' => $undangan->akad_alamat,
                    'maps' => $undangan->akad_link_maps,
                ],
                [
                    'judul' => 'Resepsi',
                    'tanggal' => $undangan->resepsi_tanggal,
                    'mulai' => $undangan->resepsi_waktu_mulai,
                    'selesai' => $undangan->resepsi_waktu_selesai,
                    'lokasi' => $undangan->resepsi_lokasi,
                    'alamat' => $undangan->resepsi_alamat,
                    'maps' => $undangan->resepsi_link_maps,
                ],
            ])->filter(fn ($a) => $a['tanggal']); // buang acara yang tanggalnya belum diisi
        @endphp

        @if ($acaras->count() > 0)
            <div id="acara" class="relative px-6 pb-24 overflow-hidden pt-28" style="background: #f8dfe4;">

                {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
                @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

                <h2 class="text-6xl text-center floral-script" style="color: #4f6b4a;">Acara</h2>
                <p class="max-w-md mx-auto mt-3 mb-16 text-lg text-center floral-serif" style="color: #7a6258;">
                    Kami bermaksud mengundang Anda dalam acara pernikahan kami pada:
                </p>

                {{-- grid 2 kolom kalau acara ada dua; kalau cuma satu, kartunya dipusatkan dengan lebar terbatas --}}
                <div class="grid max-w-5xl gap-14 mx-auto {{ $acaras->count() > 1 ? 'md:grid-cols-2 md:gap-10' : 'max-w-md' }}">
                    @foreach ($acaras as $acara)
                        {{-- kartu acara: putih, sudut bulat besar, mt-6 memberi ruang buat bunga yang menempel di atasnya --}}
                        <div class="relative px-8 pt-14 pb-10 mt-6 text-center bg-white rounded-[2rem]"
                            style="border: 1px solid #c9a24a; box-shadow: 0 10px 30px rgba(176, 86, 111, 0.15);">

                            {{-- bunga menempel di tengah atas kartu, setengahnya keluar dari tepi --}}
                            <svg class="absolute w-20 h-20 -translate-x-1/2 -top-10 left-1/2"><use href="#floral-bunga" width="100%" height="100%" /></svg>

                            <h3 class="text-5xl floral-script" style="color: #b0566f;">{{ $acara['judul'] }}</h3>

                            @php $tgl = \Carbon\Carbon::parse($acara['tanggal']); @endphp

                            {{-- nama hari kecil di atas, angka tanggal sangat besar sebagai sorotan, lalu bulan dan tahun --}}
                            <p class="mt-6 text-sm uppercase floral-serif" style="color: #c9a24a; letter-spacing: 0.3em;">{{ $tgl->translatedFormat('l') }}</p>
                            <p class="leading-none text-8xl floral-serif" style="color: #4f6b4a; font-weight: 600;">{{ $tgl->format('d') }}</p>
                            <p class="mt-1 text-xl floral-serif" style="color: #7a6258;">{{ $tgl->translatedFormat('F Y') }}</p>

                            {{-- garis emas pendek sebagai pemisah --}}
                            <div class="w-16 mx-auto my-6" style="border-top: 1px solid #c9a24a;"></div>

                            {{-- jam mulai (dan selesai kalau diisi), cuma tampil kalau jam mulai diisi --}}
                            @if ($acara['mulai'])
                                <p class="text-xl floral-serif" style="color: #7a6258;">
                                    {{ \Carbon\Carbon::parse($acara['mulai'])->format('H:i') }}
                                    @if ($acara['selesai'])
                                        - {{ \Carbon\Carbon::parse($acara['selesai'])->format('H:i') }}
                                    @endif
                                    WIB
                                </p>
                            @endif

                            {{-- nama tempat, cuma tampil kalau diisi --}}
                            @if ($acara['lokasi'])
                                <p class="mt-4 text-2xl floral-serif" style="color: #4f6b4a; font-weight: 600;">{{ $acara['lokasi'] }}</p>
                            @endif

                            {{-- alamat lengkap, cuma tampil kalau diisi --}}
                            @if ($acara['alamat'])
                                <p class="mt-1 text-base leading-relaxed floral-serif" style="color: #9a7b6d;">{{ $acara['alamat'] }}</p>
                            @endif

                            {{-- tombol Google Maps, cuma tampil kalau link diisi --}}
                            @if ($acara['maps'])
                                <a href="{{ $acara['maps'] }}" target="_blank"
                                    class="inline-block py-3 mt-6 text-sm transition rounded-full px-7 floral-serif hover:opacity-80"
                                    style="background: #b0566f; color: #fff; letter-spacing: 0.1em;">
                                    Lihat Lokasi
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
            @php $warnaSebelumnya = '#f8dfe4'; @endphp
        @endif


                {{-- SECTION RSVP: krem. Background dan gelombang dipasang di sini (milik tema), isinya komponen Livewire dengan tampilan Floral --}}
        <div class="relative overflow-hidden" style="background: #fbf3ee;">
            @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

            {{-- atribut tampilan memberi tahu RsvpForm untuk memakai file temas/floral/rsvp.blade.php --}}
            <livewire:rsvp-form :undanganId="$undangan->id" tampilan="temas.floral.rsvp" />
        </div>




        {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
        @php $warnaSebelumnya = '#fbf3ee'; @endphp

                {{-- SECTION UCAPAN: hijau sage tua. Background dan gelombang dipasang di sini, isinya komponen Livewire dengan tampilan Floral --}}
        <div class="relative overflow-hidden" style="background: #4f6b4a;">
            @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

            {{-- atribut tampilan memberi tahu UcapanForm untuk memakai file temas/floral/ucapan.blade.php --}}
            <livewire:ucapan-form :undanganId="$undangan->id" tampilan="temas.floral.ucapan" />
        </div>

        {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
        @php $warnaSebelumnya = '#4f6b4a'; @endphp



                {{-- SECTION KADO: blush. Rekening tampil sebagai kartu bank, QRIS di kartu putih. Cuma tampil kalau ada data rekening atau QRIS --}}
        @if ($undangan->rekening_nomor || $undangan->qris_gambar)
            <div class="relative px-6 pb-24 overflow-hidden pt-28" style="background: #f8dfe4;">

                {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
                @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

                <h2 class="text-6xl text-center floral-script" style="color: #4f6b4a;">Kado</h2>
                <p class="max-w-md mx-auto mt-3 text-lg text-center mb-14 floral-serif" style="color: #7a6258;">
                    Doa restu Anda adalah hadiah terbaik. Namun bila ingin memberi tanda kasih, dapat melalui:
                </p>

                {{-- 2 kolom di layar lebar kalau rekening dan QRIS sama-sama ada; kalau cuma satu, dipusatkan dengan lebar terbatas --}}
                <div class="grid items-start max-w-4xl gap-10 mx-auto {{ ($undangan->rekening_nomor && $undangan->qris_gambar) ? 'md:grid-cols-2' : 'max-w-md' }}">

                    {{-- KARTU REKENING: bentuknya seperti kartu bank, gradasi hijau sage dengan tulisan emas dan krem --}}
                    @if ($undangan->rekening_nomor)
                        {{-- x-data khusus kartu ini: variabel copied (feedback 'Tersalin') dan fungsi salin() dengan cara cadangan untuk HTTP --}}
                        <div x-data="{
                                copied: false,
                                salin(teks) {
                                    // cara modern, cuma jalan di HTTPS atau localhost
                                    if (navigator.clipboard && window.isSecureContext) {
                                        navigator.clipboard.writeText(teks);
                                    } else {
                                        // cara cadangan: bikin textarea sementara, pilih isinya, lalu copy
                                        const el = document.createElement('textarea');
                                        el.value = teks;
                                        document.body.appendChild(el);
                                        el.select();
                                        document.execCommand('copy');
                                        document.body.removeChild(el);
                                    }
                                    this.copied = true; // tampilkan tulisan 'Tersalin!'
                                    setTimeout(() => this.copied = false, 2000); // balik normal setelah 2 detik
                                }
                            }"
                            class="text-center">

                            <div class="relative px-7 py-8 text-left overflow-hidden rounded-[1.75rem]"
                                style="background: linear-gradient(135deg, #4f6b4a, #6f8a68); box-shadow: 0 12px 30px rgba(79, 107, 74, 0.35); border: 1px solid #c9a24a;">

                                {{-- bunga besar setengah keluar dari pojok kanan atas kartu, semi transparan sebagai dekorasi --}}
                                <svg class="absolute w-32 h-32 -top-8 -right-8 opacity-30" style="transform: rotate(20deg);"><use href="#floral-bunga" width="100%" height="100%" /></svg>

                                {{-- nama bank --}}
                                @if ($undangan->rekening_bank)
                                    <p class="relative text-lg uppercase floral-serif" style="color: #c9a24a; letter-spacing: 0.3em; font-weight: 600;">{{ $undangan->rekening_bank }}</p>
                                @endif

                                {{-- nomor rekening, besar dengan jarak huruf lebar seperti nomor kartu --}}
                                <p class="relative mt-8 text-3xl floral-serif" style="color: #fbf3ee; letter-spacing: 0.12em; font-weight: 600;">{{ $undangan->rekening_nomor }}</p>

                                {{-- nama pemilik rekening --}}
                                @if ($undangan->rekening_nama)
                                    <p class="relative mt-6 text-sm uppercase floral-serif" style="color: #c9a24a; letter-spacing: 0.2em;">Atas Nama</p>
                                    <p class="relative text-xl floral-serif" style="color: #fbf3ee;">{{ $undangan->rekening_nama }}</p>
                                @endif
                            </div>

                            {{-- tombol salin. @js() mengubah nomor jadi teks JavaScript yang aman, jadi tanda kutip di data tidak bisa merusak kode --}}
                            <button type="button" @click="salin(@js($undangan->rekening_nomor))"
                                class="py-3 mt-6 text-base transition rounded-full px-7 floral-serif hover:opacity-80"
                                style="background: #b0566f; color: #fff; letter-spacing: 0.1em;">
                                <span x-show="!copied">Salin Nomor Rekening</span>
                                <span x-show="copied">Tersalin!</span>
                            </button>
                        </div>
                    @endif

                    {{-- KARTU QRIS: kartu putih berbingkai emas dengan bunga di tengah atas, sama gayanya dengan kartu Acara --}}
                    @if ($undangan->qris_gambar)
                        <div class="relative px-8 pt-14 pb-8 mt-6 text-center bg-white rounded-[2rem]"
                            style="border: 1px solid #c9a24a; box-shadow: 0 10px 30px rgba(176, 86, 111, 0.15);">

                            <svg class="absolute w-20 h-20 -translate-x-1/2 -top-10 left-1/2"><use href="#floral-bunga" width="100%" height="100%" /></svg>

                            <p class="text-4xl floral-script" style="color: #b0566f;">Scan QRIS</p>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($undangan->qris_gambar) }}"
                                class="object-contain w-56 h-56 mx-auto mt-4" alt="QRIS">
                        </div>
                    @endif
                </div>
            </div>



                    {{-- SECTION LIVE STREAMING: hijau sage tua. Cuma tampil kalau link streaming diisi --}}
        @if ($undangan->link_streaming)
            <div class="relative px-6 pb-24 overflow-hidden text-center pt-28" style="background: #4f6b4a;">

                {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
                @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

                <div class="max-w-md mx-auto">
                    {{-- lingkaran emas berisi ikon play. Dua lingkaran (luar tipis, dalam tebal) memberi kesan bingkai ganda --}}
                    <div class="flex items-center justify-center w-32 h-32 mx-auto rounded-full"
                        style="border: 1px solid #c9a24a; outline: 1px solid rgba(201, 162, 74, 0.45); outline-offset: 8px;">
                        <svg class="w-14 h-14" fill="#c9a24a" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>

                    <h2 class="mt-10 text-6xl floral-script" style="color: #fbf3ee;">Live Streaming</h2>

                    <p class="mt-4 text-lg leading-relaxed floral-serif" style="color: #e6ecdf;">
                        Bagi keluarga dan sahabat yang berhalangan hadir, silakan saksikan acara kami secara langsung melalui tautan berikut.
                    </p>

                    {{-- rel="noopener" supaya halaman tujuan tidak bisa mengakses halaman undangan ini --}}
                    <a href="{{ $undangan->link_streaming }}" target="_blank" rel="noopener"
                        class="inline-block py-3 mt-8 text-lg transition rounded-full px-9 floral-serif hover:opacity-80"
                        style="background: #b0566f; color: #fff; letter-spacing: 0.1em;">
                        Tonton Siaran Langsung
                    </a>
                </div>
            </div>


                    {{-- SECTION PROTOKOL KESEHATAN: krem, teks statis (sama untuk semua undangan), tiap poin berupa lingkaran emas --}}
        <div class="relative px-6 pb-24 overflow-hidden text-center pt-28" style="background: #fbf3ee;">

            {{-- gelombang di tepi atas, diwarnai warna section sebelumnya --}}
            @include('temas.floral.partials.gelombang', ['warna' => $warnaSebelumnya])

            <h2 class="text-6xl floral-script" style="color: #4f6b4a;">Protokol Kesehatan</h2>
            <p class="max-w-md mx-auto mt-3 text-lg mb-14 floral-serif" style="color: #7a6258;">
                Demi kenyamanan bersama, mohon perhatikan hal-hal berikut saat menghadiri acara.
            </p>

            @php
                // daftar protokol, gampang ditambah atau diubah lewat array ini
                $protokol = [
                    'Gunakan masker bila sedang kurang sehat',
                    'Cuci tangan atau gunakan hand sanitizer',
                    'Jaga jarak dan hindari kerumunan',
                    'Tetap di rumah bila sedang sakit',
                ];
            @endphp

            {{-- 2 kolom di HP, 4 kolom di layar lebar --}}
            <div class="grid max-w-4xl grid-cols-2 mx-auto gap-x-6 gap-y-10 md:grid-cols-4">
                @foreach ($protokol as $item)
                    <div class="flex flex-col items-center">
                        {{-- lingkaran emas berisi centang hijau --}}
                        <div class="flex items-center justify-center w-20 h-20 rounded-full"
                            style="border: 1px solid #c9a24a; outline: 1px solid rgba(201, 162, 74, 0.45); outline-offset: 5px; background: #fff;">
                            <svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke="#4f6b4a" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="max-w-[10rem] mt-5 text-base leading-snug floral-serif" style="color: #7a6258;">{{ $item }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
        @php $warnaSebelumnya = '#fbf3ee'; @endphp

            {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
            @php $warnaSebelumnya = '#4f6b4a'; @endphp
        @endif
            {{-- catat warna section ini sebagai "warna sebelumnya" untuk gelombang section berikutnya --}}
            @php $warnaSebelumnya = '#f8dfe4'; @endphp
        @endif
        {{-- penanda sementara: galeri, acara, dan section lain menyusul di langkah berikutnya --}}
        <div class="px-4 py-24 text-center floral-serif" style="background: #fbf3ee; color: #9a7b6d;">
            <p>Section lain untuk tema Floral menyusul.</p>
        </div>
        
    </x-undangan.pembungkus>

@endsection
