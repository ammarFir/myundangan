<div class="max-w-4xl px-4 py-8 mx-auto">

    {{-- tampil hanya kalau mode = 'list' --}}
    @if ($mode === 'list')

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Undangan Saya</h2>
            {{-- wire:click memanggil method create() di file .php tanpa reload halaman --}}
            <button wire:click="create"
                class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                + Buat Undangan Baru
            </button>
        </div>

        {{-- kalau belum punya undangan sama sekali --}}
        @if ($undangans->isEmpty())
            <div class="py-16 text-center text-gray-500 border border-gray-300 border-dashed rounded-xl">
                Kamu belum punya undangan. Yuk buat yang pertama!
            </div>
        @else
            {{-- looping semua undangan yang dikirim dari render() --}}
            <div class="space-y-4">
                @foreach ($undangans as $undangan)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $undangan->nama_pria }} & {{ $undangan->nama_wanita }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Status:
                                {{-- ucfirst membuat huruf pertama kapital, misal "draft" jadi "Draft" --}}
                                <span class="font-medium">{{ ucfirst($undangan->status) }}</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            {{-- panggil method edit(id), $undangan->id dikirim sebagai parameter --}}
                            <button wire:click="edit({{ $undangan->id }})"
                                class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                Edit
                            </button>
                            {{-- wire:confirm memunculkan dialog konfirmasi sebelum method dijalankan --}}
                            <button wire:click="delete({{ $undangan->id }})"
                                wire:confirm="Yakin mau hapus undangan ini?"
                                class="text-sm font-medium text-red-600 hover:text-red-800">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @endif

    {{-- tampil hanya kalau mode = 'form' --}}
    @if ($mode === 'form')

        <h2 class="mb-6 text-xl font-bold text-gray-900">
            {{-- kalau editingId ada isinya berarti mode edit, kalau kosong berarti bikin baru --}}
            {{ $editingId ? 'Edit Undangan' : 'Buat Undangan Baru' }}
        </h2>

        {{-- wire:submit.prevent mencegah reload halaman, langsung panggil method save() --}}
        <form wire:submit.prevent="save" class="space-y-5">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Nama Pria</label>
                    {{-- wire:model menghubungkan input ini dengan variabel $nama_pria di file .php --}}
                    <input type="text" wire:model="nama_pria"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    {{-- menampilkan pesan error validasi khusus untuk field nama_pria --}}
                    @error('nama_pria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Nama Wanita</label>
                    <input type="text" wire:model="nama_wanita"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    @error('nama_wanita')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal Acara</label>
                    <input type="date" wire:model="tanggal_acara"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    @error('tanggal_acara')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Waktu Acara</label>
                    <input type="time" wire:model="waktu_acara"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" wire:model="lokasi"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Link Google Maps</label>
                    <input type="text" wire:model="link_maps"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    @error('link_maps')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                {{-- textarea juga bisa pakai wire:model --}}
                <textarea wire:model="alamat" rows="3"
                    class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900"></textarea>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Link Live Streaming</label>
                    <input type="text" wire:model="link_streaming"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    @error('link_streaming')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Link Musik</label>
                    <input type="text" wire:model="musik"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    @error('musik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                    Simpan
                </button>
                {{-- type="button" supaya tidak ikut submit form, cuma panggil method cancel() --}}
                <button type="button" wire:click="cancel"
                    class="px-6 py-3 text-sm font-semibold text-gray-700 rounded-full hover:bg-gray-100">
                    Batal
                </button>
            </div>

        </form>

    @endif

</div>