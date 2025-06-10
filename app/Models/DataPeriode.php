<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Periode;
use App\Models\BodyWeight;
use App\Models\Deplesi;

class DataPeriode extends Model
{
    use HasFactory;

    protected $table = 'data_periodes';

    protected $fillable = [
        'periode_id',
        'usia',
        'suhu_min',
        'suhu_max',
        'kelembapan',
        'tanggal',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'timestamps' => true
    ];

    /**
     * Relasi ke model Periode.
     * Nama metode 'periodes' harus sesuai dengan pemanggilan di Observer.
     */
    public function periodes()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }

    /**
     * Relasi ke StokPakanKeluar (jika digunakan, pastikan modelnya ada)
     */
    public function stokPakanKeluar()
    {
        return $this->hasMany(StokPakanKeluar::class, 'data_periode_id', 'id');
    }

    /**
     * Relasi ke BodyWeight (jika digunakan, pastikan modelnya ada)
     */
    public function bodyWeight()
    {
        return $this->hasOne(BodyWeight::class, 'data_periode_id', 'id');
    }

    /**
     * Relasi ke Deplesi (jika digunakan, pastikan modelnya ada)
     */
    public function deplesis()
    {
        return $this->hasOne(Deplesi::class, 'data_periode_id', 'id');
    }

    // Tambahkan relasi lain jika ada, misalnya stokObatKeluar
    // public function stokObatKeluar()
    // {
    //     return $this->hasMany(StokObatKeluar::class, 'data_periode_id', 'id');
    // }
}
