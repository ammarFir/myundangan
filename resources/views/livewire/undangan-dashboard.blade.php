@php use Illuminate\Support\Facades\Storage; @endphp
<div class="max-w-4xl px-4 py-8 mx-auto">

    {{-- tampil hanya kalau mode = 'list' --}}
    @if ($mode === 'list')

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Undangan Saya</h2>
            <button wire:click="create"
                class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                + Buat Undangan Baru
            </button>
        </div>

        @if ($undangans->isEmpty())
            <div class="py-16 text-center text-gray-500 border border-gray-300 border-dashed rounded-xl">
                Kamu belum punya undangan. Yuk buat yang pertama!
            </div>
        @else
            <div class="space-y-4">
                @foreach ($undangans as $undangan)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $undangan->nama_pria }} & {{ $undangan->nama_wanita }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Status:
                                <span class="font-medium">{{ ucfirst($undangan->status) }}</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button wire:click="edit({{ $undangan->id }})"
                                class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                Edit
                            </button>
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
            {{ $editingId ? 'Edit Undangan' : 'Buat Undangan Baru' }}
        </h2>

        <form wire:submit.prevent="save" class="space-y-5">

            {{-- NAMA PRIA & WANITA --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Nama Pria</label>
                    <input type="text" wire:model="nama_pria"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
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
            </div>

            {{-- SECTION AKAD NIKAH --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Akad Nikah</h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" wire:model="akad_tanggal"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Waktu Mulai</label>
                            <input type="time" wire:model="akad_waktu_mulai"
                                class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Waktu Selesai</label>
                            <input type="time" wire:model="akad_waktu_selesai"
                                class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" wire:model="akad_lokasi"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Link Google Maps</label>
                        <input type="text" wire:model="akad_link_maps"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea wire:model="akad_alamat" rows="3"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900"></textarea>
                </div>
            </div>

            {{-- SECTION RESEPSI --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Resepsi</h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" wire:model="resepsi_tanggal"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Waktu Mulai</label>
                            <input type="time" wire:model="resepsi_waktu_mulai"
                                class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Waktu Selesai</label>
                            <input type="time" wire:model="resepsi_waktu_selesai"
                                class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" wire:model="resepsi_lokasi"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Link Google Maps</label>
                        <input type="text" wire:model="resepsi_link_maps"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea wire:model="resepsi_alamat" rows="3"
                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900"></textarea>
                </div>
            </div>

            {{-- AYAT / KUTIPAN --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Ayat / Kutipan</h3>

                <div class="space-y-5">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Teks Ayat/Kutipan</label>
                        <textarea wire:model="ayat_teks" rows="3"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900"></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Arti/Terjemahan</label>
                        <textarea wire:model="ayat_arti" rows="3"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900"></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Sumber (contoh: Ar-Rum: 21)</label>
                        <input type="text" wire:model="ayat_sumber"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>
                </div>
            </div>

            {{-- LINK STREAMING & MUSIK --}}
            <div class="grid grid-cols-1 gap-5 pt-6 border-t border-gray-200 md:grid-cols-2">
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

            {{-- SECTION MEMPELAI PRIA --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Mempelai Pria</h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" wire:model="nama_lengkap_pria"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Anak ke Berapa</label>
                        <input type="text" wire:model="anak_ke_pria" placeholder="Contoh: Putra pertama"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Dari Pasangan</label>
                        <input type="text" wire:model="orang_tua_pria" placeholder="Contoh: Bapak Slamet & Ibu Sari"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Instagram (opsional)</label>
                        <input type="text" wire:model="instagram_pria"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Foto</label>

                    @if ($foto_pria_lama && !$foto_pria)
                        <img src="{{ Storage::url($foto_pria_lama) }}" class="object-cover w-24 h-24 mb-2 rounded-lg">
                    @endif

                    @if ($foto_pria)
                        <img src="{{ $foto_pria->temporaryUrl() }}" class="object-cover w-24 h-24 mb-2 rounded-lg">
                    @endif

                    <input type="file" wire:model="foto_pria" accept="image/*"
                        class="block text-sm text-gray-600">

                    <div wire:loading wire:target="foto_pria" class="mt-1 text-xs text-gray-400">Mengunggah...</div>

                    @error('foto_pria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- SECTION MEMPELAI WANITA --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Mempelai Wanita</h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" wire:model="nama_lengkap_wanita"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Anak ke Berapa</label>
                        <input type="text" wire:model="anak_ke_wanita" placeholder="Contoh: Putri kedua"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Dari Pasangan</label>
                        <input type="text" wire:model="orang_tua_wanita" placeholder="Contoh: Bapak Ahmad & Ibu Wati"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Instagram (opsional)</label>
                        <input type="text" wire:model="instagram_wanita"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Foto</label>

                    @if ($foto_wanita_lama && !$foto_wanita)
                        <img src="{{ Storage::url($foto_wanita_lama) }}" class="object-cover w-24 h-24 mb-2 rounded-lg">
                    @endif

                    @if ($foto_wanita)
                        <img src="{{ $foto_wanita->temporaryUrl() }}" class="object-cover w-24 h-24 mb-2 rounded-lg">
                    @endif

                    <input type="file" wire:model="foto_wanita" accept="image/*"
                        class="block text-sm text-gray-600">

                    <div wire:loading wire:target="foto_wanita" class="mt-1 text-xs text-gray-400">Mengunggah...</div>

                    @error('foto_wanita')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                    Simpan
                </button>
                <button type="button" wire:click="cancel"
                    class="px-6 py-3 text-sm font-semibold text-gray-700 rounded-full hover:bg-gray-100">
                    Batal
                </button>
            </div>

        </form>

    @endif

</div>