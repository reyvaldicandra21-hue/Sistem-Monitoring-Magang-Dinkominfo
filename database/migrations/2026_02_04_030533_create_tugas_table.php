<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {

            $table->id();

            // pembimbing
            $table->foreignId('pembimbing_id')
                ->constrained('pembimbings')
                ->cascadeOnDelete();

            // data tugas
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('deadline')->nullable();

            $table->enum('status', ['belum','sebagian','selesai'])
                ->default('belum');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
