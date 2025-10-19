<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function redirectRoot()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = strtoupper(Auth::user()->role);

        switch ($role) {
            case 'GURU': 
                return redirect()->route('dashboard.guru');
            case 'TU': 
                return redirect()->route('dashboard.tu');
            case 'KEPSEK': 
                return redirect()->route('dashboard.kepsek');
            case 'ADMIN': 
                return redirect()->route('dashboard.admin');
            default:
                abort(403, 'Role tidak dikenali');
        }
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $req)
    {
        $req->validate([
            'username' => 'required|unique:pengguna,username',
            'nama' => 'required',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,guru,kepsek,tu'
        ]);

        $user = Pengguna::create([
            'username' => $req->username,
            'nama' => $req->nama,
            'password' => Hash::make($req->password),
            'role' => strtolower($req->role),
            'no_telp' => $req->no_telp,
            'nip' => $req->nip
        ]);

        Auth::login($user);
        $req->session()->regenerate();

        session([
            'id_pengguna' => $user->id_pengguna ?? $user->id,
            'name' => $user->nama,
            'role' => strtoupper($user->role)
        ]);

        return redirect()->route('dashboard.' . strtolower($user->role));
    }

    public function login(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($req->only('username', 'password'))) {
            $req->session()->regenerate();

            $user = Auth::user();
            session([
                'id_pengguna' => $user->id_pengguna ?? $user->id,
                'name' => $user->nama ?? ($user->name ?? ''),
                'role' => strtoupper($user->role ?? '')
            ]);

            return redirect()->route('dashboard.' . strtolower($user->role));
        }

        return back()->with('error', 'Username atau password salah!');
    }
}
