<?php

namespace App\Models;

use App\Models\DataPeriode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deplesi extends Model
{
    use HasFactory;

    protected $table = 'deplesis';
    protected $fillable = [
        'data_periode_id',
        'jumlah_mati',
        'jumlah_afkir',
        'foto',
        'keterangan',
        'tanggal',
    ];

    public function dataPeriode()
    {
        return $this->belongsTo(DataPeriode::class, 'data_periode_id', 'id');
    }
}
