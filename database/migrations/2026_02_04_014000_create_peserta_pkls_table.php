<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('peserta_pkls', function (Blueprint $table) {
    $table->id();

    // Relasi ke users
    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();


    // Relasi pembimbing
    $table->foreignId('pembimbing_id')
        ->nullable()
        ->constrained('pembimbings')
        ->nullOnDelete();

    $table->foreignId('divisi_id')
        ->nullable()
        ->constrained('divisi')
        ->nullOnDelete();

    // 🔥 PERIODE UTAMA DI SINI
    $table->date('tanggal_mulai')->nullable();
    $table->date('tanggal_selesai')->nullable();

    // Data Peserta
    $table->enum('jenis', ['siswa', 'mahasiswa']);
    $table->string('asal_institusi')->nullable();
    $table->string('jurusan')->nullable();
    $table->string('no_hp')->nullable();

    // 🔥 SIMPLIFY STATUS
    $table->enum('status', [
        'pending',
        'aktif',
        'ditolak'
    ])->default('pending');

    $table->timestamps();

    // Index
    $table->index('pembimbing_id');
    $table->index('status');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_pkls');
    }
};
