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
                    <div class="border border-gray-200 rounded-xl">
                        <div class="flex items-center justify-between p-4">
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
                                {{-- tombol baru, menampilkan jumlah tamu yang udah RSVP --}}
                                                                {{-- tombol Aktifkan, cuma muncul kalau undangan masih draft (belum bayar atau pernah ditolak) --}}
                                @if ($undangan->status === 'draft')
                                    <button wire:click="toggleBayar({{ $undangan->id }})"
                                        class="text-sm font-semibold text-green-700 hover:text-green-900">
                                        Aktifkan
                                    </button>
                                @endif
                                
                                <button wire:click="toggleRsvp({{ $undangan->id }})"
                                    class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                    Lihat RSVP ({{ $undangan->tamus->count() }})
                                </button>
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

                        {{-- daftar RSVP, cuma tampil kalau undangan ini yang lagi dibuka --}}
                        @if ($showRsvpFor === $undangan->id)
                            <div class="p-4 border-t border-gray-100 bg-gray-50">
                                @if ($undangan->tamus->isEmpty())
                                    <p class="text-sm text-gray-500">Belum ada yang mengisi RSVP.</p>
                                @else
                                    <div class="space-y-2">
                                        @foreach ($undangan->tamus as $tamu)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-900">{{ $tamu->nama }}</span>
                                                <span class="text-gray-500">
                                                    {{-- str_replace bikin "tidak_hadir" jadi "tidak hadir" (lebih enak dibaca) --}}
                                                    {{ ucfirst(str_replace('_', ' ', $tamu->kehadiran)) }}
                                                    @if ($tamu->jumlah_orang)
                                                        ({{ $tamu->jumlah_orang }} orang)
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                                                {{-- ambil pembayaran terbaru undangan ini (relasi pembayarans sudah diurutkan terbaru dulu) --}}
                        @php $bayarTerakhir = $undangan->pembayarans->first(); @endphp

                        {{-- info status pembayaran, selalu tampil kalau relevan (tanpa harus buka panel) --}}
                        @if ($undangan->status === 'pending_payment')
                            <div class="p-4 text-sm text-yellow-800 border-t border-yellow-100 bg-yellow-50">
                                Bukti pembayaran sudah diterima, sedang dicek admin.
                            </div>
                        @elseif ($undangan->status === 'draft' && $bayarTerakhir && $bayarTerakhir->status === 'ditolak')
                            <div class="p-4 text-sm text-red-800 border-t border-red-100 bg-red-50">
                                Pembayaran sebelumnya ditolak.
                                @if ($bayarTerakhir->catatan)
                                    Alasan: {{ $bayarTerakhir->catatan }}.
                                @endif
                                Silakan kirim ulang bukti transfer.
                            </div>
                        @elseif ($undangan->status === 'active')
                            <div class="p-4 text-sm text-green-800 border-t border-green-100 bg-green-50">
                                Undangan aktif dan bisa dibagikan ke tamu.
                            </div>
                        @endif

                        {{-- panel pembayaran, tampil hanya kalau undangan ini yang lagi dibuka via tombol Aktifkan --}}
                        @if ($bayarFor === $undangan->id)
                            <div class="p-4 space-y-4 border-t border-gray-100 bg-gray-50">
                                <p class="text-sm font-medium text-gray-900">Aktifkan Undangan</p>

                                {{-- config('pembayaran.xxx') membaca nilai dari file config/pembayaran.php --}}
                                <div class="p-4 text-sm bg-white border border-gray-200 rounded-lg">
                                    <p class="text-gray-500">Total pembayaran</p>
                                    {{-- number_format bikin 149000 jadi 149.000 --}}
                                    <p class="text-lg font-semibold text-gray-900">
                                        Rp {{ number_format(config('pembayaran.harga'), 0, ',', '.') }}
                                    </p>

                                    <p class="mt-3 text-gray-500">Transfer ke</p>
                                    <p class="font-medium text-gray-900">
                                        {{ config('pembayaran.bank') }} {{ config('pembayaran.nomor_rekening') }}
                                    </p>
                                    <p class="text-gray-600">a.n. {{ config('pembayaran.atas_nama') }}</p>

                                    {{-- QRIS cuma tampil kalau path gambarnya diisi di config --}}
                                    @if (config('pembayaran.qris'))
                                        <img src="{{ asset(config('pembayaran.qris')) }}"
                                            class="object-contain w-40 h-40 mt-3">
                                    @endif
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Nominal yang ditransfer (opsional)</label>
                                    <input type="number" wire:model="nominal_bayar"
                                        class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                                    @error('nominal_bayar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Bukti transfer</label>

                                    {{-- preview bukti yang baru dipilih (belum dikirim) --}}
                                    @if ($bukti_bayar)
                                        <img src="{{ $bukti_bayar->temporaryUrl() }}"
                                            class="object-cover w-24 h-24 mb-2 rounded-lg">
                                    @endif

                                    <input type="file" wire:model="bukti_bayar" accept="image/png,image/jpeg"
                                        class="block text-sm text-gray-600">
                                    <div wire:loading wire:target="bukti_bayar" class="mt-1 text-xs text-gray-400">Mengunggah...</div>
                                    @error('bukti_bayar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="button" wire:click="kirimBukti"
                                    class="px-5 py-2 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                                    Kirim Bukti
                                </button>
                            </div>
                        @endif
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


            {{-- SECTION GALERI FOTO --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Galeri Foto</h3>

                {{-- tampilkan foto-foto yang udah ada, dengan tombol hapus per foto --}}
                @if (count($existing_fotos) > 0)
                    <div class="grid grid-cols-3 gap-3 mb-4 md:grid-cols-4">
                        @foreach ($existing_fotos as $foto)
                            <div class="relative">
                                <img src="{{ Storage::url($foto->path) }}"
                                    class="object-cover w-full h-24 rounded-lg">
                                {{-- wire:click.stop biar klik tombol ini gak ikut trigger event lain --}}
                                <button type="button" wire:click.stop="deleteFoto({{ $foto->id }})"
                                    wire:confirm="Hapus foto ini?"
                                    class="absolute flex items-center justify-center w-6 h-6 text-xs text-white bg-red-600 rounded-full top-1 right-1 hover:bg-red-700">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- input file, "multiple" artinya user bisa pilih beberapa foto sekaligus --}}
                <label class="block mb-1 text-sm font-medium text-gray-700">Tambah Foto Baru</label>
                <input type="file" wire:model="fotos_baru" multiple accept="image/*"
                    class="block text-sm text-gray-600">

                <div wire:loading wire:target="fotos_baru" class="mt-1 text-xs text-gray-400">Mengunggah...</div>

                @error('fotos_baru.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- preview foto baru yang barusan dipilih, belum disimpan ke server --}}
                @if (count($fotos_baru) > 0)
                    <div class="grid grid-cols-3 gap-3 mt-3 md:grid-cols-4">
                        @foreach ($fotos_baru as $foto)
                            <img src="{{ $foto->temporaryUrl() }}" class="object-cover w-full h-24 rounded-lg">
                        @endforeach
                    </div>
                @endif
            </div>

                        {{-- SECTION KADO --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Kado (Rekening & QRIS)</h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nama Bank</label>
                        <input type="text" wire:model="rekening_bank" placeholder="Contoh: BCA"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nomor Rekening</label>
                        <input type="text" wire:model="rekening_nomor"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nama Pemilik Rekening</label>
                        <input type="text" wire:model="rekening_nama"
                            class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Gambar QRIS</label>

                    {{-- preview QRIS lama, tampil kalau ada dan belum pilih file baru --}}
                    @if ($qris_gambar_lama && !$qris_gambar)
                        <img src="{{ Storage::url($qris_gambar_lama) }}" class="object-cover w-24 h-24 mb-2 rounded-lg">
                    @endif

                    {{-- preview file QRIS yang baru dipilih (belum disimpan ke server) --}}
                    @if ($qris_gambar)
                        <img src="{{ $qris_gambar->temporaryUrl() }}" class="object-cover w-24 h-24 mb-2 rounded-lg">
                    @endif

                    <input type="file" wire:model="qris_gambar" accept="image/png,image/jpeg"
                        class="block text-sm text-gray-600">

                    <div wire:loading wire:target="qris_gambar" class="mt-1 text-xs text-gray-400">Mengunggah...</div>

                    @error('qris_gambar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

                        {{-- SECTION LOVE STORY --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="mb-4 font-semibold text-gray-900">Love Story</h3>

                {{-- form mini butuh editingId (undangan harus udah tersimpan dulu), jadi saat bikin baru cuma tampil keterangan --}}
                @if (!$editingId)
                    <p class="text-sm text-gray-500">
                        Simpan undangan ini dulu, setelah itu klik Edit untuk menambahkan momen Love Story.
                    </p>
                @else

                    {{-- daftar momen yang udah tersimpan, tiap momen ada tombol hapus --}}
                    @if (count($existing_stories) > 0)
                        <div class="mb-5 space-y-3">
                            @foreach ($existing_stories as $momen)
                                <div class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                                    {{-- foto momen, cuma tampil kalau ada --}}
                                    @if ($momen->foto)
                                        <img src="{{ Storage::url($momen->foto) }}"
                                            class="object-cover w-16 h-16 rounded-lg shrink-0">
                                    @endif

                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $momen->judul }}</p>
                                        @if ($momen->waktu)
                                            <p class="text-xs text-gray-500">{{ $momen->waktu }}</p>
                                        @endif
                                        @if ($momen->cerita)
                                            <p class="mt-1 text-sm text-gray-600">{{ $momen->cerita }}</p>
                                        @endif
                                    </div>

                                    {{-- type="button" wajib, supaya klik ini gak ikut nge-submit form utama --}}
                                    <button type="button" wire:click="hapusMomen({{ $momen->id }})"
                                        wire:confirm="Hapus momen ini?"
                                        class="text-sm font-medium text-red-600 hover:text-red-800">
                                        Hapus
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- form mini "Tambah Momen", pakai div (bukan form) karena sudah berada di dalam form utama --}}
                    <div class="p-4 space-y-4 border border-gray-200 border-dashed rounded-lg">
                        <p class="text-sm font-medium text-gray-700">Tambah Momen Baru</p>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">Judul</label>
                                {{-- @keydown.enter.prevent: tekan Enter di sini tidak ikut submit form utama --}}
                                <input type="text" wire:model="ls_judul" @keydown.enter.prevent
                                    placeholder="Contoh: Pertemuan Pertama"
                                    class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                                @error('ls_judul')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">Waktu</label>
                                <input type="text" wire:model="ls_waktu" @keydown.enter.prevent
                                    placeholder="Contoh: Agustus 2021"
                                    class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900">
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Cerita</label>
                            <textarea wire:model="ls_cerita" rows="3"
                                class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900"></textarea>
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Foto (opsional)</label>

                            {{-- preview foto yang baru dipilih, belum disimpan ke server --}}
                            @if ($ls_foto)
                                <img src="{{ $ls_foto->temporaryUrl() }}" class="object-cover w-24 h-24 mb-2 rounded-lg">
                            @endif

                            <input type="file" wire:model="ls_foto" accept="image/png,image/jpeg"
                                class="block text-sm text-gray-600">

                            <div wire:loading wire:target="ls_foto" class="mt-1 text-xs text-gray-400">Mengunggah...</div>

                            @error('ls_foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- type="button" + wire:click, supaya cuma manggil tambahMomen(), bukan save() --}}
                        <button type="button" wire:click="tambahMomen"
                            class="px-5 py-2 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
                            Tambah Momen
                        </button>
                    </div>
                @endif
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