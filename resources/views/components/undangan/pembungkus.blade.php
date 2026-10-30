{{-- @props: data undangan dikirim dari tema yang manggil pembungkus ini --}}
@props(['undangan'])

{{-- pembungkus perilaku dasar undangan. Semua yang ada di dalamnya (cover + isi halaman) milik tema --}}
{{-- x-data: variabel 'open' (cover sudah dibuka atau belum) + fungsi bukaUndangan() untuk tombol di cover --}}
{{-- x-init: jalan sekali saat halaman dimuat
     1. history.scrollRestoration = 'manual' : matiin fitur browser yang nginget posisi scroll terakhir
     2. window.scrollTo(0, 0) : paksa halaman mulai dari paling atas
     3. document.body.style.overflow = 'hidden' : kunci scroll sampai undangan dibuka --}}
{{-- x-effect: jalan tiap 'open' berubah, buka kunci scroll kalau sudah true --}}
<div x-data="{
        open: false,
        // dipanggil tombol di cover tema manapun
        bukaUndangan() {
            mulaiMusikUndangan(); // harus paling awal supaya masih dianggap hasil klik user oleh browser
            this.open = true; // cover menghilang, scroll terbuka
            // scroll halus ke elemen penanda id='mulai' (awal isi halaman), disediakan oleh tema
            this.$nextTick(() => {
                const tujuan = document.getElementById('mulai');
                if (tujuan) tujuan.scrollIntoView({ behavior: 'smooth' });
            });
        }
    }"
    x-init="history.scrollRestoration = 'manual'; window.scrollTo(0, 0); document.body.style.overflow = 'hidden'"
    x-effect="document.body.style.overflow = open ? 'auto' : 'hidden'">

    {{-- musik latar (komponen bersama) --}}
    <x-undangan.musik :undangan="$undangan" />

    {{-- watermark preview kalau undangan belum aktif (komponen bersama) --}}
    <x-undangan.watermark :undangan="$undangan" />

    {{-- {{ $slot }} = tempat isi dari tema (cover + semua section) masuk --}}
    {{ $slot }}
</div>