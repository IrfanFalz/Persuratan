<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function redirectRoot()
    {
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $users = [
            'ktu' => ['password' => 'ktu123', 'role' => 'KTU', 'name' => 'Budi Santoso'],
            'tu' => ['password' => 'tu123', 'role' => 'TU', 'name' => 'Siti Aminah'],
            'kepsek' => ['password' => 'kepsek123', 'role' => 'KEPSEK', 'name' => 'Dr. Ahmad Wijaya'],
            'guru1' => ['password' => 'guru123', 'role' => 'GURU', 'name' => 'Maya Sari'],
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
                    case 'KTU': return redirect()->route('dashboard.ktu');
                    case 'TU': return redirect()->route('dashboard.tu');
                    case 'KEPSEK': return redirect()->route('dashboard.kepsek');
                }
            } else {
                return back()->with('error', 'Username atau password salah!');
            }
        }

        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
