<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. TAMPILKAN HALAMAN
    public function index()
    {
        // Ambil semua user urut dari yang terbaru
        $users = User::orderBy('id', 'desc')->get();
        
        // Arahkan ke file view: resources/views/super_admin/users.blade.php
        return view('super_admin.users', compact('users'));
    }

    // 2. SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required',
            'status'   => 'required'
        ]);

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    // 3. UPDATE USER
    public function update(Request $request)
    {
        $request->validate([
            'id'       => 'required',
            'username' => 'required',
            'role'     => 'required',
            'status'   => 'required'
        ]);

        $user = User::findOrFail($request->id);

        $dataToUpdate = [
            'username' => $request->username,
            'role'     => $request->role,
            'status'   => $request->status
        ];

        // Update password HANYA JIKA diisi
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    // 4. HAPUS USER
    public function destroy($id)
    {
        // Jangan biarkan admin menghapus dirinya sendiri
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        User::destroy($id);
        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}