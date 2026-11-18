<?php
namespace App\Http\Controllers;

use App\Models\Undangan;

class DemoTemaController extends Controller
{
    public function show($tema) {
        //ambil semua tema dari config/temas.php
        $daftarTema = config('temas.daftar');

        //kalau kunci tidak ad di daftar , tampilkan 404
        abort_unless(array_key_exists($tema, $daftarTema), 404);

        //ambil slug undangan contoh milik tema ini
        $slugDemo = $daftarTema[$tema]['demo'] ?? null;

        //kalau tema beluwem punya undangan demo maka 404
        abort_unless($slugDemo && Undangan::where('slug', $slugDemo)->exists(), 404);

        //kirim ke halaman demo: data tema terpilih dll
        return view('demo.show', [
            'kunciTema' => $tema,
            'tema' => $daftarTema[$tema],
            'alamatDemo' => route('undangan.show', $slugDemo),
            'daftarTema' => $daftarTema,
        ]);

    }
}