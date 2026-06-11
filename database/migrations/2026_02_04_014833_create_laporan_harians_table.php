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
        Schema::create('laporan_harians', function (Blueprint $table) {
    $table->id();

    $table->foreignId('peserta_pkl_id')
        ->constrained('peserta_pkls')
        ->cascadeOnDelete();

    $table->date('tanggal');
    $table->text('kegiatan');
    $table->text('hasil')->nullable();
    $table->text('kendala')->nullable();

    $table->enum('status', ['menunggu', 'disetujui', 'revisi'])
        ->default('menunggu');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_harians');
    }
};
