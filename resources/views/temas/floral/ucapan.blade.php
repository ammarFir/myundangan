{{-- TAMPILAN UCAPAN TEMA FLORAL.
     File ini dirender oleh UcapanForm.php, jadi harus memakai nama-nama berikut agar berfungsi:
       input   : wire:model="nama", wire:model="pesan"
       form    : wire:submit.prevent="kirim"
       daftar  : $this->ucapans (koleksi Tamu; tiap item punya ->nama, ->pesan, ->created_at)
       error   : @error('nama'), @error('pesan')
     Livewire mewajibkan SATU elemen pembungkus di paling luar.
     Background dan gelombang pemisah section dipasang oleh tema (show.blade.php), bukan di sini. --}}
<div class="max-w-xl px-6 pt-32 pb-24 mx-auto text-center">

    <h2 class="text-6xl floral-script" style="color: #fbf3ee;">Ucapan & Doa</h2>
    <p class="mt-3 text-lg mb-14 floral-serif" style="color: #c9a24a; letter-spacing: 0.15em;">Kirimkan doa dan restu untuk kami</p>

    {{-- kartu form: putih dengan bunga di tengah atas, gaya sama dengan kartu RSVP dan Acara --}}
    <div class="relative px-8 pt-14 pb-10 text-left bg-white rounded-[2rem]"
        style="border: 1px solid #c9a24a; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);">

        <svg class="absolute w-20 h-20 -translate-x-1/2 -top-10 left-1/2"><use href="#floral-bunga" width="100%" height="100%" /></svg>

        <form wire:submit.prevent="kirim" class="space-y-6">

            <div>
                <label class="block mb-2 text-base floral-serif" style="color: #4f6b4a; font-weight: 600;">Nama</label>
                <input type="text" wire:model="nama"
                    class="w-full px-5 py-3 text-lg rounded-full floral-serif border border-[#f3c1cc] bg-[#fffaf8] focus:outline-none focus:border-[#b0566f] focus:ring-2 focus:ring-[#b0566f]/30">
                @error('nama')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-base floral-serif" style="color: #4f6b4a; font-weight: 600;">Ucapan & Doa</label>
                <textarea wire:model="pesan" rows="4"
                    class="w-full px-5 py-3 text-lg rounded-3xl floral-serif border border-[#f3c1cc] bg-[#fffaf8] focus:outline-none focus:border-[#b0566f] focus:ring-2 focus:ring-[#b0566f]/30"></textarea>
                @error('pesan')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full px-8 py-3 text-lg transition rounded-full floral-serif hover:opacity-80"
                style="background: #b0566f; color: #fff; letter-spacing: 0.1em;">
                Kirim Ucapan
            </button>
        </form>
    </div>

    {{-- daftar ucapan yang sudah masuk. max-h + overflow-y-auto: daftar bisa digulir sendiri supaya halaman tidak kepanjangan --}}
    <div class="mt-14 space-y-4 text-left overflow-y-auto max-h-[28rem] pr-2">
        {{-- $this->ucapans memanggil getUcapansProperty() di UcapanForm.php --}}
        @forelse ($this->ucapans as $ucapan)
            {{-- kartu ucapan: transparan putih tipis di atas hijau, beda dari kartu form yang putih penuh --}}
            <div class="p-5 rounded-3xl" style="background: rgba(251, 243, 238, 0.1); border: 1px solid rgba(201, 162, 74, 0.5);">
                <div class="flex items-baseline justify-between gap-3">
                    <p class="text-3xl floral-script" style="color: #fbf3ee;">{{ $ucapan->nama }}</p>
                    {{-- diffForHumans: waktu jadi "2 jam yang lalu" --}}
                    <p class="text-sm floral-serif shrink-0" style="color: #c9a24a;">{{ $ucapan->created_at->diffForHumans() }}</p>
                </div>
                <p class="mt-2 text-lg leading-relaxed floral-serif" style="color: #e6ecdf;">{{ $ucapan->pesan }}</p>
            </div>
        @empty
            {{-- tampil kalau belum ada ucapan sama sekali --}}
            <p class="text-lg text-center floral-serif" style="color: #c9a24a;">Belum ada ucapan. Jadilah yang pertama!</p>
        @endforelse
    </div>
</div>