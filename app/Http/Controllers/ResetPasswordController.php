<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Verified; // [TAMBAHAN] Import Event Verified

class ResetPasswordController extends Controller
{
    // 1. Tampilkan View "Reset Password" (Form Password Baru)
    public function showResetForm(Request $request)
    {
        return view('auth.passwords.reset', [
            'token' => $request->route('token'),
            'email' => $request->email
        ]);
    }

    // 2. Proses Simpan Password Baru
    public function reset(Request $request)
    {
        // Validasi Input
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6', // Pastikan konfirmasi password cocok
        ]);

        // Proses Reset Password Built-in Laravel
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update Password Baru
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                // ============================================================
                // [LOGIKA BARU] AUTO-VERIFY EMAIL
                // ============================================================
                // Jika user belum verifikasi (misal user Google lama atau Manual),
                // Kita anggap verifikasi sukses karena mereka punya akses ke link email ini.
                if ($user->email_verified_at === null) {
                    $user->forceFill([
                        'email_verified_at' => now()
                    ]);
                    // (Opsional) Beritahu sistem bahwa user baru saja diverifikasi
                    event(new Verified($user));
                }
                // ============================================================

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Jika Berhasil
        if ($status === Password::PASSWORD_RESET) {
            // Arahkan ke Login dengan Notifikasi Sukses + Info Verifikasi
            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah! Akun Anda kini terverifikasi. Silakan login.');
        }

        // Jika Gagal (Token Expired / Email Salah)
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Token reset password tidak valid atau kedaluwarsa.']);
    }
}