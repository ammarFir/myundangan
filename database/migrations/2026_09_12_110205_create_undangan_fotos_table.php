<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::create('undangan_fotos', function (Blueprint $table) {
            $table->id(); // primary key, ID unik tiap baris foto

            $table->foreignId('undangan_id') // kolom relasi ke tabel undangans
                ->constrained() // otomatis nyari tabel "undangans" berdasarkan nama kolom (undangan_id -> undangans)
                ->cascadeOnDelete(); // kalau undangannya dihapus, semua fotonya ikut kehapus otomatis

            $table->string('path'); // lokasi/path file foto yang disimpan di storage

            $table->unsignedInteger('urutan')->default(0); // urutan tampil foto (0, 1, 2, dst), default 0 kalau belum diatur

            $table->timestamps(); // created_at & updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('undangan_fotos');
    }
};
