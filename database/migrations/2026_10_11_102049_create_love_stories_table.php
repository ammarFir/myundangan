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
        Schema::create('love_stories', function (Blueprint $table) {
            $table->id(); //primary key

            $table->foreignId('undangan_id')
            ->constrained()
            ->cascadeOnDelete(); //kalau undangan dihapus.  momennya ikut ke hapus

            $table->string('judul');
            $table->string('waktu')->nullable();
            $table->text('cerita')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedInteger('urutan')->default(0); //urutan tampildi timeline
            
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('love_stories');
    }
};
