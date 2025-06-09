<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class DeveloperAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.dev-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_email'    => 'required|email',
            'user_password' => 'required|string',
        ]);

        $creds = $request->only('user_email', 'user_password');

        // Cek user dengan user_group developer
        $user = User::where('user_email', $request->user_email)
            ->where('user_group', 'developer')
            ->first();

        if ($user && Hash::check($request->user_password, $user->user_password)) {
            // ← pakai guard('developer') biar sessionnya tersimpan di guard itu
            Auth::login($user);
            return redirect()->route('developer.dashboard.peternakan');
        }

        return back()->withErrors(['user_email' => 'Email/password salah atau bukan developer']);
    }

    public function logout()
    {
        Auth::guard('developer')->logout();
        return redirect()->route('developer.login');
    }
}
