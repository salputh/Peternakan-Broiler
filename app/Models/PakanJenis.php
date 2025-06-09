<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PakanJenis extends Model
{
    use HasFactory;

    protected $table = 'pakan_jenis';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode',
        'keterangan',
    ];
}
