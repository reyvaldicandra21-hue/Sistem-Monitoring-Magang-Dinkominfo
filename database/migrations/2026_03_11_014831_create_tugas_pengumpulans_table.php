<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_pengumpulans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tugas_id')
                ->constrained('tugas')
                ->cascadeOnDelete();

            $table->foreignId('peserta_pkl_id')
                ->constrained('peserta_pkls')
                ->cascadeOnDelete();

            $table->string('file')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamp('tanggal_kumpul')->nullable();

            $table->enum('status',[
                'belum',
                'dikumpulkan',
                'terlambat',
                'dinilai'
            ])->default('belum');

            $table->integer('nilai')->nullable();

            $table->text('komentar_pembimbing')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_pengumpulans');
    }
};
