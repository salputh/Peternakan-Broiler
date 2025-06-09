<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardPerformaHarian extends Model
{
     use HasFactory;

     protected $table = 'standard_performa_harian';

     protected $fillable = [
          'age',
          'deplesi_std_cum',
          'pakan_std_zak',
          'pakan_std_gr_ek',
          'pakan_std_cum_gr_ek',
          'bw_std_abw',
          'bw_std_dg',
          'std_suhu_min',
          'std_suhu_max',
          'std_kelembapan',
          'fcr_std',
          'ip_std'
     ];
}
