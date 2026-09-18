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
        Schema::table('undangans', function (Blueprint $table) {
            // hapus field lama yang generic (gak dipakai lagi)
            $table->dropColumn(['tanggal_acara', 'waktu_acara', 'lokasi', 'alamat', 'link_maps']);

            // field baru buat Akad Nikah
            $table->date('akad_tanggal')->nullable();
            $table->time('akad_waktu_mulai')->nullable();
            $table->time('akad_waktu_selesai')->nullable();
            $table->string('akad_lokasi')->nullable();
            $table->text('akad_alamat')->nullable();
            $table->string('akad_link_maps')->nullable();

            // field baru buat Resepsi
            $table->date('resepsi_tanggal')->nullable();
            $table->time('resepsi_waktu_mulai')->nullable();
            $table->time('resepsi_waktu_selesai')->nullable();
            $table->string('resepsi_lokasi')->nullable();
            $table->text('resepsi_alamat')->nullable();
            $table->string('resepsi_link_maps')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('undangans', function (Blueprint $table) {
            // kembalikan field lama kalau migration di-rollback
            $table->date('tanggal_acara')->nullable();
            $table->time('waktu_acara')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('link_maps')->nullable();

            // hapus field baru
            $table->dropColumn([
                'akad_tanggal', 'akad_waktu_mulai', 'akad_waktu_selesai', 'akad_lokasi', 'akad_alamat', 'akad_link_maps',
                'resepsi_tanggal', 'resepsi_waktu_mulai', 'resepsi_waktu_selesai', 'resepsi_lokasi', 'resepsi_alamat', 'resepsi_link_maps',
                  ]);
        });
    }
};
