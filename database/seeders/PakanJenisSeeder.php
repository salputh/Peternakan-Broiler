<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PakanJenis;

class PakanJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PakanJenis::create([
            'kode' => 'SB10',
            'keterangan' => 'Starter - usia 1–10 hari',
        ]);

        PakanJenis::create([
            'kode' => 'SB11',
            'keterangan' => 'Grower - usia 11–21 hari',
        ]);

        PakanJenis::create([
            'kode' => 'SB12',
            'keterangan' => 'Finisher - usia 22–panen',
        ]);
    }
}
