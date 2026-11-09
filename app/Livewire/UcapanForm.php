<?php

namespace App\Livewire;
//define bahwa class ini berdaa di folder tsb

use App\Models\Tamu;
//import model tamu , make table  yg kayak rsvp
use Livewire\Component;
//ucapan form nanti akan jadi livewire component

use Livewire\Attributes\Locked;


class UcapanForm extends Component
{

    public $undanganId;
    //menerima undangan id dari luar , (dikirim saat component dipanggil dari halaman public)

    //nama view diubah dari kode server tidak dari web
    #[Locked]
    public $tampilan = 'livewire.ucapan-form';

    public $nama = '';
    public $pesan = '';


    //mount dijalankan sekali saat component pertama kali dimuat
    //$tampilan opsional : kalau tidak dkirim , dipakai tampilan default
    public function mount($undanganId , $tampilan = 'livewire.ucapan-form'){
        $this->undanganId = $undanganId;

        $this->tampilan = view()->exists($tampilan) ? $tampilan : 'livewire.ucapan-form';
        //this itu object ucapan form

        //data dari parameter $undanganId disimpan ke property $this
    }

    //aturan validasi
    public function rules () {
        return [
            'nama' => 'required|string|max:225',
            'pesan' => 'required|string|max:1000',
        ];
    }

    public function kirim (){
        //dijalankan ketika user mengirim ucapan 
        // $this : objetc ucapan form ini tolong jalankan validate()
        $this->validate(); //jalankan validasi berdasarkan rules() diatas , kalau berhenti gagal
        //kalau berhasil lanjut ke tamu create
        //simpan data ucapan ke tabel tamus
        //kehadiran & jumlah orang sengaja diisi , karena ini bukan form rsvp
        Tamu::create([
            'undangan_id' => $this->undanganId,
            //ambil id dari property component
            'nama' => $this->nama,
            'pesan' => $this->pesan,
        ]);

        //kosongkan form 
        $this->reset(['nama', 'pesan']);
    }


    public function getUcapansProperty () {
        return Tamu::where('undangan_id', $this->undanganId)
        //mencari data tamu yang undangan_id nya sama dengan ID undangan yg sedang dibuka
        ->whereNotNull('pesan')
        //hanya ambil data yg punya pesan
        //alias yg tidak ada pesan tidak diambil
        ->latest()
        //urutkan data dari data terbaru
        ->get();
        //jalankan query dan ambil hasilnya
    }

    public function render()
    {
        return view($this->tampilan);
    }
}
