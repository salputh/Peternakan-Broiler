<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StandardPerformaHarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada sebelumnya untuk menghindari duplikasi saat seeding ulang
        DB::table('standard_performa_harian')->truncate();

        $dataStandards = [
            // Data yang Anda berikan
            [
                'age' => 1,
                'deplesi_std_cum' => 0.10,
                'pakan_std_gr_ek' => 18,
                'pakan_std_cum_gr_ek' => 18,
                'bw_std_abw' => 56,
                'bw_std_dg' => 14,
                'fcr_std' => 0.321,
                'ip_std' => null
            ],
            [
                'age' => 2,
                'deplesi_std_cum' => 0.23,
                'pakan_std_gr_ek' => 22,
                'pakan_std_cum_gr_ek' => 40,
                'bw_std_abw' => 73,
                'bw_std_dg' => 17,
                'fcr_std' => 0.549,
                'ip_std' => null
            ],
            [
                'age' => 3,
                'deplesi_std_cum' => 0.36,
                'pakan_std_gr_ek' => 26,
                'pakan_std_cum_gr_ek' => 66,
                'bw_std_abw' => 94,
                'bw_std_dg' => 20,
                'fcr_std' => 0.711,
                'ip_std' => null
            ],
            [
                'age' => 4,
                'deplesi_std_cum' => 0.49,
                'pakan_std_gr_ek' => 30,
                'pakan_std_cum_gr_ek' => 96,
                'bw_std_abw' => 117,
                'bw_std_dg' => 24,
                'fcr_std' => 0.810,
                'ip_std' => null
            ],
            [
                'age' => 5,
                'deplesi_std_cum' => 0.62,
                'pakan_std_gr_ek' => 34,
                'pakan_std_cum_gr_ek' => 130,
                'bw_std_abw' => 144,
                'bw_std_dg' => 27,
                'fcr_std' => 0.914,
                'ip_std' => null
            ],
            [
                'age' => 6,
                'deplesi_std_cum' => 0.75,
                'pakan_std_gr_ek' => 38,
                'pakan_std_cum_gr_ek' => 168,
                'bw_std_abw' => 174,
                'bw_std_dg' => 30,
                'fcr_std' => 0.979,
                'ip_std' => null // Menambahkan 'ip_std' null agar konsisten
            ],
            [
                'age' => 7,
                'deplesi_std_cum' => 0.88,
                'pakan_std_gr_ek' => 43,
                'pakan_std_cum_gr_ek' => 211,
                'bw_std_abw' => 205,
                'bw_std_dg' => 33,
                'fcr_std' => 1.029,
                'ip_std' => null
            ],
            [
                'age' => 8,
                'deplesi_std_cum' => 1.01,
                'pakan_std_gr_ek' => 45,
                'pakan_std_cum_gr_ek' => 256,
                'bw_std_abw' => 244,
                'bw_std_dg' => 37,
                'fcr_std' => 1.068,
                'ip_std' => 270
            ],
            [
                'age' => 9,
                'deplesi_std_cum' => 1.14,
                'pakan_std_gr_ek' => 53,
                'pakan_std_cum_gr_ek' => 309,
                'bw_std_abw' => 285,
                'bw_std_dg' => 41,
                'fcr_std' => 1.099,
                'ip_std' => 268
            ],
            [
                'age' => 10,
                'deplesi_std_cum' => 1.27,
                'pakan_std_gr_ek' => 56,
                'pakan_std_cum_gr_ek' => 365,
                'bw_std_abw' => 330,
                'bw_std_dg' => 45,
                'fcr_std' => 1.129,
                'ip_std' => 274
            ],
            [
                'age' => 11,
                'deplesi_std_cum' => 1.40,
                'pakan_std_gr_ek' => 63,
                'pakan_std_cum_gr_ek' => 428,
                'bw_std_abw' => 380,
                'bw_std_dg' => 49,
                'fcr_std' => 1.149,
                'ip_std' => 281
            ],
            [
                'age' => 12,
                'deplesi_std_cum' => 1.53,
                'pakan_std_gr_ek' => 68,
                'pakan_std_cum_gr_ek' => 496,
                'bw_std_abw' => 434,
                'bw_std_dg' => 54,
                'fcr_std' => 1.169,
                'ip_std' => 289
            ],
            [
                'age' => 13,
                'deplesi_std_cum' => 1.68,
                'pakan_std_gr_ek' => 73,
                'pakan_std_cum_gr_ek' => 569,
                'bw_std_abw' => 492,
                'bw_std_dg' => 58,
                'fcr_std' => 1.186,
                'ip_std' => 297
            ],
            [
                'age' => 14,
                'deplesi_std_cum' => 1.79,
                'pakan_std_gr_ek' => 78,
                'pakan_std_cum_gr_ek' => 647,
                'bw_std_abw' => 553,
                'bw_std_dg' => 61,
                'fcr_std' => 1.208,
                'ip_std' => 305
            ],
            [
                'age' => 15,
                'deplesi_std_cum' => 1.92,
                'pakan_std_gr_ek' => 84,
                'pakan_std_cum_gr_ek' => 731,
                'bw_std_abw' => 618,
                'bw_std_dg' => 65,
                'fcr_std' => 1.219,
                'ip_std' => 312
            ],
            [
                'age' => 16,
                'deplesi_std_cum' => 2.06,
                'pakan_std_gr_ek' => 90,
                'pakan_std_cum_gr_ek' => 821,
                'bw_std_abw' => 685,
                'bw_std_dg' => 68,
                'fcr_std' => 1.229,
                'ip_std' => 317
            ],
            [
                'age' => 17,
                'deplesi_std_cum' => 2.18,
                'pakan_std_gr_ek' => 96,
                'pakan_std_cum_gr_ek' => 917,
                'bw_std_abw' => 756,
                'bw_std_dg' => 71,
                'fcr_std' => 1.239,
                'ip_std' => 327
            ],
            [
                'age' => 18,
                'deplesi_std_cum' => 2.31,
                'pakan_std_gr_ek' => 100,
                'pakan_std_cum_gr_ek' => 1019,
                'bw_std_abw' => 830,
                'bw_std_dg' => 74,
                'fcr_std' => 1.249,
                'ip_std' => 334
            ],
            [
                'age' => 19,
                'deplesi_std_cum' => 2.44,
                'pakan_std_gr_ek' => 106,
                'pakan_std_cum_gr_ek' => 1125,
                'bw_std_abw' => 913,
                'bw_std_dg' => 83,
                'fcr_std' => 1.259,
                'ip_std' => 343
            ],
            [
                'age' => 20,
                'deplesi_std_cum' => 2.57,
                'pakan_std_gr_ek' => 114,
                'pakan_std_cum_gr_ek' => 1239,
                'bw_std_abw' => 987,
                'bw_std_dg' => 80,
                'fcr_std' => 1.272,
                'ip_std' => 368
            ],
            [
                'age' => 21,
                'deplesi_std_cum' => 2.70,
                'pakan_std_gr_ek' => 120,
                'pakan_std_cum_gr_ek' => 1359,
                'bw_std_abw' => 1069,
                'bw_std_dg' => 82,
                'fcr_std' => 1.294,
                'ip_std' => 383
            ],
            [
                'age' => 22,
                'deplesi_std_cum' => 2.83,
                'pakan_std_gr_ek' => 126,
                'pakan_std_cum_gr_ek' => 1485,
                'bw_std_abw' => 1153,
                'bw_std_dg' => 84,
                'fcr_std' => 1.317,
                'ip_std' => 387
            ],
            [
                'age' => 23,
                'deplesi_std_cum' => 2.86,
                'pakan_std_gr_ek' => 132,
                'pakan_std_cum_gr_ek' => 1617,
                'bw_std_abw' => 1239,
                'bw_std_dg' => 86,
                'fcr_std' => 1.337,
                'ip_std' => 391
            ],
            [
                'age' => 24,
                'deplesi_std_cum' => 3.09,
                'pakan_std_gr_ek' => 138,
                'pakan_std_cum_gr_ek' => 1755,
                'bw_std_abw' => 1327,
                'bw_std_dg' => 88,
                'fcr_std' => 1.361,
                'ip_std' => 394
            ],
            [
                'age' => 25,
                'deplesi_std_cum' => 3.22,
                'pakan_std_gr_ek' => 144,
                'pakan_std_cum_gr_ek' => 1899,
                'bw_std_abw' => 1418,
                'bw_std_dg' => 91,
                'fcr_std' => 1.382,
                'ip_std' => 398
            ],
            [
                'age' => 26,
                'deplesi_std_cum' => 3.35,
                'pakan_std_gr_ek' => 150,
                'pakan_std_cum_gr_ek' => 2049,
                'bw_std_abw' => 1510,
                'bw_std_dg' => 92,
                'fcr_std' => 1.405,
                'ip_std' => 400
            ],
            [
                'age' => 27,
                'deplesi_std_cum' => 3.40,
                'pakan_std_gr_ek' => 156,
                'pakan_std_cum_gr_ek' => 2205,
                'bw_std_abw' => 1604,
                'bw_std_dg' => 94,
                'fcr_std' => 1.426,
                'ip_std' => 407
            ],
            [
                'age' => 28,
                'deplesi_std_cum' => 3.61,
                'pakan_std_gr_ek' => 162,
                'pakan_std_cum_gr_ek' => 2367,
                'bw_std_abw' => 1698,
                'bw_std_dg' => 95,
                'fcr_std' => 1.446,
                'ip_std' => 404
            ],
            [
                'age' => 29,
                'deplesi_std_cum' => 3.74,
                'pakan_std_gr_ek' => 168,
                'pakan_std_cum_gr_ek' => 2535,
                'bw_std_abw' => 1794,
                'bw_std_dg' => 96,
                'fcr_std' => 1.470,
                'ip_std' => 405
            ],
            [
                'age' => 30,
                'deplesi_std_cum' => 3.87,
                'pakan_std_gr_ek' => 173,
                'pakan_std_cum_gr_ek' => 2708,
                'bw_std_abw' => 1889,
                'bw_std_dg' => 98,
                'fcr_std' => 1.491,
                'ip_std' => 406
            ],
            [
                'age' => 31,
                'deplesi_std_cum' => 4.00,
                'pakan_std_gr_ek' => 178,
                'pakan_std_cum_gr_ek' => 2886,
                'bw_std_abw' => 1989,
                'bw_std_dg' => 100,
                'fcr_std' => 1.514,
                'ip_std' => 407
            ],
            [
                'age' => 32,
                'deplesi_std_cum' => 4.13,
                'pakan_std_gr_ek' => 183,
                'pakan_std_cum_gr_ek' => 3069,
                'bw_std_abw' => 2087,
                'bw_std_dg' => 98,
                'fcr_std' => 1.531,
                'ip_std' => 407
            ],
            [
                'age' => 33,
                'deplesi_std_cum' => 4.26,
                'pakan_std_gr_ek' => 188,
                'pakan_std_cum_gr_ek' => 3257,
                'bw_std_abw' => 2187,
                'bw_std_dg' => 100,
                'fcr_std' => 1.556,
                'ip_std' => 393
            ],
            [
                'age' => 34,
                'deplesi_std_cum' => 4.39,
                'pakan_std_gr_ek' => 193,
                'pakan_std_cum_gr_ek' => 3450,
                'bw_std_abw' => 2289,
                'bw_std_dg' => 101,
                'fcr_std' => 1.578,
                'ip_std' => 386
            ],
            [
                'age' => 35,
                'deplesi_std_cum' => 4.52,
                'pakan_std_gr_ek' => 198,
                'pakan_std_cum_gr_ek' => 3648,
                'bw_std_abw' => 2390,
                'bw_std_dg' => 101,
                'fcr_std' => 1.600,
                'ip_std' => 407
            ],
        ];

        // Tambahkan data suhu dan kelembaban standar
        // Ini adalah contoh nilai yang bisa disesuaikan lebih lanjut
        $finalData = [];
        foreach ($dataStandards as $item) {
            $age = $item['age'];
            $tempMin = null;
            $tempMax = null;
            $humidity = null;

            if ($age >= 1 && $age <= 7) {
                $tempMin = 30.0;
                $tempMax = 32.0;
                $humidity = 65.0;
            } elseif ($age >= 8 && $age <= 14) {
                $tempMin = 28.0;
                $tempMax = 30.0;
                $humidity = 60.0;
            } elseif ($age >= 15 && $age <= 21) {
                $tempMin = 26.0;
                $tempMax = 28.0;
                $humidity = 55.0;
            } else { // age >= 22
                $tempMin = 24.0;
                $tempMax = 26.0;
                $humidity = 50.0;
            }

            $finalData[] = array_merge($item, [
                'std_suhu_min' => $tempMin,
                'std_suhu_max' => $tempMax,
                'std_kelembapan' => $humidity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('standard_performa_harian')->insert($finalData);
    }
}
