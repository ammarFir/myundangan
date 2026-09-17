<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Undangan extends Model
{
    use HasFactory;
     protected $fillable = [
        'user_id',
        'slug',
        'tema',
        'nama_pria',
        'nama_wanita',
        'tanggal_acara',
        'waktu_acara',
        'lokasi',
        'alamat',
        'link_maps',
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
    ];

    public function user(){
        //1 undangan hanya dimiliki 1 user : belongsTo punya induk 
        return $this->belongsTo(User::class);
    }


    public function fotos() {
        //1 undangan bisa punya banyak foto
        return $this->hasMany(UndanganFoto::class);
    }


}
