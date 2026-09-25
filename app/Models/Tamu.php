<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Tamu extends Model
{
    use HasFactory;
    //memungkinkan mode memakai fitur factory

    //kolom yg boleh diisi lewat mass-assignment
    //fillable menentukan data mana yg boleh diisi
    protected $fillable = [
        'undangan_id',
        'nama',
        'kehadiran',
        'jumlah_orang',
        'pesan',
    ];

    public function undangan() {
        return $this->belongsTo(Undangan::class);
    }

  
}
