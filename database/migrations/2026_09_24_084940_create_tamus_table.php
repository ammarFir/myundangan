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
        Schema::create('tamus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('undangan_id') // relasi ke undangan tamu
                ->constrained()
                ->cascadeOnDelete(); // kalau undangan dihapus  , data tamu ikut kehapus


            $table->string('nama');//nama tamu yg isi form , waji diisi

            $table->enum('kehadiran', ['hadir', 'tidak_hadir', 'ragu_ragu'])->nullable();// boleh diisi ato tidak
            
            $table->unsignedInteger('jumlah_orang')->nullable();//jumlah org yg kira2 akan hadir

            $table->text('pesan')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
