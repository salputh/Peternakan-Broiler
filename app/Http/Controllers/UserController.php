<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peternakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function showLoginForm()
    {
        // View: resources/views/auth/login.blade.php
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        $user = \App\Models\User::where('user_email', $credentials['user_email'])->first();

        if (!$user) {
            return back()->withErrors([
                'user_email' => 'Email tidak terdaftar.',
            ])->onlyInput('user_email');
        }

        if (!\Illuminate\Support\Facades\Hash::check($credentials['user_password'], $user->user_password)) {
            return back()->withErrors([
                'user_password' => 'Password salah.',
            ])->onlyInput('user_email');
        }

        \Illuminate\Support\Facades\Auth::login($user);
        $request->session()->regenerate();

        // Redirect berdasarkan role
        switch ($user->user_group) {
            case 'developer':
                return redirect()->route('dev.dashboard')->with('success', 'Login berhasil sebagai Developer.');
            case 'owner':
                $peternakan = Peternakan::find($user->peternakan_id);
                $nama_peternakan = str_replace(' ', '-', strtolower($peternakan->nama_peternakan));
                return redirect()->route('owner.kandang.index', [
                    'nama_peternakan' => $nama_peternakan,
                    'peternakan' => $user->peternakan_id
                ])->with('success', 'Login berhasil sebagai Owner.');
            case 'manager':
                return redirect()->route('manager.dashboard')->with('success', 'Login berhasil sebagai Manager.');
            case 'operator':
                return redirect()->route('operator.dashboard')->with('success', 'Login berhasil sebagai Operator.');
            default:
                return redirect()->route('dashboard')->with('success', 'Login berhasil.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    // ================= Login User =================
    public function index()
    {
        $users = User::with('peternakan')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $peternakans = Peternakan::all();
        return view('users.create', compact('peternakans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_nama' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,user_email',
            'user_no_hp' => 'required|string|max:20',
            'user_password' => 'required|string|min:6',
            'user_group' => 'required|in:developer,owner,manager,operator',
            'peternakan_id' => 'required|exists:peternakan,peternakan_id',
        ]);

        User::create([
            'user_nama' => $request->user_nama,
            'user_email' => $request->user_email,
            'user_no_hp' => $request->user_no_hp,
            'user_password' => Hash::make($request->user_password),
            'user_group' => $request->user_group,
            'user_foto' => $request->user_foto,
            'peternakan_id' => $request->peternakan_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $peternakans = Peternakan::all();
        return view('users.edit', compact('user', 'peternakans'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'user_nama' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,user_email,' . $user->user_id . ',user_id',
            'user_no_hp' => 'required|string|max:20',
            'user_group' => 'required|in:developer,owner,manager,operator',
            'peternakan_id' => 'required|exists:peternakan,peternakan_id',
        ]);

        $user->update([
            'user_nama' => $request->user_nama,
            'user_email' => $request->user_email,
            'user_no_hp' => $request->user_no_hp,
            'user_group' => $request->user_group,
            'user_foto' => $request->user_foto,
            'peternakan_id' => $request->peternakan_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
