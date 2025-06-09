<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     // =========== FORM LOGIN DEVELOPER ===========
     public function devLoginForm()
     {
          return view('auth.dev-login');
     }

     // =========== PROSES LOGIN DEVELOPER ===========
     public function processDevLogin(Request $request)
     {
          $credentials = $request->only('email', 'password');

          if (Auth::attempt($credentials)) {
               $request->session()->regenerate();
               $user = Auth::user();

               if ($user->role !== 'developer') {
                    Auth::logout();
                    return redirect()->route('dev.login.form')->with('error', 'Akses hanya untuk developer.');
               }

               return redirect()->route('developer.dashboard')->with('success', 'Anda berhasil login sebagai developer.');
          }

          return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
     }

     // =========== PROSES LOGOUT DEVELOPER ===========
     public function logoutDev(Request $request)
     {
          Auth::logout();
          $request->session()->invalidate();
          $request->session()->regenerateToken();
          return redirect()->route('dev.login.form');
     }

     // =========== FORM LOGIN USER ===========
     public function loginForm()
     {
          return view('auth.login');
     }

     // =========== PROSES LOGIN USER ===========
     public function processLogin(Request $request)
     {
          $credentials = $request->only('email', 'password');

          if (Auth::attempt($credentials)) {
               $request->session()->regenerate();
               $user = Auth::user();

               return match ($user->user_group) {
                    'owner' => redirect()->route('owner.dashboard'),
                    'manager' => redirect()->route('manager.dashboard'),
                    'operator' => redirect()->route('operator.dashboard'),
                    default => redirect()->route('login.form')->with('error', 'Role tidak dikenali.'),
               };
          }

          return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
     }

     // =========== PROSES LOGOUT USER ===========
     public function logout(Request $request)
     {
          Auth::logout();
          $request->session()->invalidate();
          $request->session()->regenerateToken();
          return redirect()->route('login.form');
     }
}
