<?php

namespace App\Livewire;
use App\Models\Tamu;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RsvpForm extends Component
{

    //menerima id dari luar 
    public $undanganId;

    //nama view tampilan
    //locked nilainya  cuma bisa idubah dari kode sarvar
    #[Locked]
    public $tampilan = 'livewire.rsvp-form';

    //field form yg diisi tamu
    public $nama = '';
    public $kehadiran = '';
    public $jumlah_orang = '';

    //boleean sebagai status beerhasil dikirimmm atau belum
    public $sudahKirim = false;

    //mount = fungsi yg dijalankan component saat pertama kali dibuat/dimuat
    public function mount ($undanganId, $tampilan = 'livewire.rsvp-form') {
        $this->undanganId = $undanganId;

        $this->tampilan = view()->exists($tampilan) ? $tampilan : 'livewire.rsvp-form';
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
        return view($this->tampilan);
    }
}
