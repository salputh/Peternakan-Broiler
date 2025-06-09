<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                return match ($user->user_group) {
                    'developer' => redirect()->route('dev.dashboard'),

                    'owner' => redirect()->route('owner.dashboard', [
                        'peternakan' => $user->peternakan?->slug ?? $user->peternakan_id
                    ]),

                    'manager' => redirect()->route('manager.kandang.show', [
                        'peternakan' => $user->peternakan?->slug ?? $user->peternakan_id,
                        'kandang'    => $user->kandang?->slug ?? $user->kandang_id,
                    ]),

                    'operator' => redirect()->route('operator.kandang.show', [
                        'peternakan' => $user->peternakan?->slug ?? $user->peternakan_id,
                        'kandang'    => $user->kandang?->slug ?? $user->kandang_id,
                    ]),

                    default => redirect()->route('login.form'),
                };
            }
        }

        return $next($request);
    }
}
