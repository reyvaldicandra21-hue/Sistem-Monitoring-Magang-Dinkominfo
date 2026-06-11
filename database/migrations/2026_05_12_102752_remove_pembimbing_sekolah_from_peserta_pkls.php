<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_pkls', function (Blueprint $table) {
            // Drop foreign key constraint first, then the column
            $table->dropForeign(['pembimbing_sekolah_id']);
            $table->dropColumn('pembimbing_sekolah_id');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_pkls', function (Blueprint $table) {
            $table->foreignId('pembimbing_sekolah_id')
                  ->nullable()
                  ->constrained('pembimbing_sekolahs')
                  ->nullOnDelete();
        });
    }
};
