<div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">

    <h2 class="mb-6 font-serif text-2xl font-bold text-gray-900">RSVP</h2>

    {{-- kalau sudah kirim, tampilkan pesan terima kasih, form disembunyikan --}}
    @if ($sudahKirim)
        <div class="p-6 bg-gray-50 rounded-xl">
            <p class="font-semibold text-gray-900">Terima kasih!</p>
            <p class="mt-1 text-sm text-gray-600">Konfirmasi kehadiranmu sudah kami terima.</p>
        </div>
    @else
        <form wire:submit.prevent="kirim" class="space-y-4 text-left">

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Nama</label>
                <input type="text" wire:model="nama"
                    class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Konfirmasi Kehadiran</label>
                {{-- select dropdown buat pilih salah satu status --}}
                <select wire:model="kehadiran"
                    class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    <option value="">-- Pilih --</option>
                    <option value="hadir">Hadir</option>
                    <option value="tidak_hadir">Tidak Hadir</option>
                    <option value="ragu_ragu">Ragu-ragu</option>
                </select>
                @error('kehadiran')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Jumlah Orang (opsional)</label>
                <input type="number" wire:model="jumlah_orang" min="1" max="20"
                    class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                @error('jumlah_orang')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                Kirim Konfirmasi
            </button>

        </form>
    @endif

</div>