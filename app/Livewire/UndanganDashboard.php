<?php

namespace App\Livewire;

use App\Models\Undangan;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads; // trait wajib biar Livewire bisa handle upload file

class UndanganDashboard extends Component
{
    use WithFileUploads; // aktifkan fitur upload file di komponen ini

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
    public $ayat_teks = '';
    public $ayat_arti = '';
    public $ayat_sumber = '';

    // data mempelai pria
    public $nama_lengkap_pria = '';
    public $anak_ke_pria = '';
    public $orang_tua_pria = '';
    public $instagram_pria = '';
    public $foto_pria = null; // null karena ini nampung file upload, bukan teks
    public $foto_pria_lama = null; // simpan path foto lama, biar tau kalau user gak upload ulang

    // data mempelai wanita
    public $nama_lengkap_wanita = '';
    public $anak_ke_wanita = '';
    public $orang_tua_wanita = '';
    public $instagram_wanita = '';
    public $foto_wanita = null;
    public $foto_wanita_lama = null;

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
            'ayat_teks' => 'nullable|string',
            'ayat_arti' => 'nullable|string',
            'ayat_sumber' => 'nullable|string|max:255',
            'nama_lengkap_pria' => 'nullable|string|max:255',
            'anak_ke_pria' => 'nullable|string|max:255',
            'orang_tua_pria' => 'nullable|string|max:255',
            'instagram_pria' => 'nullable|string|max:255',
            'foto_pria' => 'nullable|image|max:2048', // maksimal 2MB, harus gambar
            'nama_lengkap_wanita' => 'nullable|string|max:255',
            'anak_ke_wanita' => 'nullable|string|max:255',
            'orang_tua_wanita' => 'nullable|string|max:255',
            'instagram_wanita' => 'nullable|string|max:255',
            'foto_wanita' => 'nullable|image|max:2048',
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
        $this->ayat_teks = $undangan->ayat_teks;
        $this->ayat_arti = $undangan->ayat_arti;
        $this->ayat_sumber = $undangan->ayat_sumber;

        $this->nama_lengkap_pria = $undangan->nama_lengkap_pria;
        $this->anak_ke_pria = $undangan->anak_ke_pria;
        $this->orang_tua_pria = $undangan->orang_tua_pria;
        $this->instagram_pria = $undangan->instagram_pria;
        $this->foto_pria_lama = $undangan->foto_pria; // simpan path lama buat ditampilin sbg preview
        $this->foto_pria = null; // kosongkan slot upload baru

        $this->nama_lengkap_wanita = $undangan->nama_lengkap_wanita;
        $this->anak_ke_wanita = $undangan->anak_ke_wanita;
        $this->orang_tua_wanita = $undangan->orang_tua_wanita;
        $this->instagram_wanita = $undangan->instagram_wanita;
        $this->foto_wanita_lama = $undangan->foto_wanita;
        $this->foto_wanita = null;

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
            'ayat_teks' => $this->ayat_teks,
            'ayat_arti' => $this->ayat_arti,
            'ayat_sumber' => $this->ayat_sumber,
            'nama_lengkap_pria' => $this->nama_lengkap_pria,
            'anak_ke_pria' => $this->anak_ke_pria,
            'orang_tua_pria' => $this->orang_tua_pria,
            'instagram_pria' => $this->instagram_pria,
            'nama_lengkap_wanita' => $this->nama_lengkap_wanita,
            'anak_ke_wanita' => $this->anak_ke_wanita,
            'orang_tua_wanita' => $this->orang_tua_wanita,
            'instagram_wanita' => $this->instagram_wanita,
        ];

        // kalau user upload foto baru, simpan file-nya dan catat path-nya
        // kalau tidak upload foto baru, biarkan kosong (nanti dipertahankan pakai foto lama)
        if ($this->foto_pria) {
            $data['foto_pria'] = $this->foto_pria->store('mempelai', 'public'); // simpan ke storage/app/public/mempelai
        }
        if ($this->foto_wanita) {
            $data['foto_wanita'] = $this->foto_wanita->store('mempelai', 'public');
        }

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
        $this->ayat_teks = '';
        $this->ayat_arti = '';
        $this->ayat_sumber = '';
        $this->nama_lengkap_pria = '';
        $this->anak_ke_pria = '';
        $this->orang_tua_pria = '';
        $this->instagram_pria = '';
        $this->foto_pria = null;
        $this->foto_pria_lama = null;
        $this->nama_lengkap_wanita = '';
        $this->anak_ke_wanita = '';
        $this->orang_tua_wanita = '';
        $this->instagram_wanita = '';
        $this->foto_wanita = null;
        $this->foto_wanita_lama = null;
    }
}