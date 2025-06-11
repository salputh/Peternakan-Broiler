<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ringkasan_performa_harian', function (Blueprint $table) {
            $table->bigIncrements('id'); // Kolom 'id' sebagai primary key (bigint) auto-increment

            // Foreign Keys dan Unique Constraint
            $table->bigInteger('data_periode_id')->unsigned();
            $table->foreign('data_periode_id')->references('id')->on('data_periodes')->onDelete('cascade')->comment('Foreign Key ke tabel data_periodes');

            $table->bigInteger('periode_id')->unsigned();
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade')->comment('Foreign Key ke tabel periodes');

            $table->bigInteger('kandang_id')->unsigned();
            $table->foreign('kandang_id')->references('id')->on('kandangs')->onDelete('cascade')->comment('Foreign Key ke tabel kandangs');

            // Unique constraint untuk memastikan satu ringkasan per hari per periode per kandang
            $table->unique(['data_periode_id', 'periode_id', 'kandang_id'], 'unique_ringkasan_harian_per_day');

            // Kolom Dasar Harian
            $table->timestamp('tanggal_catat')->comment('Tanggal pencatatan, diambil dari data_periodes.tanggal');
            $table->integer('usia_ke')->comment('Usia ayam dalam hari, diambil dari data_periodes.usia');
            $table->integer('jumlah_ayam_awal')->comment('Jumlah ayam di awal hari');

            // Data Populasi & Deplesi
            $table->integer('jumlah_mati_harian')->default(0)->comment('Jumlah ayam mati pada hari itu');
            $table->integer('jumlah_afkir_harian')->default(0)->comment('Jumlah ayam diafkir pada hari itu');
            $table->integer('jumlah_deplesi_harian')->default(0)->comment('Hasil: jumlah_mati_harian + jumlah_afkir_harian');
            $table->numeric('cum_deplesi_harian', 5, 2)->default(0)->comment('Persentase deplesi kumulatif dari awal periode');
            $table->numeric('std_cum_deplesi_harian', 5, 2)->default(0)->comment('Persentase deplesi kumulatif dari awal periode');

            // Data Pakan (Standard & Aktual)
            $table->numeric('std_pakan_zak_harian', 10, 2)->default(0)->comment('Target pakan standar harian dalam zak');
            $table->numeric('std_cum_zak_harian', 10, 2)->default(0)->comment('Target kumulatif pakan standar dalam zak');
            $table->numeric('std_gr_per_ekor_harian', 10, 2)->default(0)->comment('Target pakan standar harian per ekor (gram/ekor)');
            $table->numeric('std_cum_gr_per_ekor_harian', 10, 2)->default(0)->comment('Target kumulatif pakan standar per ekor (gram/ekor)');

            $table->numeric('act_zak_harian', 10, 2)->default(0)->comment('Total pakan keluar aktual hari ini dalam zak');
            $table->varchar('act_jenis_pakan_harian', 10)->nullable()->comment('Jenis pakan aktual yang diberikan hari ini');
            $table->numeric('act_cum_zak_harian', 10, 2)->default(0)->comment('Akumulasi aktual pakan dalam zak');
            $table->numeric('act_gr_per_ekor_harian', 10, 2)->default(0)->comment('Konsumsi pakan aktual per ekor per hari');
            $table->numeric('act_cum_gr_per_ekor_harian', 10, 2)->default(0)->comment('Konsumsi pakan aktual kumulatif per ekor');

            // Delta Pakan (Aktual vs Standard)
            $table->numeric('delta_zak_harian', 10, 2)->default(0)->comment('Selisih pakan harian aktual vs standar (zak)');
            $table->numeric('delta_cum_zak_harian', 10, 2)->default(0)->comment('Selisih pakan kumulatif aktual vs standar (zak)');
            $table->numeric('delta_gr_per_ekor_harian', 10, 2)->default(0)->comment('Selisih pakan harian aktual vs standar (gr/ekor)');
            $table->numeric('delta_cum_gr_per_ekor_harian', 10, 2)->default(0)->comment('Selisih pakan kumulatif aktual vs standar (gr/ekor)');

            // Data Berat Badan (BW) & Daily Gain (DG)
            $table->numeric('std_bw_abw_harian', 10, 2)->default(0)->comment('Target berat badan standar rata-rata (gram/ekor)');
            $table->numeric('std_bw_dg_gr_harian', 10, 2)->default(0)->comment('Target daily gain standar (gram/ekor)');

            $table->numeric('act_bw_abw_harian', 10, 2)->default(0)->comment('Berat badan aktual rata-rata (gram/ekor)');
            $table->numeric('act_bw_dg_gr_harian', 10, 2)->default(0)->comment('Daily gain aktual (gram/ekor)');

            $table->numeric('delta_bw_abw_harian', 10, 2)->default(0)->comment('Selisih berat badan aktual vs standar');
            $table->numeric('delta_bw_dg_gr_harian', 10, 2)->default(0)->comment('Selisih daily gain aktual vs standar');

            // Data Obat (jika ingin diringkas di sini)
            $table->varchar('obat_jenis_harian', 20)->nullable()->comment('Jenis obat yang diberikan hari ini');
            $table->numeric('obat_jumlah_harian', 10, 2)->default(0)->comment('Jumlah obat yang diberikan hari ini');

            // Data Lingkungan (Standard & Aktual)
            $table->numeric('std_lingkungan_suhu_min_harian', 4, 1)->nullable()->comment('Suhu min standar harian');
            $table->numeric('std_lingkungan_suhu_max_harian', 4, 1)->nullable()->comment('Suhu max standar harian');
            $table->numeric('std_lingkungan_persen_hum_harian', 4, 1)->nullable()->comment('Kelembaban standar harian');

            $table->numeric('act_lingkungan_suhu_min_harian', 4, 1)->nullable()->comment('Suhu min aktual harian');
            $table->numeric('act_lingkungan_suhu_max_harian', 4, 1)->nullable()->comment('Suhu max aktual harian');
            $table->numeric('act_lingkungan_persen_hum_harian', 4, 1)->nullable()->comment('Kelembaban aktual harian');

            // Delta Lingkungan
            $table->numeric('delta_lingkungan_suhu_min_harian', 4, 1)->default(0)->comment('Selisih suhu min aktual vs standar');
            $table->numeric('delta_lingkungan_suhu_max_harian', 4, 1)->default(0)->comment('Selisih suhu max aktual vs standar');
            $table->numeric('delta_lingkungan_persen_hum_harian', 4, 1)->default(0)->comment('Selisih kelembaban aktual vs standar');

            // Data Indikator Performa
            $table->numeric('std_fcr_harian', 5, 3)->default(0)->comment('Target FCR standar');
            $table->numeric('act_fcr_harian', 5, 3)->default(0)->comment('FCR aktual');
            $table->numeric('delta_fcr_harian', 5, 3)->default(0)->comment('Selisih FCR aktual vs standar');

            $table->integer('std_index_performance_harian')->nullable()->comment('Target Index Performance standar');
            $table->integer('act_index_performance_harian')->nullable()->comment('Index Performance aktual');
            $table->integer('delta_index_performance_harian')->nullable()->comment('Selisih Index Performance aktual vs standar');

            $table->integer('jumlah_ayam_dipanen')->nullable()->default(0)->comment('Jumlah ayam dipanen pada hari itu');
            $table->integer('jumlah_ayam_akhir')->comment('Jumlah ayam di akhir hari');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ringkasan_performa_harian');
    }
};
