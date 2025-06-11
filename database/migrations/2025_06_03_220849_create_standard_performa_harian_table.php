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
        Schema::create('standard_performa_harian', function (Blueprint $table) {
            $table->id();
            $table->integer('age')->unique()->check('age >= 0')->comment('Usia ayam dalam hari, kunci untuk mencari standar');

            $table->decimal('deplesi_std_cum', 5, 2)->default(0)->check('deplesi_std_cum >= 0')->comment('Target kumulatif deplesi standar (%)');
            $table->decimal('pakan_std_gr_ek', 10, 2)->default(0)->check('pakan_std_gr_ek >= 0')->comment('Target pakan standar harian per ekor (gram/ekor)');
            $table->decimal('pakan_std_cum_gr_ek', 10, 2)->default(0)->check('pakan_std_cum_gr_ek >= 0')->comment('Target kumulatif pakan standar per ekor (gram/ekor)');
            $table->decimal('bw_std_abw', 10, 2)->default(0)->check('bw_std_abw >= 0')->comment('Target berat badan standar rata-rata (gram/ekor)');
            $table->decimal('bw_std_dg', 10, 2)->default(0)->check('bw_std_dg >= 0')->comment('Target daily gain (pertambahan berat harian) standar (gram/ekor)');

            // Suhu dan Kelembaban standar, bisa nullable jika tidak selalu ada data untuk usia tertentu
            $table->decimal('std_suhu_min', 4, 1)->nullable()->check('std_suhu_min IS NULL OR std_suhu_min >= -50')->comment('Suhu minimum standar harian (Celcius)');
            $table->decimal('std_suhu_max', 4, 1)->nullable()->check('std_suhu_max IS NULL OR std_suhu_max <= 50')->comment('Suhu maksimum standar harian (Celcius)');
            $table->decimal('std_kelembapan', 4, 1)->nullable()->check('std_kelembapan IS NULL OR (std_kelembapan >= 0 AND std_kelembapan <= 100)')->comment('Kelembaban relatif standar harian (%)');

            $table->decimal('fcr_std', 5, 3)->default(0)->check('fcr_std >= 0')->comment('Target FCR standar');
            $table->integer('ip_std')->nullable()->check('ip_std IS NULL OR ip_std >= 0')->comment('Target Index Performance standar'); // Bisa null atau default 0

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_performa_harian');
    }
};
