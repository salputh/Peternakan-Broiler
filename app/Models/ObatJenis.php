<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatJenis extends Model
{
     use HasFactory;

     /**
      * The table associated with the model.
      *
      * @var string
      */
     protected $table = 'obat_jenis';

     /**
      * The attributes that are mass assignable.
      *
      * @var array<int, string>
      */
     protected $fillable = [
          'nama',
          'kategori',
          'keterangan',
          'satuan',
     ];

     /**
      * Get the stok obat for the obat jenis.
      */
     public function stokObats()
     {
          return $this->hasMany(StokObat::class, 'obat_jenis_id');
     }
}
