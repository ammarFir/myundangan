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

        // kirim data undangan ke view, nanti dipakai buat nampilin nama, tanggal, dll
        return view('undangan.show', [
            'undangan' => $undangan,
        ]);
    }
}