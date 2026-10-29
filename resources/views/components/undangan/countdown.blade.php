{{-- @props: data undangan dikirim dari tema yang manggil komponen ini --}}
@props(['undangan'])

{{-- countdown ke hari-H, cuma tampil kalau tanggal akad diisi --}}
@if ($undangan->akad_tanggal)
    @php
        // gabungkan tanggal akad + jam mulai jadi 1 waktu target, contoh: 2026-11-21T09:00:00
        // tanpa zona waktu, jadi dibaca JavaScript sebagai jam lokal perangkat tamu
        $targetCountdown = \Carbon\Carbon::parse($undangan->akad_tanggal)->format('Y-m-d')
            . 'T'
            . ($undangan->akad_waktu_mulai
                ? \Carbon\Carbon::parse($undangan->akad_waktu_mulai)->format('H:i:s')
                : '00:00:00');
    @endphp

    <div class="mt-8"
        x-data="{
            target: new Date('{{ $targetCountdown }}').getTime(), // waktu target dalam milidetik
            hari: 0, jam: 0, menit: 0, detik: 0, // angka yang ditampilkan
            selesai: false, // true kalau hari-H sudah lewat
            hitung() {
                const selisih = this.target - Date.now(); // sisa waktu dalam milidetik
                if (isNaN(selisih)) return; // tanggal tidak valid, biarkan angka 0
                if (selisih <= 0) { this.selesai = true; return; } // sudah lewat
                this.hari = Math.floor(selisih / 86400000); // 1 hari = 86.400.000 ms
                this.jam = Math.floor((selisih % 86400000) / 3600000); // sisa dibagi 1 jam
                this.menit = Math.floor((selisih % 3600000) / 60000); // sisa dibagi 1 menit
                this.detik = Math.floor((selisih % 60000) / 1000); // sisa dibagi 1 detik
            }
        }"
        x-init="hitung(); setInterval(() => hitung(), 1000)">

        {{-- tampil kalau acara belum lewat: 4 kotak versi kecil --}}
        <div x-show="!selesai" class="grid grid-cols-4 gap-1.5 max-w-[16rem] mx-auto">
            <div class="px-1 py-1.5 rounded-md bg-white/10">
                <p class="text-lg font-semibold leading-tight" x-text="hari"></p>
                <p class="text-[10px] text-gray-400">Hari</p>
            </div>
            <div class="px-1 py-1.5 rounded-md bg-white/10">
                {{-- padStart bikin angka selalu 2 digit, contoh 5 jadi 05 --}}
                <p class="text-lg font-semibold leading-tight" x-text="String(jam).padStart(2, '0')"></p>
                <p class="text-[10px] text-gray-400">Jam</p>
            </div>
            <div class="px-1 py-1.5 rounded-md bg-white/10">
                <p class="text-lg font-semibold leading-tight" x-text="String(menit).padStart(2, '0')"></p>
                <p class="text-[10px] text-gray-400">Menit</p>
            </div>
            <div class="px-1 py-1.5 rounded-md bg-white/10">
                <p class="text-lg font-semibold leading-tight" x-text="String(detik).padStart(2, '0')"></p>
                <p class="text-[10px] text-gray-400">Detik</p>
            </div>
        </div>

        {{-- tampil kalau hari-H sudah lewat --}}
        <p x-show="selesai" class="text-sm text-gray-300">Acara telah berlangsung</p>
    </div>
@endif