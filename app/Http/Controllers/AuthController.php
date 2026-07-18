<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $isFormAdmin = $request->has('is_admin_login');

        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();

            if ($isFormAdmin && $user->role !== 'admin') {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Akun ini bukan Admin.']);
            }

            if (!$isFormAdmin && $user->role === 'admin') {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Admin hanya boleh login melalui tombol Login Admin.']);
            }

            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Selamat datang kembali!');
        }

        return redirect()->back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'relawan',
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Anda telah masuk sistem.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}