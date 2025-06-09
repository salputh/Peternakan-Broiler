<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'password',
        'role',
        'photo',
        'peternakan_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $slug = Str::slug($user->name) ?: 'user';
            $originalSlug = $slug;
            $count = 1;

            // Handle duplikat slug
            while (User::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $user->slug = $slug;
        });

        // Saat mengupdate user dan nama berubah
        static::updating(function ($user) {
            if ($user->isDirty('name')) {
                $slug = Str::slug($user->name) ?: 'user';
                $originalSlug = $slug;
                $count = 1;

                while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $user->slug = $slug;
            }
        });

        Route::bind('owner', function ($value) {
            return User::where('slug', $value)->where('role', 'owner')->firstOrFail();
        });
    }


    public function peternakan()
    {
        return $this->belongsTo(Peternakan::class, 'peternakan_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
