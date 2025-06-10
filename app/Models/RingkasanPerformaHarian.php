<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RingkasanPerformaHarian extends Model
{
     use HasFactory;

     protected $table = 'ringkasan_performa_harian';

     protected $fillable = [
          'kandang_id',
          'periode_id',
          'data_periode_id',
          'tanggal_catat',
          'usia_ke',

          'jumlah_ayam_awal',
          'jumlah_mati_harian',
          'jumlah_afkir_harian',
          'jumlah_deplesi_harian',
          'cum_deplesi_harian',

          'std_pakan_zak_harian',
          'std_cum_zak_harian',
          'std_gr_per_ekor_harian',
          'std_cum_gr_per_ekor_harian',
          'act_zak_harian',
          'act_jenis_pakan_harian',
          'act_cum_zak_harian',
          'act_gr_per_ekor_harian',
          'act_cum_gr_per_ekor_harian',
          'delta_zak_harian',
          'delta_cum_zak_harian',
          'delta_gr_per_ekor_harian',
          'delta_cum_gr_per_ekor_harian',

          'std_bw_abw_harian',
          'std_bw_dg_gr_harian',

          'act_bw_abw_harian',
          'act_bw_dg_gr_harian',
          'delta_bw_abw_harian',
          'delta_bw_dg_gr_harian',

          'obat_jenis_harian',
          'obat_jumlah_harian',

          'std_lingkungan_suhu_min_harian',
          'std_lingkungan_suhu_max_harian',
          'std_lingkungan_persen_hum_harian',
          'act_lingkungan_suhu_min_harian',
          'act_lingkungan_suhu_max_harian',
          'act_lingkungan_persen_hum_harian',
          'delta_lingkungan_suhu_min_harian',
          'delta_lingkungan_suhu_max_harian',
          'delta_lingkungan_persen_hum_harian',

          'std_fcr_harian',
          'act_fcr_harian',
          'delta_fcr_harian',
          'std_index_performance_harian',
          'act_index_performance_harian',
          'delta_index_performance_harian',
          'jumlah_ayam_dipanen',
          'jumlah_ayam_akhir',
     ];

     protected $casts = [
          'tanggal_catat' => 'date',
     ];

     public function periode()
     {
          return $this->belongsTo(Periode::class, 'periode_id');
     }

     /**
      * Mendapatkan kandang yang memiliki ringkasan performa harian ini.
      */
     public function kandang()
     {
          return $this->belongsTo(Kandangs::class, 'kandang_id', 'id');
     }

     /**
      * Mendapatkan data_periode yang terkait dengan ringkasan harian ini.
      */
     public function dataPeriodes()
     {
          return $this->belongsTo(DataPeriode::class, 'data_periode_id');
     }

     /**
      * Mendapatkan standar performa harian yang terkait dengan usia ini.
      * Ini bukan relasi langsung many-to-one, tapi lebih ke lookup.
      * Anda bisa menggunakan ini untuk memuat standar secara eager load
      * atau menanganinya di Observer/Controller.
      */
     // public function standardPerforma()
     // {
     //     return $this->belongsTo(StandardPerformaHarian::class, 'hari_ke_periode', 'age');
     // }
}
