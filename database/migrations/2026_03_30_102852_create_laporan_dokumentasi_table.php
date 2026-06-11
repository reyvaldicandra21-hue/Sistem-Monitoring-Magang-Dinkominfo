<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_dokumentasi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('laporan_harian_id');
            $table->string('file');

            $table->timestamps();

            // relasi ke laporan_harian
            $table->foreign('laporan_harian_id')
                  ->references('id')
                  ->on('laporan_harians')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_dokumentasi');
    }
};
