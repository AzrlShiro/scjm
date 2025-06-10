<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Jika autentikasi berhasil
            return redirect()->intended('/homepage');
        }

        // Jika autentikasi gagal
        return redirect()->back()->withErrors([
            'email' => 'Email atau password salah'
        ])->withInput($request->only('email'));
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function registerAction(Request $request)
    {
        // Validasi input registrasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Auto login setelah registrasi berhasil
            Auth::login($user);

            // Redirect ke homepage atau dashboard
            return redirect('/homepage')->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])
                ->withInput($request->only('name', 'email'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
