<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisi', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Generate UUID untuk data yang sudah ada
        $divisis = \App\Models\Divisi::whereNull('uuid')->get();
        foreach ($divisis as $divisi) {
            $divisi->uuid = (string) Str::uuid();
            $divisi->save();
        }
    }

    public function down(): void
    {
        Schema::table('divisi', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
