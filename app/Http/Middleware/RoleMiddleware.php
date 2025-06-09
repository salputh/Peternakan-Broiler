<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
      public function handle(Request $request, Closure $next, ...$roles): Response
      {
            $user = $request->user();

            if (!$user) {
                  return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
            }

            // Allow developer role to access everything
            if ($user->role === 'developer') {
                  return $next($request);
            }

            // Check role permissions for other users
            if (!in_array($user->role, $roles)) {
                  abort(403, 'Akses ditolak: Anda tidak memiliki izin.');
            }

            return $next($request);
      }
}
