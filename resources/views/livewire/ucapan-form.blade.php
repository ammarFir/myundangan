<div class="max-w-md px-4 py-16 mx-auto text-center border-t border-gray-100">

    <h2 class="mb-6 font-serif text-2xl font-bold text-gray-900">Ucapan & Doa</h2>

    {{-- FORM KIRIM UCAPAN --}}
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
            <label class="block mb-1 text-sm font-medium text-gray-700">Ucapan & Doa</label>
            <textarea wire:model="pesan" rows="3"
                class="w-full border-gray-300 rounded-lg focus:border-gray-900 focus:ring-gray-900"></textarea>
            @error('pesan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-700">
            Kirim Ucapan
        </button>

    </form>

    {{-- LIST UCAPAN YANG SUDAH MASUK --}}
    <div class="mt-10 space-y-4 text-left">
        {{-- $this->ucapans otomatis manggil method getUcapansProperty() di file .php --}}
        @forelse ($this->ucapans as $ucapan)
            <div class="p-4 border border-gray-100 rounded-xl">
                <div class="flex items-center justify-between">
                    <p class="font-semibold text-gray-900">{{ $ucapan->nama }}</p>
                    {{-- diffForHumans bikin waktu jadi "2 jam yang lalu", lebih enak dibaca --}}
                    <p class="text-xs text-gray-400">{{ $ucapan->created_at->diffForHumans() }}</p>
                </div>
                <p class="mt-1 text-sm text-gray-600">{{ $ucapan->pesan }}</p>
            </div>
        @empty
            {{-- tampil kalau belum ada ucapan sama sekali --}}
            <p class="text-sm text-center text-gray-400">Belum ada ucapan. Jadilah yang pertama!</p>
        @endforelse
    </div>

</div>