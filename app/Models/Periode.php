<?php

namespace App\Models;

use App\Models\DataPeriode;
use App\Models\Kandangs;
use App\Models\StokPakan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\StokPakanKeluar;
use App\Models\StokPakanMasuk;


class Periode extends Model
{
    use HasFactory;

    protected $table = 'periodes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kandang_id',
        'nama_periode',
        'slug',
        'tanggal_mulai',
        'aktif',
        'jumlah_ayam',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($periode) {
            $slug = Str::slug($periode->nama_periode) ?: 'periode';
            $originalSlug = $slug;
            $count = 1;

            // Handle duplikat slug
            while (Periode::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $periode->slug = $slug;
        });

        // Saat mengupdate periode dan nama berubah
        static::updating(function ($periode) {
            if ($periode->isDirty('nama_periode')) {
                $slug = Str::slug($periode->nama_periode) ?: 'periode';
                $originalSlug = $slug;
                $count = 1;

                while (Periode::where('slug', $slug)->where('id', '!=', $periode->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $periode->slug = $slug;
            }
        });
    }

    public function kandangs()
    {
        return $this->belongsTo(Kandangs::class, 'kandang_id');
    }

    public function dataPeriodes()
    {
        return $this->hasMany(DataPeriode::class, 'periode_id');
    }

    public function stokPakans()
    {
        return $this->hasMany(StokPakan::class, 'periode_id');
    }

    public function stokObatMasuk()
    {
        return $this->hasMany(StokObat::class, 'periode_id');
    }

    public function stokPakanKeluar()
    {
        return $this->hasMany(StokPakanKeluar::class, 'data_periode_id');
    }
    public function stokPakanMasuk()
    {
        return $this->hasMany(StokPakanMasuk::class, 'periode_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
