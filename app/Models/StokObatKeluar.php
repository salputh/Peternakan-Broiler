<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokObatKeluar extends Model
{
     use HasFactory;

     protected $table = 'stok_obat_keluar';

     protected $fillable = [
          'stok_obat_id',
          'data_periode_id',
          'tanggal',
          'jumlah_keluar',
     ];

     public function stokObats()
     {
          return $this->belongsTo(StokObat::class, 'stok_obat_id');
     }

     public function dataPeriodes()
     {
          return $this->belongsTo(DataPeriode::class, 'data_periode_id', 'id');
     }
}
