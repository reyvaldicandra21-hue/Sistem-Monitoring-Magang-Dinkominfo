<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_divisi', function (Blueprint $table) {
            $table->id();

            // Relasi ke peserta_pkls
            $table->foreignId('peserta_pkl_id')
                ->constrained('peserta_pkls')
                ->cascadeOnDelete();

            // Divisi sebelumnya
            $table->foreignId('divisi_id_lama')
                ->nullable()
                ->constrained('divisi')
                ->nullOnDelete();

            // Divisi baru
            $table->foreignId('divisi_id_baru')
                ->nullable()
                ->constrained('divisi')
                ->nullOnDelete();

            // Keterangan perubahan
            $table->text('keterangan')->nullable();

            // Tanggal perubahan
            $table->timestamp('tanggal_perubahan')->useCurrent();

            $table->timestamps();

            // Index untuk query yang lebih cepat
            $table->index('peserta_pkl_id');
            $table->index('tanggal_perubahan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_divisi');
    }
};
