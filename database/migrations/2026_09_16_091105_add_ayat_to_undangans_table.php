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
            $table->text('ayat_teks')->nullable();
            $table->text('ayat_arti')->nullable();
            $table->string('ayat_sumber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('undangans', function (Blueprint $table) {
            $table->dropColumn(['ayat_teks', 'ayat_arti', 
            'ayat_sumber']);
        });
    }
};
