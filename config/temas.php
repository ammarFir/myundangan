<?php

//daftar semua tema undangan yang tersedia
//key (misal "klasik") adalah nilai yang disimpan di kolom undangans.tema
//tema baru cukup ditambah satu entri di sini, lalu dropdown dan controller otomatis ikut

return [

    //tema yang dipakai kalau undagan belum punya pilihan tema (kolom tema kosong) atau isinya tidak dikenal
    'default' => 'klasik',

    'daftar' => [

    'klasik' => [
        'klasik' => 'Klasik' ,
        'deskripsi' => 'Tema gelap sederhana dengan cover layar penuh',
        'view' => 'undangan.show',
        'thumbnail' => '',
    ],
],
];