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
        Schema::create('log_verifikasi', function (Blueprint $table) {
    $table->id();

    $table->foreignId('laporan_harian_id')
        ->constrained('laporan_harians')
        ->cascadeOnDelete();

    $table->foreignId('pembimbing_id')
        ->constrained('pembimbings')
        ->cascadeOnDelete();

    $table->enum('status', ['menunggu', 'disetujui', 'revisi'])
        ->default('menunggu');

    $table->text('catatan_pembimbing')->nullable();

    $table->timestamps();

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_verifikasis');
    }
};
