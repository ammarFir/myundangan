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
            //data mempleai pria
            $table->string('nama_lengkap_pria')->nullable();
            $table->string('anak_ke_pria')->nullable();
            $table->string('orang_tua_pria')->nullable();
            $table->string('instagram_pria')->nullable();
            $table->string('foto_pria')->nullable();
            //data mempleai wanita
            $table->string('nama_lengkap_wanita')->nullable();
            $table->string('anak_ke_wanita')->nullable();
            $table->string('orang_tua_wanita')->nullable();
            $table->string('instagram_wanita')->nullable();
            $table->string('foto_wanita')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('undangans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap_pria',
                'anak_ke_pria',
                'orang_tua_pria',
                'instagram_pria',
                'foto_pria',
                'nama_lengkap_wanita',
                'anak_ke_wanita',
                'orang_tua_wanita',
                'instagram_wanita',
                'foto_wanita',
            ]);
        });
    }
};
