<?php

namespace App\Http\Controllers;

use App\Models\Undangan; // import model Undangan
use Illuminate\Http\Request;

class UndanganPublicController extends Controller
{
    // dipanggil ketika tamu membuka link undangan, $slug diambil otomatis dari URL
    public function show($slug)
    {
        // cari undangan berdasarkan kolom slug, kalau gak ketemu otomatis munculkan halaman 404
        $undangan = Undangan::where('slug', $slug)->firstOrFail();

        //ambil daftar tema dari config/temas.php
        $daftarTema = config('temas.daftar');

        //pakai tem apilihan undangan ,kalau kosong atau tidak ada di daftar , jatuh ke tema defualt
        $kunciTema = array_key_exists($undangan->tema, $daftarTema)
        ? $undangan->tema
        : config('temas.default');

        // ambil nama view mlik tema itu , contoh 'undangan.show'
        $namaView = $daftarTema[$kunciTema]['view'];


        // kirim data undangan ke view, nanti dipakai buat nampilin nama, tanggal, dll
        return view($namaView,  [
            'undangan' => $undangan,
        ]);
    }
}