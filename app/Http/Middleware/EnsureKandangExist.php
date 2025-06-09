<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Kandangs;

class EnsureKandangExist
{
    public function handle($request, Closure $next)
    {
        if (Kandangs::count() === 0) {
            return redirect()->route('kandang.create')
                ->with('warning', 'Anda harus membuat kandang terlebih dahulu sebelum melakukan registrasi.');
        }

        return $next($request);
    }
}
