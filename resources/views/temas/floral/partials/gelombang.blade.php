{{-- gelombang pemisah antar section. Variabel $warna dikirim saat dipanggil: isi dengan warna section SEBELUMNYA --}}
{{-- absolute di tepi atas section, top -1px supaya tidak ada garis tipis di sambungannya --}}
<svg class="absolute left-0 w-full pointer-events-none" style="top: -1px; height: 48px;"
    viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
    {{-- bentuk bergelombang yang menggantung dari atas, diwarnai dengan warna section sebelumnya --}}
    <path d="M0,0 L1440,0 L1440,36 C1200,84 960,6 720,40 C480,74 240,10 0,48 Z" fill="{{ $warna }}" />
</svg>