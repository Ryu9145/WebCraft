<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan View "Lupa Password" (Desain Liquid Aurora Anda)
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // 2. Kirim Link Reset ke Email
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi Email
        $request->validate(['email' => 'required|email']);

        // Kirim Link (Laravel otomatis menangani token)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Cek Status Pengiriman
        if ($status === Password::RESET_LINK_SENT) {
            // Jika Berhasil: Kembali dengan pesan sukses (Liquid Aurora akan menampilkan alert hijau)
            return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
        }

        // Jika Gagal (Email tidak ditemukan, dll): Kembali dengan error
        return back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
    }
}