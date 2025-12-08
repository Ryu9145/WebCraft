<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Pastikan nama function ini adalah 'register' (huruf kecil semua)
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed' 
        ]);

        // 2. Cari ID untuk Role 'user'
        $roleUser = Role::where('nama_role', 'user')->first();

        // Jaga-jaga jika seeder belum jalan
        if (!$roleUser) {
            return back()->withErrors(['email' => 'Error: Role User belum dibuat di database.']);
        }

        // 3. Simpan ke Database
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $roleUser->id,
            'status'   => 'active'
        ]);

        // 4. Auto Login
        Auth::login($user);

        // 5. Redirect ke Dashboard Dummy
        return redirect('/dashboard');
    }
}