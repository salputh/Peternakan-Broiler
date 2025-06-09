<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Periode;
use App\Models\Kandangs;
use App\Models\StokObatMasuk;
use App\Models\StokObatKeluar;

class StokObat extends Model
{
     use HasFactory;

     protected $table = 'stok_obat';

     protected $fillable = [
          'nama_obat',
          'kategori',
          'satuan',
     ];

     public function stokObatMasuk()
     {
          return $this->hasMany(StokObatMasuk::class);
     }

     public function stokObatKeluar()
     {
          return $this->hasMany(StokObatKeluar::class);
     }
}
