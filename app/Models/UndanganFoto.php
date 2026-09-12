<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UndanganFoto extends Model
{
    use HasFactory;


    //kolomm yg boleh diisi 
   protected $fillable = [
    'undangan_id',
    'path',
    'urutan',
   ];

   //1 foto dimiliki 1 undangan
   public function undangan() {
    return $this->belongsTo(Undangan::class);
   }
}
