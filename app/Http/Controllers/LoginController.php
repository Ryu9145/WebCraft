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
            'username.ends_with' => 'Format email tidak valid (harus @gmail.com).',
            'username.email'     => 'Format email tidak valid.',
            'username.required'  => 'Email tidak boleh kosong.',
            'password.required'  => 'Password tidak boleh kosong.',
        ]);

        $throttleKey = 'login:' . Str::lower($request->username) . '|' . $request->ip();

        // 2. CEK BLOKIR (Rate Limiting)
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
            
            // =======================================================
            // [LOGIKA BARU] CEK VERIFIKASI EMAIL
            // =======================================================
            $user = Auth::user();

            if ($user->email_verified_at === null) {
                // Jika belum diverifikasi:
                Auth::logout(); // 1. Paksa Logout
                
                $request->session()->invalidate(); // 2. Hapus sesi
                $request->session()->regenerateToken();

                // 3. Tendang kembali ke login dengan pesan error
                return back()
                    ->with('login_error', 'Akun Anda belum diverifikasi. Silakan cek inbox email Anda.')
                    ->withInput();
            }
            // =======================================================

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            // Cek Super Admin
            if ($user->role == 'superadmin') {
                return redirect()->route('super_admin.dashboard');
            } 
            
            // Cek Admin Biasa
            elseif ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } 
            
            // Cek User Biasa
            else {
                return redirect()->route('home');
            }
        }

        // 5. JIKA GAGAL (Password Salah)
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