<?php

namespace App\Livewire;

use App\Models\Undangan;
use Illuminate\Support\Str;
use Livewire\Component;

class UndanganDashboard extends Component
{
    //menentukan tampilan yg aktif  list atau form edit
    public $mode = 'list';

    //menyimpan id undangan yg lagi di edit , defaultnya null
    public $editingId = null;

    //deklare field form 
    public $nama_pria = '';
    public $nama_wanita = '';
    public $tanggal_acara = '';
    public $waktu_acara = '';
    public $lokasi = '';
    public $alamat = '';
    public $link_maps = '';
    public $link_streaming = '';
    public $musik = '';

    //aturan validasi tiap field , dipanggil pas simpan data
    protected function rules() {
        return [
            'nama_pria' => 'required|string|max:255',
            'nama_wanita' => 'required|string|max:255',
            'tanggal_acara' => 'nullable|date',
            'waktu_acara' => 'nullable',
            'lokasi'  => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'link_maps' => 'nullable|url',
            'link_streaming' => 'nullable|url',
            'musik' => 'nullable|url',
        ];
    }

//dipanggil saat tiap kali komponen dimuat
    public function render()
    {
        //ambil by user id , jadi yg tampil c uma undangan milik dia sendiri
        $undangans = Undangan::where('user_id', auth()->id())
        ->latest()
        ->get();

        return view('livewire.undangan-dashboard', [
            'undangans' => $undangans,
        ]);
    }

    //dipanggil ketika tombol buat undangan diclick
    public function create(){
        $this->resetForm();
        //kosongkan semua field
        $this->mode = 'form';
        //pindah ke tampilan form
    }

    public function edit($id) {
        //cari undangan by id , biar ga edit punya org lain
        $undangan = Undangan::where('user_id', auth()->id())->findOrFail($id);

        //simpan id yg diedit
        $this->editingId = $undangan->id;

        $this->nama_pria = $undangan->nama_pria;
        $this->nama_wanita = $undangan->nama_wanita;
        $this->tanggal_acara = $undangan->tanggal_acara;
        $this->waktu_acara = $undangan->waktu_acara;
        $this->lokasi = $undangan->lokasi;
        $this->alamat = $undangan->alamat;
        $this->link_maps = $undangan->link_maps;
        $this->link_streaming = $undangan->link_streaming;
        $this->musik = $undangan->musik;

        $this->mode= 'form';

    }

    public function save() {
        //menjalankan validasi diatas berhenti ketika gagal
        $this->validate();

        //data yg akan disimpan
        $data = [
            'nama_pria' => $this->nama_pria,
            'nama_wanita' => $this->nama_wanita,
            'tanggal_acara' => $this->tanggal_acara,
            'waktu_acara' => $this->waktu_acara,
            'lokasi' => $this->lokasi,
            'alamat' => $this->alamat,
            'link_maps' => $this->link_maps,
            'link_streaming' => $this->link_streaming,
            'musik' => $this->musik,
        ];

        if ($this->editingId){
            //kalau sedang mode edit , cari undangan lama lalu update
            $undangan = Undangan::where('user_id', auth()->id())->findOrFail($this->editingId);
            $undangan ->update($data);
        } else {
            $data['user_id'] = auth()->id();
            //ambil id user login

            $data['slug'] = Str::slug($this->nama_pria . '-' . $this->nama_wanita) . '-' . uniqid();
            $data['status'] = 'draft';
            Undangan::create($data);
        }

        $this->resetForm();
        $this->mode = 'list';

    }


    public function cancel () {
        $this->resetForm();
        $this->mode = 'list';
    }

    public function delete($id) {
        //cari lalu hapus 
        Undangan::where('user_id', auth()->id())->findOrFail($id)->delete();
    }


    private function resetForm(){
        $this->editingId = null;
        $this->nama_pria = '';
        $this->nama_wanita = '';
        $this->tanggal_acara = '';
        $this->waktu_acara = '';
        $this->lokasi = '';
        $this->alamat = '';
        $this->link_maps = '';
        $this->link_streaming = '';
        $this->musik = '';
    }
}
