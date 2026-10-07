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
    //fungsi up = apa yg dilakukan ketika migration dijalankan
    //menerapkan perubahan
    {
        Schema::table('undangans', function (Blueprint $table) {
            $table->string('rekening_bank') -> nullable();//nama bank
            $table->string('rekening_nomor') -> nullable();//no rek
            $table->string('rekening_nama') -> nullable();//nama pemilik rekening
            $table->string('qris_gambar') -> nullable();//path url file gambar qr code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    //down : membatalkan perubahan
    //tapi dijalkankan saat php artisan migrate

    {
        Schema::table('undangans', function (Blueprint $table) {
            //

            $table->dropColumn(['rekening_bank', 'rekening_nomor', 'rekening_nama', 'qris_gambar']);
        });
    }
};
