<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'TIK',
            'IKP',
            'Sekretariat',
            'Statistika'
        ];

        foreach ($data as $nama) {
            Divisi::firstOrCreate([
                'nama_divisi' => $nama
            ]);
        }
    }
}
