<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\PesertaPkl;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_pkls', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Isi UUID untuk data yang sudah ada
        foreach (\DB::table('peserta_pkls')->whereNull('uuid')->get() as $row) {
            \DB::table('peserta_pkls')
                ->where('id', $row->id)
                ->update(['uuid' => (string) Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('peserta_pkls', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
