@extends('layouts.site')
@php use Illuminate\Support\Facades\Storage; @endphp

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


        {{-- section mempelai, tampil kalau salah satu nama lengkap ada isinya --}}
        @if ($undangan->nama_lengkap_pria || $undangan->nama_lengkap_wanita)
            <div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">
                <h2 class="mb-10 font-serif text-2xl font-bold text-gray-900">Mempelai</h2>

                <div class="grid grid-cols-1 gap-10">

                    {{-- kartu mempelai pria --}}
                    @if ($undangan->nama_lengkap_pria)
                        <div>
                            {{-- foto ditampilkan bulat, cuma muncul kalau ada file-nya --}}
                            @if ($undangan->foto_pria)
                                <img src="{{ Storage::url($undangan->foto_pria) }}"
                                    class="object-cover w-32 h-32 mx-auto mb-4 rounded-full">
                            @endif

                            <h3 class="font-serif text-xl font-semibold text-gray-900">
                                {{ $undangan->nama_lengkap_pria }}
                            </h3>

                            @if ($undangan->anak_ke_pria)
                                <p class="mt-1 text-sm text-gray-600">{{ $undangan->anak_ke_pria }}</p>
                            @endif

                            @if ($undangan->orang_tua_pria)
                                <p class="text-sm text-gray-500">{{ $undangan->orang_tua_pria }}</p>
                            @endif

                            @if ($undangan->instagram_pria)
                                <a href="https://instagram.com/{{ ltrim($undangan->instagram_pria, '@') }}"
                                    target="_blank" class="inline-block mt-2 text-sm text-gray-900 underline">
                                    @{{ ltrim($undangan->instagram_pria, '@') }}
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- kartu mempelai wanita --}}
                    @if ($undangan->nama_lengkap_wanita)
                        <div>
                            @if ($undangan->foto_wanita)
                                <img src="{{ Storage::url($undangan->foto_wanita) }}"
                                    class="object-cover w-32 h-32 mx-auto mb-4 rounded-full">
                            @endif

                            <h3 class="font-serif text-xl font-semibold text-gray-900">
                                {{ $undangan->nama_lengkap_wanita }}
                            </h3>

                            @if ($undangan->anak_ke_wanita)
                                <p class="mt-1 text-sm text-gray-600">{{ $undangan->anak_ke_wanita }}</p>
                            @endif

                            @if ($undangan->orang_tua_wanita)
                                <p class="text-sm text-gray-500">{{ $undangan->orang_tua_wanita }}</p>
                            @endif

                            @if ($undangan->instagram_wanita)
                                <a href="https://instagram.com/{{ ltrim($undangan->instagram_wanita, '@') }}"
                                    target="_blank" class="inline-block mt-2 text-sm text-gray-900 underline">
                                    @{{ ltrim($undangan->instagram_wanita, '@') }}
                                </a>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        @endif


       {{-- section detail acara, id="detail" dipakai sebagai tujuan scroll tombol di atas --}}
        <div id="detail" class="max-w-md px-4 py-16 mx-auto text-center">

            <h2 class="mb-10 font-serif text-2xl font-bold text-gray-900">Acara</h2>

            {{-- card Akad Nikah, cuma tampil kalau tanggal akad ada isinya --}}
            @if ($undangan->akad_tanggal)
                <div class="p-6 mb-8 border border-gray-200 rounded-2xl">
                    <h3 class="font-semibold text-gray-900">Akad Nikah</h3>

                    <p class="mt-2 text-gray-600">
                        {{ \Carbon\Carbon::parse($undangan->akad_tanggal)->translatedFormat('l, d F Y') }}
                    </p>

                    {{-- tampilkan rentang jam mulai-selesai, cuma kalau ada isinya --}}
                    @if ($undangan->akad_waktu_mulai)
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($undangan->akad_waktu_mulai)->format('H:i') }}
                            @if ($undangan->akad_waktu_selesai)
                                - {{ \Carbon\Carbon::parse($undangan->akad_waktu_selesai)->format('H:i') }}
                            @endif
                            WIB
                        </p>
                    @endif

                    @if ($undangan->akad_lokasi)
                        <p class="mt-3 font-medium text-gray-900">{{ $undangan->akad_lokasi }}</p>
                    @endif

                    @if ($undangan->akad_alamat)
                        <p class="text-sm text-gray-500">{{ $undangan->akad_alamat }}</p>
                    @endif

                    @if ($undangan->akad_link_maps)
                        <a href="{{ $undangan->akad_link_maps }}" target="_blank"
                            class="inline-block px-4 py-2 mt-3 text-sm font-semibold text-gray-900 border border-gray-300 rounded-full hover:bg-gray-50">
                            Lihat di Google Maps
                        </a>
                    @endif
                </div>
            @endif

            {{-- card Resepsi, cuma tampil kalau tanggal resepsi ada isinya --}}
            @if ($undangan->resepsi_tanggal)
                <div class="p-6 mb-8 border border-gray-200 rounded-2xl">
                    <h3 class="font-semibold text-gray-900">Resepsi</h3>

                    <p class="mt-2 text-gray-600">
                        {{ \Carbon\Carbon::parse($undangan->resepsi_tanggal)->translatedFormat('l, d F Y') }}
                    </p>

                    @if ($undangan->resepsi_waktu_mulai)
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($undangan->resepsi_waktu_mulai)->format('H:i') }}
                            @if ($undangan->resepsi_waktu_selesai)
                                - {{ \Carbon\Carbon::parse($undangan->resepsi_waktu_selesai)->format('H:i') }}
                            @endif
                            WIB
                        </p>
                    @endif

                    @if ($undangan->resepsi_lokasi)
                        <p class="mt-3 font-medium text-gray-900">{{ $undangan->resepsi_lokasi }}</p>
                    @endif

                    @if ($undangan->resepsi_alamat)
                        <p class="text-sm text-gray-500">{{ $undangan->resepsi_alamat }}</p>
                    @endif

                    @if ($undangan->resepsi_link_maps)
                        <a href="{{ $undangan->resepsi_link_maps }}" target="_blank"
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