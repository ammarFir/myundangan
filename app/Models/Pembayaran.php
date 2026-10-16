<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;


    //kolom yg boleh diiisilewat mass asignment
    protected $fillable = [
        'undangan_id',
        'bukti',
        'nominal',
        'status',
        'catatan',
    ];



    public function undangan() {
        return $this->belongsTo(Undangan::class);
    }
    
}
