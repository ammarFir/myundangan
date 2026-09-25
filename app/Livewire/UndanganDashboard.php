<?php

namespace App\Livewire;


use App\Models\UndanganFoto;
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

    // data acara Akad Nikah
    public $akad_tanggal = '';
    public $akad_waktu_mulai = '';
    public $akad_waktu_selesai = '';
    public $akad_lokasi = '';
    public $akad_alamat = '';
    public $akad_link_maps = '';
    
    
    // data acara Resepsif
    public $resepsi_tanggal = '';
    public $resepsi_waktu_mulai = '';
    public $resepsi_waktu_selesai = '';
    public $resepsi_lokasi = '';
    public $resepsi_alamat = '';
    public $resepsi_link_maps = '';

    //tambahan property baru
    public $fotos_baru = [];
    public $existing_fotos = [];
    public $showRsvpFor = null;  // menyimpan ID undangan yang lagi dibuka daftar RSVP-nya, null kalau gak ada yang dibuka

    //aturan validasi tiap field , dipanggil pas simpan data
    protected function rules() {
        return [
            'nama_pria' => 'required|string|max:255',
            'nama_wanita' => 'required|string|max:255',
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
            'akad_tanggal' => 'nullable|date',
            'akad_waktu_mulai' => 'nullable',
            'akad_waktu_selesai' => 'nullable',
            'akad_lokasi' => 'nullable|string|max:255',
            'akad_alamat' => 'nullable|string',
            'akad_link_maps' => 'nullable|url',
            'resepsi_tanggal' => 'nullable|date',
            'resepsi_waktu_mulai' => 'nullable',
            'resepsi_waktu_selesai' => 'nullable',
            'resepsi_lokasi' => 'nullable|string|max:255',
            'resepsi_alamat' => 'nullable|string',
            'resepsi_link_maps' => 'nullable|url',
            //pnambahan validasii
            'fotos_baru.*' => 'nullable|image|max:2048',
        ];
    }

    //dipanggil saat tiap kali komponen dimuat
    public function render()
    {
        //ambil by user id , jadi yg tampil c uma undangan milik dia sendiri
        $undangans = Undangan::where('user_id', auth()->id())
        ->with('tamus')
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
        $this->akad_tanggal = $undangan->akad_tanggal;
        $this->akad_waktu_mulai = $undangan->akad_waktu_mulai ? substr($undangan->akad_waktu_mulai, 0, 5) : '';
        $this->akad_waktu_selesai = $undangan->akad_waktu_selesai ? substr($undangan->akad_waktu_selesai, 0, 5) : '';
        $this->akad_lokasi = $undangan->akad_lokasi;
        $this->akad_alamat = $undangan->akad_alamat;
        $this->akad_link_maps = $undangan->akad_link_maps;

        $this->resepsi_tanggal = $undangan->resepsi_tanggal;
        $this->resepsi_waktu_mulai = $undangan->resepsi_waktu_mulai ? substr($undangan->resepsi_waktu_mulai, 0, 5) : '';
        $this->resepsi_waktu_selesai = $undangan->resepsi_waktu_selesai ? substr($undangan->resepsi_waktu_selesai, 0, 5) : '';
        $this->resepsi_lokasi = $undangan->resepsi_lokasi;
        $this->resepsi_alamat = $undangan->resepsi_alamat;
        $this->resepsi_link_maps = $undangan->resepsi_link_maps;

        //ambil semua foto galeri milik undangan , urutkan by id
        $this->existing_fotos = $undangan->fotos()->orderBy('urutan')->get();
        $this->fotos_baru = [];

        $this->mode= 'form';

    }

    public function save() {
        //menjalankan validasi diatas berhenti ketika gagal
        $this->validate();

        //data yg akan disimpan
        $data = [
            'nama_pria' => $this->nama_pria,
            'nama_wanita' => $this->nama_wanita,
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
            'akad_tanggal' => $this->akad_tanggal,
            'akad_waktu_mulai' => $this->akad_waktu_mulai,
            'akad_waktu_selesai' => $this->akad_waktu_selesai,
            'akad_lokasi' => $this->akad_lokasi,
            'akad_alamat' => $this->akad_alamat,
            'akad_link_maps' => $this->akad_link_maps,
            'resepsi_tanggal' => $this->resepsi_tanggal,
            'resepsi_waktu_mulai' => $this->resepsi_waktu_mulai,
            'resepsi_waktu_selesai' => $this->resepsi_waktu_selesai,
            'resepsi_lokasi' => $this->resepsi_lokasi,
            'resepsi_alamat' => $this->resepsi_alamat,
            'resepsi_link_maps' => $this->resepsi_link_maps,
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
            $undangan = Undangan::create($data);
            Undangan::create($data);
        }

        //simpan tiap goto baru yg diupload ke tabel undangan foto

        foreach ( $this->fotos_baru as $foto) {
            UndanganFoto::create([
                'undangan_id' => $undangan->id,
                'path' => $foto->store('galeri', 'public'),
                'urutan' => 0,
            ]);
        }

        $this->resetForm();
        $this->mode = 'list';

    }


    public function cancel () {
        $this->resetForm();
        $this->mode = 'list';
    }

    public function toggleRsvp($id) {
        $this->showRsvpFor = $this->showRsvpFor === $id ? null : $id;
    }

    public function deleteFoto($fotoId)  {
        UndanganFoto::where('id', $fotoId)->delete();
      
        
        //refresh daftar exisitiong foto
        $this->existing_fotos = UndanganFoto::where('undangan_id', $this->editingId)
        ->orderBy('urutan')
        ->get();
    }


    public function delete($id) {
        //cari lalu hapus 
        Undangan::where('user_id', auth()->id())->findOrFail($id)->delete();
    }


    private function resetForm(){
        $this->editingId = null;
        $this->nama_pria = '';
        $this->nama_wanita = '';
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
        $this->akad_tanggal = '';
        $this->akad_waktu_mulai = '';
        $this->akad_waktu_selesai = '';
        $this->akad_lokasi = '';
        $this->akad_alamat = '';
        $this->akad_link_maps = '';
        $this->resepsi_tanggal = '';
        $this->resepsi_waktu_mulai = '';
        $this->resepsi_waktu_selesai = '';
        $this->resepsi_lokasi = '';
        $this->resepsi_alamat = '';
        $this->resepsi_link_maps = '';
    }
}