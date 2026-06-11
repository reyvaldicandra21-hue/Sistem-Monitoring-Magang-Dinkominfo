<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pembimbing;
use App\Models\PesertaPkl;

use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ======================
        // ADMIN
        // ======================
        User::create([
            'name' => 'Admin PKL',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // ======================
        // PEMBIMBING INDUSTRI
        // ======================
        $userPembimbing = User::create([
            'name' => 'Pembimbing Industri',
            'email' => 'pembimbing@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'pembimbing',
        ]);

        $pembimbing = Pembimbing::create([
            'user_id' => $userPembimbing->id,
            'nama' => 'Budi Santoso',
            'jabatan' => 'Supervisor IT',
        ]);

        // ======================
        // PESERTA PKL
        // ======================
        $userPeserta = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@example.com',
            'password' => Hash::make('password'),
            'role' => 'pesertapkl',
        ]);

        PesertaPkl::create([
            'user_id' => $userPeserta->id,

            // 🔥 PERIODE UTAMA
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(3),

            'jenis' => 'siswa',
            'asal_institusi' => 'SMK Negeri 1',
            'jurusan' => 'RPL',

            'status' => 'pending',
        ]);


    }
}
