<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokPakanMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_pakan_masuk';
    protected $primaryKey = 'id';

    protected $fillable = [
        'stok_pakan_id',
        'tanggal',
        'jumlah_masuk',
    ];

    public function stokPakans()
    {
        return $this->belongsTo(StokPakan::class, 'stok_pakan_id');
    }
}
