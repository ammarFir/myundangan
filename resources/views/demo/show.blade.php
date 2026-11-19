@extends('layouts.site')

{{-- judul tab browser --}}
@section('title', 'Demo Tema ' . $tema['nama'] . ' - Undanganku')

@section('content')

    <div class="min-h-screen bg-gray-50">

        {{-- HEADER: logo di kiri, tombol di kanan --}}
        <header class="px-4 py-4 bg-white border-b border-gray-100 md:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3 mx-auto max-w-7xl">
                <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">Undanganku</a>

                <div class="flex items-center gap-3">
                    {{-- kembali ke section Tema di landing page --}}
                    <a href="{{ url('/') }}#themes" class="text-sm font-medium text-gray-600 hover:text-gray-900">Semua Tema</a>

                    {{-- sudah login ke dashboard, belum login ke halaman daftar --}}
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}"
                        class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                        Pakai Tema Ini
                    </a>
                </div>
            </div>
        </header>

        {{-- JUDUL TEMA + PENGGANTI TEMA --}}
        <div class="px-4 pt-10 mx-auto text-center max-w-7xl md:px-8">
            <h1 class="font-serif text-4xl font-bold text-gray-900">{{ $tema['nama'] }}</h1>
            <p class="max-w-xl mx-auto mt-2 text-gray-600">{{ $tema['deskripsi'] }}</p>

            {{-- tombol pil untuk pindah ke demo tema lain, otomatis mengikuti isi config/temas.php --}}
            <div class="flex flex-wrap justify-center gap-2 mt-6">
                @foreach ($daftarTema as $kunci => $item)
                    {{-- lewati tema yang belum punya undangan demo --}}
                    @continue(empty($item['demo']))
                    <a href="{{ route('demo.show', $kunci) }}"
                        class="px-4 py-2 text-sm border rounded-full {{ $kunci === $kunciTema ? 'bg-gray-900 text-white border-gray-900' : 'text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                        {{ $item['nama'] }}
                    </a>
                @endforeach
            </div>

            <p class="mt-6 text-sm text-gray-500">Klik "Buka Undangan" di dalam bingkai untuk mencoba seperti tamu sungguhan.</p>
        </div>

        {{-- DUA BINGKAI: kiri versi web, kanan versi HP --}}
        <div class="flex items-start justify-center gap-10 px-4 py-12 mx-auto max-w-7xl md:px-8">

            {{-- BINGKAI WEB: disembunyikan di layar kecil (hidden), tampil mulai lg. flex-1 min-w-0 = mengisi sisa lebar --}}
            {{-- x-data: undangan dirender di lebar desktop tetap (lebarAsli), lalu diperkecil (scale) supaya muat di bingkai --}}
            <div class="flex-1 hidden min-w-0 lg:block"
                x-data="{
                    escala: 1, // faktor pengecilan, dihitung ulang otomatis
                    lebarAsli: 1280, // lebar layar desktop virtual di dalam iframe, supaya layout desktop tema tampil
                    tinggiArea: 600, // tinggi area tampilan di halaman, dalam px
                    // hitung faktor pengecilan = lebar area saat ini dibagi lebar asli
                    hitung() { this.escala = this.$refs.area.clientWidth / this.lebarAsli; }
                }"
                x-init="hitung(); new ResizeObserver(() => hitung()).observe($refs.area)">

                <p class="mb-3 text-sm font-semibold text-center text-gray-500">Versi Web</p>

                {{-- bingkai jendela browser --}}
                <div class="overflow-hidden bg-white border border-gray-300 shadow-2xl rounded-xl">
                    {{-- bilah atas: tiga titik dan kolom alamat --}}
                    <div class="flex items-center gap-3 px-4 py-2.5 bg-gray-100 border-b border-gray-200">
                        <div class="flex gap-1.5">
                            <span class="w-3 h-3 bg-red-400 rounded-full"></span>
                            <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
                            <span class="w-3 h-3 bg-green-400 rounded-full"></span>
                        </div>
                        {{-- preg_replace membuang https:// dari alamat supaya tampil rapi --}}
                        <div class="flex-1 px-3 py-1 text-xs text-gray-500 truncate bg-white rounded-md">
                            {{ preg_replace('~^https?://~', '', $alamatDemo) }}
                        </div>
                    </div>

                    {{-- area tampilan: tingginya dikunci, isi yang melebihi dipotong (overflow-hidden) --}}
                    <div x-ref="area" class="overflow-hidden bg-white" :style="'height: ' + tinggiArea + 'px'">
                        {{-- iframe selebar lebarAsli lalu diperkecil; tingginya dibagi escala supaya setelah diperkecil pas memenuhi area --}}
                        <iframe src="{{ $alamatDemo }}" title="Demo {{ $tema['nama'] }} versi web" loading="lazy"
                            :style="'width: ' + lebarAsli + 'px; height: ' + (tinggiArea / escala) + 'px; transform: scale(' + escala + '); transform-origin: top left; border: 0;'">
                        </iframe>
                    </div>
                </div>
            </div>

            {{-- BINGKAI HP: sama gayanya dengan mockup di hero landing, tapi dibuat 340px supaya layar dalamnya 316px (layout HP) --}}
            <div class="shrink-0">
                <p class="mb-3 text-sm font-semibold text-center text-gray-500">Versi HP</p>

                <div class="relative w-[340px] h-[700px] bg-gray-900 rounded-[3rem] p-3 shadow-2xl">
                    {{-- poni (notch) di tengah atas --}}
                    <div class="absolute z-10 w-32 h-6 -translate-x-1/2 bg-gray-900 top-3 left-1/2 rounded-b-2xl"></div>

                    {{-- layar HP: iframe memenuhi seluruh layar, lebarnya otomatis 316px jadi tema memakai layout HP --}}
                    <div class="relative w-full h-full rounded-[2.25rem] overflow-hidden bg-white">
                        <iframe src="{{ $alamatDemo }}" title="Demo {{ $tema['nama'] }} versi HP"
                            class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection