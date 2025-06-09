<?php

namespace App\Models;

use App\Models\DataPeriode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokPakanKeluar extends Model
{
    use HasFactory;

    protected $table = 'stok_pakan_keluar';

    protected $fillable = [
        'data_periode_id',
        'stok_pakan_id',
        'jumlah_keluar',
        'tanggal'
    ];

    public function dataPeriodes()
    {
        return $this->belongsTo(DataPeriode::class, 'data_periode_id', 'id');
    }

    public function stokPakans()
    {
        return $this->belongsTo(StokPakan::class, 'stok_pakan_id');
    }
}
