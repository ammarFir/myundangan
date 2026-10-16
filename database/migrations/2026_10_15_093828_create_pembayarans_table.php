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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();//pk

            $table->foreignId('undangan_id')//undangan yg dibayar
                ->constrained()
                ->cascadeOnDelete();

            $table->string('bukti');
            $table->unsignedInteger('nominal')->nullable();

            //status pembayaran
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
