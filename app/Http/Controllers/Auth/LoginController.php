<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// === TAMBAHKAN 2 BARIS INI DI PALING ATAS ===
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function index()
    {
        // Pastikan nama view sesuai dengan file Anda
        // Jika file ada di resources/views/login.blade.php -> gunakan 'login'
        // Jika file ada di resources/views/auth/login.blade.php -> gunakan 'auth.login'
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. KUNCI UNIK (Rate Limiter Key)
        // Kita gabungkan Username + IP Address user
        // Contoh hasil: "login:budi|127.0.0.1"
        $throttleKey = 'login:' . Str::lower($request->username) . '|' . $request->ip();

        // 3. CEK APAKAH USER SEDANG DIKUNCI?
        // Cek apakah gagal lebih dari 5 kali?
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan. Tunggu $seconds detik lagi.");
        }

        // 4. PROSES LOGIN
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // A. Login Sukses -> Hapus catatan gagal (Reset)
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();
            
            // Redirect sesuai Role
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        // 5. JIKA LOGIN GAGAL
        
        // A. Hitung Mundur (Tambah 1 kegagalan)
        // Kunci selama 60 detik jika sudah mencapai batas
        RateLimiter::hit($throttleKey, 60);

        // B. Hitung sisa nyawa
        $sisa = RateLimiter::retriesLeft($throttleKey, 5);

        return back()->with('error', "Password salah! Sisa percobaan: $sisa kali.");
    }
}