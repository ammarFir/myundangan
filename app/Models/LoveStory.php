<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoveStory extends Model
{
    use HasFactory;
    //memakai fitur factory di dalam class ini


    //kolom yg boleh diisi lewat mass asignment
    protected $fillable = [
        'undangan_id',
        'judul',
        'waktu',
        'cerita',
        'foto',
        'urutan',
        ];

        //relasi 1 momendimiliki oleh 1 undangan
        public function undangan(){
            return $this->belongsTo(Undangan::class);
        }
}
