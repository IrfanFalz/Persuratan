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

        $role = Auth::user()->role;
        switch ($role) {
            case 'guru': return redirect()->route('dashboard.guru');
            case 'tu': return redirect()->route('dashboard.tu');
            case 'kepsek': return redirect()->route('dashboard.kepsek');
            case 'admin': return redirect()->route('dashboard.admin');
        }

        return redirect()->route('login');
    }

    public function showLoginForm()
    {
<<<<<<< Updated upstream
        $users = [
            //'ktu' => ['password' => 'ktu123', 'role' => 'KTU', 'name' => 'Budi Santoso'],
            '0111' => ['password' => 'tu123', 'role' => 'TU', 'name' => 'Siti Aminah'],
            '0222' => ['password' => 'kepsek123', 'role' => 'KEPSEK', 'name' => 'Dr. Ahmad Wijaya'],
            '0333' => ['password' => 'guru123', 'role' => 'GURU', 'name' => 'Maya Sari'],
            '0444' => ['password' => 'admin123', 'role' => 'ADMIN', 'name' => 'Administrator'],
        ];

        if ($request->isMethod('post')) {
            $username = $request->input('username');
            $password = $request->input('password');

            if (isset($users[$username]) && $users[$username]['password'] === $password) {
                session([
                    'user' => $username,
                    'role' => $users[$username]['role'],
                    'name' => $users[$username]['name'],
                ]);

                switch ($users[$username]['role']) {
                    case 'GURU': return redirect()->route('dashboard.guru');
                   // case 'KTU': return redirect()->route('dashboard.ktu');
                    case 'TU': return redirect()->route('dashboard.tu');
                    case 'KEPSEK': return redirect()->route('dashboard.kepsek');
                    case 'ADMIN': return redirect()->route('dashboard.admin');
                }
            } else {
                return back()->with('error', 'Username atau password salah!');
            }
        }

=======
>>>>>>> Stashed changes
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
