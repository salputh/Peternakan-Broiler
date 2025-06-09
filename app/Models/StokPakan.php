<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kandangs;
use App\Models\StokPakanMasuk;
use App\Models\StokPakanKeluar;

class StokPakan extends Model
{
    use HasFactory;

    protected $table = 'stok_pakans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'periode_id',
        'kandang_id',
        'pakan_jenis_id',
        'jumlah_stok'
    ];

    public function kandangs()
    {
        return $this->belongsTo(Kandangs::class, 'kandang_id');
    }

    public function pakanJenis()
    {
        return $this->belongsTo(PakanJenis::class, 'pakan_jenis_id');
    }

    public function periodes()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function stokPakanMasuk()
    {
        return $this->hasMany(StokPakanMasuk::class, 'stok_pakan_id');
    }

    public function stokPakanKeluar()
    {
        return $this->hasMany(StokPakanKeluar::class, 'stok_pakan_id');
    }
}
