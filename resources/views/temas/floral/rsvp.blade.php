{{-- TAMPILAN RSVP TEMA FLORAL.
     File ini dirender oleh RsvpForm.php, jadi harus memakai nama-nama berikut agar form berfungsi:
       input   : wire:model="nama", wire:model="kehadiran" (nilai: hadir / tidak_hadir / ragu_ragu), wire:model="jumlah_orang"
       form    : wire:submit.prevent="kirim"
       variabel: $sudahKirim (true setelah berhasil kirim), error validasi lewat @error('nama'), @error('kehadiran'), @error('jumlah_orang')
     Livewire mewajibkan SATU elemen pembungkus di paling luar.
     Background dan gelombang pemisah section dipasang oleh tema (show.blade.php), bukan di sini. --}}
<div class="max-w-xl px-6 pt-32 pb-24 mx-auto text-center">

    <h2 class="text-6xl floral-script" style="color: #4f6b4a;">RSVP</h2>
    <p class="mt-3 text-lg mb-14 floral-serif" style="color: #7a6258;">
        Mohon konfirmasi kehadiran Anda agar kami dapat mempersiapkan segalanya.
    </p>

    {{-- kartu putih dengan bunga menempel di tengah atas, sama gayanya dengan kartu Acara --}}
    <div class="relative px-8 pt-14 pb-10 text-left bg-white rounded-[2rem]"
        style="border: 1px solid #c9a24a; box-shadow: 0 10px 30px rgba(176, 86, 111, 0.15);">

        <svg class="absolute w-20 h-20 -translate-x-1/2 -top-10 left-1/2"><use href="#floral-bunga" width="100%" height="100%" /></svg>

        {{-- setelah berhasil kirim: tampilkan ucapan terima kasih, form disembunyikan --}}
        @if ($sudahKirim)
            <div class="py-6 text-center">
                <p class="text-5xl floral-script" style="color: #b0566f;">Terima kasih</p>
                <p class="mt-3 text-lg floral-serif" style="color: #7a6258;">Konfirmasi kehadiranmu sudah kami terima.</p>
            </div>
        @else
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
                    <label class="block mb-2 text-base floral-serif" style="color: #4f6b4a; font-weight: 600;">Konfirmasi Kehadiran</label>
                    {{-- tiga pilihan berbentuk pil. Radio asli disembunyikan (sr-only), pil menyala lewat peer-checked --}}
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['hadir' => 'Hadir', 'tidak_hadir' => 'Tidak Hadir', 'ragu_ragu' => 'Ragu-ragu'] as $nilai => $label)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="kehadiran" value="{{ $nilai }}" class="sr-only peer">
                                <span class="block px-2 py-3 text-base text-center transition border rounded-full floral-serif border-[#f3c1cc] text-[#7a6258] peer-checked:bg-[#b0566f] peer-checked:text-white peer-checked:border-[#b0566f]">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('kehadiran')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 text-base floral-serif" style="color: #4f6b4a; font-weight: 600;">Jumlah Orang (opsional)</label>
                    <input type="number" wire:model="jumlah_orang" min="1" max="20"
                        class="w-full px-5 py-3 text-lg rounded-full floral-serif border border-[#f3c1cc] bg-[#fffaf8] focus:outline-none focus:border-[#b0566f] focus:ring-2 focus:ring-[#b0566f]/30">
                    @error('jumlah_orang')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full px-8 py-3 text-lg transition rounded-full floral-serif hover:opacity-80"
                    style="background: #b0566f; color: #fff; letter-spacing: 0.1em;">
                    Kirim Konfirmasi
                </button>
            </form>
        @endif
    </div>
</div>