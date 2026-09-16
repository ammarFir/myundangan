@extends('layouts.site')

@section('title', $undangan->nama_pria . ' & ' . $undangan->nama_wanita)

@section('content')

    {{-- x-data mendefinisikan variabel 'open' (status cover sudah dibuka atau belum)
         x-init dijalankan sekali saat halaman dimuat, langsung kunci scroll body
         x-effect dijalankan setiap kali 'open' berubah, buka kunci scroll kalau sudah true --}}
    <div x-data="{ open: false }" x-init="document.body.style.overflow = 'hidden'"
        x-effect="document.body.style.overflow = open ? 'auto' : 'hidden'">

        {{-- COVER: nutup seluruh layar (fixed inset-0), z-50 biar selalu di paling depan --}}
        {{-- x-show="!open" artinya cover ini tampil selama belum diklik --}}
        {{-- x-transition bikin animasi fade out yang halus pas ditutup --}}
        <div x-show="!open" x-transition:leave="transition ease-in-out duration-700"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center min-h-screen px-4 bg-gray-900">

            {{-- card sempit ala HP, di-center di tengah layar --}}
            <div class="w-full max-w-md py-20 text-center text-white">

                {{-- label kecil di atas nama --}}
                <p class="text-sm tracking-widest text-gray-400 uppercase">
                    The Wedding Of
                </p>

                {{-- nama pasangan, font serif biar elegan --}}
                <h1 class="mt-4 font-serif text-5xl font-bold">
                    {{ $undangan->nama_pria }}
                    <span class="block my-2 text-2xl font-normal">&</span>
                    {{ $undangan->nama_wanita }}
                </h1>

                {{-- tanggal acara, cuma tampil kalau ada isinya --}}
                @if ($undangan->tanggal_acara)
                    <p class="mt-6 text-gray-300">
                        {{ \Carbon\Carbon::parse($undangan->tanggal_acara)->translatedFormat('l, d F Y') }}
                    </p>
                @endif

                {{-- tombol jadi <button>, bukan <a> lagi, karena harus jalanin 2 aksi sekaligus:
                     1. ubah 'open' jadi true (bikin cover fade out & scroll ke-unlock)
                     2. scroll halaman ke section #detail --}}
                <button type="button"
                    @click="open = true; $nextTick(() => document.getElementById('detail').scrollIntoView({ behavior: 'smooth' }))"
                    class="inline-block px-8 py-3 mt-10 text-sm font-semibold text-white transition border border-white rounded-full hover:bg-white hover:text-gray-900">
                    Buka Undangan
                </button>

                {{-- request()->query('to') ambil value dari URL, contoh: ?to=Andi+Sanjaya --}}
                @if (request()->query('to'))
                    <div class="mt-10 text-sm text-gray-400">
                        <p>Kepada Yth.</p>
                        <p class="mt-1 text-lg font-semibold text-white">{{ e(request()->query('to')) }}</p>
                    </div>
                @endif

            </div>
        </div>

        {{-- spacer setinggi 1 layar, biar pas cover di-fade-out, halaman gak "loncat" tiba-tiba ke section ayat --}}
        <div class="min-h-screen bg-gray-900"></div>

        {{-- section ayat/kutipan, cuma tampil kalau field ayat_teks ada isinya --}}
        @if ($undangan->ayat_teks)
            <div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">
                <p class="font-serif text-lg italic leading-relaxed text-gray-700">
                    "{{ $undangan->ayat_teks }}"
                </p>
                @if ($undangan->ayat_arti)
                    <p class="mt-4 text-sm leading-relaxed text-gray-500">
                        {{ $undangan->ayat_arti }}
                    </p>
                @endif
                @if ($undangan->ayat_sumber)
                    <p class="mt-3 text-sm font-semibold text-gray-900">
                        {{ $undangan->ayat_sumber }}
                    </p>
                @endif
            </div>
        @endif

        {{-- section detail acara, id="detail" dipakai sebagai tujuan scroll tombol di atas --}}
        <div id="detail" class="max-w-md px-4 py-16 mx-auto text-center">

            {{-- lokasi acara --}}
            @if ($undangan->lokasi)
                <div class="mt-8">
                    <h2 class="font-semibold text-gray-900">Lokasi</h2>
                    <p class="mt-1 text-gray-600">{{ $undangan->lokasi }}</p>
                    @if ($undangan->alamat)
                        <p class="text-sm text-gray-500">{{ $undangan->alamat }}</p>
                    @endif
                    @if ($undangan->link_maps)
                        <a href="{{ $undangan->link_maps }}" target="_blank"
                            class="inline-block px-4 py-2 mt-3 text-sm font-semibold text-gray-900 border border-gray-300 rounded-full hover:bg-gray-50">
                            Lihat di Google Maps
                        </a>
                    @endif
                </div>
            @endif

            {{-- link live streaming, kalau ada --}}
            @if ($undangan->link_streaming)
                <div class="mt-8">
                    <a href="{{ $undangan->link_streaming }}" target="_blank"
                        class="inline-block px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                        Tonton Live Streaming
                    </a>
                </div>
            @endif

        </div>

    </div>

@endsection