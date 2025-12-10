<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        // 1. VALIDASI
        $request->validate([
            'username' => ['required', 'email', 'ends_with:gmail.com'],
            'password' => 'required',
        ], [
            'username.ends_with' => 'Format email tidak valid.',
            'username.email'     => 'Format email tidak valid.',
            'username.required'  => 'Email tidak boleh kosong.',
            'password.required'  => 'Password tidak boleh kosong.',
        ]);

        $throttleKey = 'login:' . Str::lower($request->username) . '|' . $request->ip();

        // 2. CEK BLOKIR
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('blocked_error', $seconds)->withInput(); 
        }

        // 3. MAPPING INPUT
        $credentials = [
            'email'    => $request->username, 
            'password' => $request->password
        ];

        // 4. PROSES LOGIN
        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            // =======================================================
            // PERBAIKAN: Panggil Auth::user()->role secara langsung
            // =======================================================
            
            // Cek Super Admin
            if (Auth::user()->role == 'superadmin') {
                return redirect()->route('super_admin.dashboard');
            } 
            
            // Cek Admin Biasa
            elseif (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } 
            
            // Cek User Biasa
            else {
                return redirect()->route('home');
            }
        }

        // 5. JIKA GAGAL
        RateLimiter::hit($throttleKey, 70);
        
        return back()->with('login_error', "Email atau Password salah.")->withInput();
    }

    public function logout(Request $request)
    {
        // 1. Proses Logout
        Auth::logout();
 
        // 2. Hapus Sesi (Keamanan)
        $request->session()->invalidate();
 
        // 3. Regenerasi Token CSRF (Keamanan)
        $request->session()->regenerateToken();
 
        // 4. Redirect ke Halaman Login dengan Pesan Sukses
        return redirect()->route('login');
    }
}