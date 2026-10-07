<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//file yg mewakili data undangan di database
//db 
// |
// table undangans
// |
// Model undangan.php
// |
// dipakai controller / livewire / kode laravel lainnya
class Undangan extends Model
{
    use HasFactory;
    //fillable artinya kolom2 yg boleh diisi dengan menggunakan mass assignment
     protected $fillable = [
        'user_id',
        'slug',
        'tema',
        'nama_pria',
        'nama_wanita',
        'link_streaming',
        'musik',
        'status',
        'ayat_teks',
        'ayat_arti',
        'ayat_sumber',
        'nama_lengkap_pria',
        'anak_ke_pria',
        'orang_tua_pria',
        'instagram_pria',
        'foto_pria',
        'nama_lengkap_wanita',
        'anak_ke_wanita',
        'orang_tua_wanita',
        'instagram_wanita',
        'foto_wanita',
        'akad_tanggal',
        'akad_waktu_mulai',
        'akad_waktu_selesai',
        'akad_lokasi',
        'akad_alamat',
        'akad_link_maps',
        'resepsi_tanggal',
        'resepsi_waktu_mulai',
        'resepsi_waktu_selesai',
        'resepsi_lokasi',
        'resepsi_alamat',
        'resepsi_link_maps',
        'rekening_bank',
        'rekening_nomor',
        'rekening_nama',
        'qris_gambar',
    ];

    public function user(){
        //1 undangan hanya dimiliki 1 user : belongsTo punya induk 
        return $this->belongsTo(User::class);
    }


    public function fotos() {
        //1 undangan bisa punya banyak foto
        return $this->hasMany(UndanganFoto::class);
    }

      //relasi : 1 undangan bisa punya banyak tamu 
    public function tamus () {
        return $this->hasMany(Tamu::class);
        //$this mengacu ke undangan 
        // object this (undangan ) punya banyak tamu
    }

}
