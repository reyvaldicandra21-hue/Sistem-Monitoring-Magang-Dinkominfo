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
        Schema::table('absensi', function (Blueprint $table) {

            // 🔥 TAMBAH KOLOM GPS
            $table->string('latitude')->nullable()->after('foto');
            $table->string('longitude')->nullable()->after('latitude');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {

            // 🔥 HAPUS KOLOM JIKA ROLLBACK
            $table->dropColumn(['latitude','longitude']);

        });
    }
};
