<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();

            // relasi ke peserta
            $table->foreignId('peserta_pkl_id')
                  ->constrained('peserta_pkls')
                  ->cascadeOnDelete();

            // 🔥 nilai sikap
            $table->integer('disiplin')->nullable();
            $table->integer('tanggung_jawab')->nullable();
            $table->integer('kerjasama')->nullable();
            $table->integer('etika')->nullable();
            $table->integer('inisiatif')->nullable();

            // hasil
            $table->float('nilai_akhir')->nullable();
            $table->string('predikat')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
