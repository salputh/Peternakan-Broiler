<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kandangs extends Model
{
    use HasFactory;

    protected $table = 'kandangs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kandang',
        'slug',
        'alamat',
        'peternakan_id',
        'kapasitas',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kandang) {
            $slug = Str::slug($kandang->nama_kandang) ?: 'kandang';
            $originalSlug = $slug;
            $count = 1;

            while (Kandangs::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $kandang->slug = $slug;
        });

        static::updating(function ($kandang) {
            if ($kandang->isDirty('nama_kandang')) {
                $slug = Str::slug($kandang->nama_kandang) ?: 'kandang';
                $originalSlug = $slug;
                $count = 1;

                while (User::where('slug', $slug)->where('id', '!=', $kandang->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $kandang->slug = $slug;
            }
        });
    }

    public function peternakan()
    {
        return $this->belongsTo(Peternakan::class, 'peternakan_id', 'id');
    }

    public function periodes()
    {
        return $this->hasMany(Periode::class, 'kandang_id', 'id');
    }

    public function periodeAktif()
    {
        return $this->periodes()->where('aktif', 1)->first();
    }

    public function stokPakans()
    {
        return $this->hasMany(StokPakan::class, 'kandang_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
