<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        // 2. Simpan User
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', 
            'status'   => 'active'
        ]);

        // 3. Kirim Email Verifikasi
        event(new Registered($user));

        // 4. [KEMBALIKAN] Login Otomatis
        // Kita login-kan agar mereka bisa mengakses halaman 'verification.notice'
        Auth::login($user); 

        // 5. [UBAH] Arahkan ke halaman "verification.notice" (Verify Blade)
        return redirect()->route('verification.notice');
    }
}