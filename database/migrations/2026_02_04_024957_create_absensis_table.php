<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {

            $table->id();

            $table->foreignId('peserta_pkl_id')
                  ->constrained('peserta_pkls')
                  ->cascadeOnDelete();

            $table->date('tanggal');

            $table->time('jam_masuk')->nullable();

            $table->time('jam_pulang')->nullable();

            $table->string('status')->default('hadir');

            $table->string('keterangan')->nullable();

            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
