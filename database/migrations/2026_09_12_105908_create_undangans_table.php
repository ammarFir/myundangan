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
        Schema::create('undangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('tema')->nullable();

            $table->string('nama_pria');
            $table->string('nama_wanita');

            $table->date('tanggal_acara')->nullable();
            $table->time('waktu_acara')->nullable();

            $table->string('lokasi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('link_maps')->nullable();
            $table->string('link_streaming')->nullable();
            $table->string('musik')->nullable();

            $table->enum('status', ['draft', 'pending_payment', 'active'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('undangans');
    }
};
