<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    // 1. TAMPILKAN DAFTAR USER (HANYA CUSTOMER)
    public function index()
    {
        // Filter: Hanya ambil user dengan role 'user'
        $users = User::where('role', 'user')->orderBy('id', 'desc')->get();
        
        return view('admin.users', compact('users'));
    }

    // 2. UPDATE STATUS (VERIFIKASI / SUSPEND)
    public function updateStatus($id, $status)
    {
        // Proteksi: Pastikan yang diedit adalah 'user' biasa
        // Agar Admin tidak bisa memblokir sesama Admin atau Super Admin
        $user = User::where('id', $id)->where('role', 'user')->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan atau Anda tidak memiliki akses!');
        }

        // Validasi input status agar sesuai enum database
        if (in_array($status, ['active', 'suspended', 'pending'])) {
            $user->update(['status' => $status]);
            $msg = 'Status user berhasil diperbarui menjadi ' . ucfirst($status);
            return redirect()->back()->with('success', $msg);
        }

        return redirect()->back()->with('error', 'Status tidak valid!');
    }
}