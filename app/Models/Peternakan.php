<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kandangs;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class Peternakan extends Model
{
    use HasFactory;
    protected $table = 'peternakans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'owner_id',
        'is_active',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($peternakan) {
            $slug = Str::slug($peternakan->nama) ?: 'peternakan';
            $originalSlug = $slug;
            $count = 1;

            while (Peternakan::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $peternakan->slug = $slug;
        });

        static::updating(function ($peternakan) {
            if ($peternakan->isDirty('nama')) {
                $slug = Str::slug($peternakan->nama) ?: 'peternakan';
                $originalSlug = $slug;
                $count = 1;

                while (Peternakan::where('slug', $slug)->where('id', '!=', $peternakan->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $peternakan->slug = $slug;
            }
        });

        View::composer('developer.*', function ($view) {
            $view->with([
                'totalOwners'      => User::where('role', 'owner')->count(),
                'totalPeternakan'  => Peternakan::count(),
                'activePeternakan' => Peternakan::where('is_active', true)->count(),
            ]);
        });
    }



    public function kandangs()
    {
        return $this->hasMany(Kandangs::class, 'peternakan_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
