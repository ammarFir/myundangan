<?php

namespace App\Livewire;
use App\Models\Tamu;
use Livewire\Component;

class RsvpForm extends Component
{

    //menerima id dari luar 
    public $undanganId;

    //field form yg diisi tamu
    public $nama = '';
    public $kehadiran = '';
    public $jumlah_orang = '';

    //boleean sebagai status beerhasil dikirimmm atau belum
    public $sudahKirim = false;

    //mount = fungsi yg dijalankan component saat pertama kali dibuat/dimuat
    public function mount ($undanganId) {
        $this->undanganId = $undanganId;

    }

    //aturan  validasi
        protected function rules () {
            return [
                'nama' => 'required|string|max:255',
                'kehadiran' => 'required|in:hadir,tidak_hadir,ragu_ragu',
                'jumlah_orang' => 'nullable|integer|min:1|max:20',
            ];
        }

        //dipanggil saat tombol kirim di click
        public function kirim() {
            $this->validate();//jalankan validasi , berhenti kalau gagal

            //simpan data RSVP ke tabel tamu
            Tamu::create([
                'undangan_id' => $this->undanganId,
                'nama' => $this->nama,
                'kehadiran' => $this->kehadiran,
                'jumlah_orang' => $this->jumlah_orang ?: null, //kalau kosong , simpan null bukan String kosong
            ]);
            
            $this->reset(['nama', 'kehadiran', 'jumlah_orang']);//reset fungsi bawaan , mengosongkan isi field

            $this->sudahKirim = true;

        }

    public function render()
    {
        return view('livewire.rsvp-form');
    }
}
