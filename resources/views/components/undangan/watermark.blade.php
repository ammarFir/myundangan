{{-- @props: daftar data yang harus dikirim saat komponen dipanggil. Di sini cuma butuh data undangan --}}
@props(['undangan'])

{{-- WATERMARK PREVIEW: cuma tampil kalau undangan belum aktif (belum dibayar atau belum disetujui admin) --}}
@if ($undangan->status !== 'active')
    {{-- lapisan menutupi layar, z-40 (di bawah cover z-50) dan pointer-events-none supaya klik tetap tembus ke halaman --}}
    <div class="fixed inset-0 z-40 pointer-events-none"
        style="background-image: url(&quot;data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='240' height='160'><text x='120' y='80' text-anchor='middle' dominant-baseline='middle' transform='rotate(-25 120 80)' font-family='sans-serif' font-size='26' font-weight='700' letter-spacing='4' fill='rgba(107,114,128,0.22)'>PREVIEW</text></svg>&quot;); background-repeat: repeat;">
    </div>

    {{-- label kecil di bawah layar, ngasih tahu pemilik kalau ini belum versi final --}}
    <div class="fixed z-40 px-4 py-1.5 text-xs font-semibold text-white -translate-x-1/2 bg-gray-900 rounded-full pointer-events-none bottom-4 left-1/2 opacity-80">
        Mode Preview, undangan belum diaktifkan
    </div>
@endif  