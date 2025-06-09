<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokObatMasuk extends Model
{
     use HasFactory;

     /**
      * The table associated with the model.
      *
      * @var string
      */
     protected $table = 'stok_obat_masuk'; // Nama tabel di database adalah 'stok_obat_masuk'

     /**
      * The attributes that are mass assignable.
      *
      * @var array<int, string>
      */
     protected $fillable = [
          'nama_obat_input',   // Kolom untuk nama obat yang diinput user
          'kategori_input',    // Kolom untuk kategori obat yang diinput user (bisa nullable)
          'stok_obat_id',           // Foreign key ke tabel 'obats'
          'periode_id',        // Foreign key ke tabel 'periodes'
          'kandang_id',        // Foreign key ke tabel 'kandangs'
          'tanggal',
          'jumlah_masuk',
     ];

     /**
      * The attributes that should be cast.
      *
      * @var array<string, string>
      */
     protected $casts = [
          'tanggal' => 'date',
     ];

     /**
      * Get the Obat (master) that this stock entry belongs to.
      * Ini adalah relasi ke tabel master 'obats'.
      */
     public function stokObats()
     {
          return $this->belongsTo(StokObat::class, 'stok_obat_id');
          // Parameter kedua 'obat_id' adalah foreign key di StokObatMasuk
          // Parameter ketiga 'obat_id' adalah local key di model Obat
     }

     /**
      * Get the Periode that this stock entry belongs to.
      */
     public function periodes()
     {
          return $this->belongsTo(Periode::class, 'periode_id');
          // Asumsi primary key di tabel 'periodes' adalah 'id'
     }

     /**
      * Get the Kandang that this stock entry belongs to.
      */
     public function kandangs()
     {
          return $this->belongsTo(Kandangs::class, 'kandang_id');
          // Asumsi primary key di tabel 'kandangs' adalah 'id'
          // Pastikan Anda memiliki model 'Kandang' atau sesuaikan namanya jika berbeda (misal 'Kandangs')
     }
}
