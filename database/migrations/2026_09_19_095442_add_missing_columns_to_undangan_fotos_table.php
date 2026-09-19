<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('undangan_fotos', function (Blueprint $table) {
            $table->foreignId('undangan_id') // kolom relasi ke tabel undangans
                ->after('id') // ditaro setelah kolom id
                ->constrained() // otomatis nyari tabel "undangans"
                ->cascadeOnDelete(); // kalau undangannya dihapus, foto ikut kehapus

            $table->string('path'); // lokasi/path file foto

            $table->unsignedInteger('urutan')->default(0); // urutan tampil foto
        });
    }

    public function down(): void
    {
        Schema::table('undangan_fotos', function (Blueprint $table) {
            $table->dropForeign(['undangan_id']); // hapus foreign key dulu sebelum hapus kolomnya
            $table->dropColumn(['undangan_id', 'path', 'urutan']);
        });
        //testing
    }
};
