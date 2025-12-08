<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
{
    // 1. Validasi (Tetap sama)
    $request->validate([
        'username' => 'required|unique:users',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed'
    ]);

    // 2. Simpan User (Tetap sama)
    // Note: Karena Anda pakai cara simpel tanpa tabel Role, pastikan 'role' => 'user'
    User::create([
        'username' => $request->username,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'user', 
        'status'   => 'active'
    ]);

    // 3. (HAPUS BAGIAN INI)
    // Auth::login($user); <--- Hapus atau jadikan komentar baris ini

    // 4. Redirect kembali ke halaman Login dengan Pesan Sukses
    // Kita kirimkan session 'success' agar bisa ditampilkan di view
    return redirect('/login')->with('success', 'Registrasi Berhasil! Silakan Login.');
}
}