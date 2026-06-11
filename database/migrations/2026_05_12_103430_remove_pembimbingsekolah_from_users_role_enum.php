<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus user dengan role pembimbingsekolah jika masih ada
        DB::table('users')->where('role', 'pembimbingsekolah')->delete();

        // MySQL: ubah enum kolom role — hapus 'pembimbingsekolah'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','pesertapkl','pembimbing') DEFAULT 'pesertapkl'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','pesertapkl','pembimbing','pembimbingsekolah') DEFAULT 'pesertapkl'");
    }
};
