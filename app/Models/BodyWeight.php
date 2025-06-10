<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataPeriode;

class BodyWeight extends Model
{
    use HasFactory;

    protected $table = 'body_weight';

    protected $fillable = [
        'data_periode_id',
        'body_weight_hasil',
        'tanggal',
    ];

    public function dataPeriode()
    {
        return $this->belongsTo(DataPeriode::class, 'data_periode_id', 'id');
    }
}
