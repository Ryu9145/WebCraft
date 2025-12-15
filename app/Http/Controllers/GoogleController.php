<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Carbon\Carbon; // Pastikan import Carbon untuk waktu

class GoogleController extends Controller
{
    // 1. Redirect ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle Callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek user berdasarkan Google ID
            $finduser = User::where('google_id', $googleUser->id)->first();

            if($finduser){
                // === USER LAMA (Sudah pernah login Google) ===
                
                // [FIX] Pastikan email_verified_at TERISI!
                // Walaupun dulu null, sekarang kita paksa isi karena login via Google valid.
                if ($finduser->email_verified_at == null) {
                    $finduser->update([
                        'email_verified_at' => Carbon::now()
                    ]);
                }

                Auth::login($finduser);
                
                // Redirect Admin ke Dashboard, User Biasa ke HOME
                if ($finduser->role == 'admin' || $finduser->role == 'superadmin') {
                    return redirect()->route('admin.dashboard');
                }
                
                // [SESUAI REQUEST] Langsung ke Halaman Utama
                return redirect()->route('home');

            } else {
                // === USER BARU ATAU USER MANUAL YANG BARU LINK GOOGLE ===
                
                // Cek apakah email sudah ada (User Manual mencoba login Google)
                $checkEmail = User::where('email', $googleUser->email)->first();

                if($checkEmail) {
                    // Jika email ada, update Google ID & Verifikasi Email
                    $checkEmail->update([
                        'google_id' => $googleUser->id,
                        'email_verified_at' => Carbon::now() // [FIX] Otomatis Verifikasi
                    ]);
                    
                    Auth::login($checkEmail);
                } else {
                    // Jika benar-benar User Baru
                    $newUser = User::create([
                        'username' => Str::slug($googleUser->name) . rand(100, 999),
                        'email' => $googleUser->email,
                        'google_id'=> $googleUser->id,
                        'password' => Hash::make(Str::random(16)), // Password acak aman
                        'role' => 'user',
                        'email_verified_at' => Carbon::now() // [FIX] Otomatis Verifikasi saat dibuat
                    ]);

                    Auth::login($newUser);
                }
                
                // [SESUAI REQUEST] Langsung ke Halaman Utama
                return redirect()->route('home');
            }

        } catch (\Exception $e) {
            // Jika batal/error, kembalikan ke login
            return redirect()->route('login')->with('login_error', 'Gagal login Google.');
        }
    }
}