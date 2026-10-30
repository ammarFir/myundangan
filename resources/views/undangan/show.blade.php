@extends('layouts.site')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', $undangan->nama_pria . ' & ' . $undangan->nama_wanita)

@section('content')




    {{-- x-data mendefinisikan variabel 'open' (status cover sudah dibuka atau belum)
         x-init dijalankan sekali saat halaman dimuat, langsung kunci scroll body
         x-effect dijalankan setiap kali 'open' berubah, buka kunci scroll kalau sudah true --}}
      {{-- x-init sekarang ngelakuin 3 hal pas halaman dimuat:
         1. history.scrollRestoration = 'manual' : matiin fitur browser yang nginget posisi scroll terakhir
         2. window.scrollTo(0, 0) : paksa halaman mulai dari paling atas
         3. document.body.style.overflow = 'hidden' : kunci scroll sampai undangan dibuka --}}
        <div x-data="{
            open: false,
        }"
        x-init="history.scrollRestoration = 'manual'; window.scrollTo(0, 0); document.body.style.overflow = 'hidden'"
        x-effect="document.body.style.overflow = open ? 'auto' : 'hidden'">

                    {{-- musik dari YouTube: player disembunyiin, yang dipakai cuma suaranya --}}
      
              {{-- musik sekarang jadi komponen bersama, dipakai semua tema --}}
        <x-undangan.musik :undangan="$undangan" />

            {{-- watermark preview sekarang jadi komponen bersama, dipakai semua tema --}}
        <x-undangan.watermark :undangan="$undangan" />

            {{-- pembungkus perilaku dasar: open, kunci scroll, musik, dan watermark --}}
    <x-undangan.pembungkus :undangan="$undangan">

        
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

                {{-- tanggal akad, cuma tampil kalau ada isinya --}}
                @if ($undangan->akad_tanggal)
                <p class="mt-6 text-gray-300">
                    {{ \Carbon\Carbon::parse($undangan->akad_tanggal)->translatedFormat('l, d F Y')}}
                </p>
                @endif
                {{-- tanggal acara, cuma tampil kalau ada isinya --}}
                                               {{-- countdown sekarang jadi komponen bersama, dipakai semua tema --}}
                <x-undangan.countdown :undangan="$undangan" />

                {{-- tombol jadi <button>, bukan <a> lagi, karena harus jalanin 2 aksi sekaligus:
                     1. ubah 'open' jadi true (bikin cover fade out & scroll ke-unlock)
                     2. scroll halaman ke section #detail --}}
                <button type="button"
                                       @click="bukaUndangan()"
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

                {{-- HERO: isinya sama kayak cover, tapi ini bagian dari halaman biasa (bukan overlay),
             jadi tetap ada pas user scroll balik ke atas setelah undangan dibuka --}}
        <div class="flex items-center justify-center min-h-screen px-4 bg-gray-900">
            <div class="w-full max-w-md py-20 text-center text-white">

                {{-- label kecil di atas nama --}}
                <p class="text-sm tracking-widest text-gray-400 uppercase">
                    The Wedding Of
                </p>

                {{-- nama pasangan --}}
                <h1 class="mt-4 font-serif text-5xl font-bold">
                    {{ $undangan->nama_pria }}
                    <span class="block my-2 text-2xl font-normal">&</span>
                    {{ $undangan->nama_wanita }}
                </h1>

                {{-- tanggal akad, cuma tampil kalau ada isinya --}}
                @if ($undangan->akad_tanggal)
                    <p class="mt-6 text-gray-300">
                        {{ \Carbon\Carbon::parse($undangan->akad_tanggal)->translatedFormat('l, d F Y') }}
                    </p>
                @endif

                {{-- nama tamu dari URL (?to=...), cuma tampil kalau ada --}}
                @if (request()->query('to'))
                    <div class="mt-10 text-sm text-gray-400">
                        <p>Kepada Yth.</p>
                        <p class="mt-1 text-lg font-semibold text-white">{{ e(request()->query('to')) }}</p>
                    </div>
                @endif

            </div>
        </div>

        {{-- penanda titik tujuan tombol "Buka Undangan", letaknya tepat setelah hero (awal section ayat dst) --}}
        <div id="mulai"></div>

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

                {{-- section love story, cuma tampil kalau undangan punya minimal 1 momen --}}
        @if ($undangan->loveStories->count() > 0)
            <div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">
                <h2 class="mb-10 font-serif text-2xl font-bold text-gray-900">Love Story</h2>

                {{-- garis vertikal timeline: border kiri, tiap momen digeser ke kanan lewat pl-6 --}}
                <div class="space-y-10 text-left border-l-2 border-gray-200">
                    @foreach ($undangan->loveStories as $momen)
                        <div class="relative pl-6">
                            {{-- titik penanda di atas garis, posisinya digeser ke kiri biar pas di tengah garis --}}
                            <span class="absolute w-3 h-3 bg-gray-900 rounded-full -left-[7px] top-1.5"></span>

                            {{-- waktu momen, cuma tampil kalau diisi --}}
                            @if ($momen->waktu)
                                <p class="text-xs tracking-widest text-gray-400 uppercase">{{ $momen->waktu }}</p>
                            @endif

                            <h3 class="mt-1 font-serif text-lg font-semibold text-gray-900">{{ $momen->judul }}</h3>

                            {{-- foto momen, cuma tampil kalau ada --}}
                            @if ($momen->foto)
                                <img src="{{ Storage::url($momen->foto) }}"
                                    class="object-cover w-full h-48 mt-3 rounded-xl">
                            @endif

                            {{-- cerita momen, cuma tampil kalau diisi --}}
                            @if ($momen->cerita)
                                <p class="mt-3 text-sm leading-relaxed text-gray-600">{{ $momen->cerita }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif


                {{-- section galeri foto, cuma tampil kalau undangan punya minimal 1 foto --}}
        @if ($undangan->fotos->count() > 0)
            <div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">
                <h2 class="mb-10 font-serif text-2xl font-bold text-gray-900">Galeri</h2>

                {{-- grid 2 kolom, foto diurutkan sesuai kolom urutan --}}
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($undangan->fotos->sortBy('urutan') as $foto)
                        <img src="{{ Storage::url($foto->path) }}" class="object-cover w-full h-40 rounded-lg">
                    @endforeach
                </div>
            </div>
        @endif

        <livewire:rsvp-form :undanganId="$undangan->id" />

                    {{-- section kado, cuma tampil kalau ada data rekening atau QRIS --}}
        @if ($undangan->rekening_nomor || $undangan->qris_gambar)
            <div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">
                <h2 class="mb-2 font-serif text-2xl font-bold text-gray-900">Kado</h2>
                <p class="mb-8 text-sm text-gray-500">Doa restu Anda adalah hadiah terbaik. Namun bila ingin memberi tanda kasih, dapat melalui:</p>

                {{-- kartu rekening bank --}}
                @if ($undangan->rekening_nomor)
                    {{-- x-data bikin variabel 'copied' buat nampilin feedback setelah tombol Salin diklik --}}
                                                          <div class="p-6 mb-6 border border-gray-200 rounded-2xl" x-data="{
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
                    }">
                        @if ($undangan->rekening_bank)
                            <p class="text-sm font-semibold text-gray-900">{{ $undangan->rekening_bank }}</p>
                        @endif

                        <p class="mt-2 font-mono text-lg tracking-wider text-gray-900">{{ $undangan->rekening_nomor }}</p>

                        @if ($undangan->rekening_nama)
                            <p class="mt-1 text-sm text-gray-500">a.n. {{ $undangan->rekening_nama }}</p>
                        @endif

                        {{-- navigator.clipboard.writeText nyalin nomor rekening ke clipboard --}}
                        <button type="button"
                                                        @click="salin('{{ $undangan->rekening_nomor }}')"
                            class="px-4 py-2 mt-4 text-sm font-semibold text-gray-900 border border-gray-300 rounded-full hover:bg-gray-50">
                            <span x-show="!copied">Salin Nomor Rekening</span>
                            <span x-show="copied">Tersalin!</span>
                        </button>
                    </div>
                @endif

                {{-- kartu QRIS --}}
                @if ($undangan->qris_gambar)
                    <div class="p-6 border border-gray-200 rounded-2xl">
                        <p class="mb-4 text-sm font-semibold text-gray-900">QRIS</p>
                        <img src="{{ Storage::url($undangan->qris_gambar) }}" class="object-contain w-48 h-48 mx-auto">
                    </div>
                @endif
            </div>
        @endif 

        <livewire:ucapan-form :undanganId="$undangan->id" />

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
           

        </div>



                {{-- section live streaming, cuma tampil kalau link-nya diisi --}}
        @if ($undangan->link_streaming)
            <div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">
                <h2 class="mb-2 font-serif text-2xl font-bold text-gray-900">Live Streaming</h2>
                <p class="mb-6 text-sm text-gray-500">
                    Bagi keluarga dan sahabat yang berhalangan hadir, silakan saksikan acara kami secara langsung melalui tautan berikut.
                </p>

                <a href="{{ $undangan->link_streaming }}" target="_blank" rel="noopener"
                    class="inline-block px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                    Tonton Live Streaming
                </a>
            </div>
        @endif

                {{-- section protokol kesehatan, teks statis (sama untuk semua undangan) --}}
        <div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">
            <h2 class="mb-2 font-serif text-2xl font-bold text-gray-900">Protokol Kesehatan</h2>
            <p class="mb-8 text-sm text-gray-500">
                Demi kenyamanan bersama, mohon perhatikan hal-hal berikut saat menghadiri acara.
            </p>

            {{-- daftar protokol, dibuat lewat array biar gampang nambah/ubah item --}}
            @php
                $protokol = [
                    'Gunakan masker bila sedang kurang sehat',
                    'Cuci tangan atau gunakan hand sanitizer',
                    'Jaga jarak dan hindari kerumunan',
                    'Tetap di rumah bila sedang sakit',
                ];
            @endphp

            {{-- grid 2 kolom, tiap item berupa kartu kecil --}}
            <div class="grid grid-cols-2 gap-3">
                @foreach ($protokol as $item)
                    <div class="flex flex-col items-center p-4 border border-gray-200 rounded-xl">
                        {{-- ikon centang bulat, sebagai penanda tiap poin --}}
                        <div class="flex items-center justify-center w-10 h-10 mb-3 text-white bg-gray-900 rounded-full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-xs leading-relaxed text-gray-600">{{ $item }}</p>
                    </div>
                @endforeach
            </div>
        </div>



                {{-- FOOTER undangan: kalimat penutup + nama pasangan + credit --}}
        <div class="px-4 py-16 text-center text-white bg-gray-900">
            {{-- kalimat penutup, max-w-md biar teks gak melebar --}}
            <p class="max-w-md mx-auto text-sm leading-relaxed text-gray-300">
                Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir
                dan memberikan doa restu.
            </p>

            {{-- ucapan terima kasih --}}
            <p class="mt-8 text-xs tracking-widest text-gray-400 uppercase">Kami yang berbahagia</p>

            {{-- nama pasangan, pakai font serif biar senada dengan cover --}}
            <h2 class="mt-3 font-serif text-3xl font-bold">
                {{ $undangan->nama_pria }} & {{ $undangan->nama_wanita }}
            </h2>

            {{-- garis pemisah tipis --}}
            <div class="w-16 mx-auto mt-10 border-t border-gray-700"></div>

            {{-- credit, ganti "Undanganku" kalau nama platformnya nanti beda --}}
            <p class="mt-6 text-xs text-gray-500">
                Dibuat dengan Undanganku
            </p>
        </div>

        </x-undangan.pembungkus>

@endsection