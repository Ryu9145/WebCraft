<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // <--- 1. TAMBAHKAN INI (Penting!)

class ProfileController extends Controller
{
    // 1. Menampilkan View
    public function index()
    {
        return view('profile'); 
    }

    // 2. Logic Update Foto & Username
    public function update(Request $request)
    {
        // 2. TAMBAHKAN KOMENTAR INI DI ATAS Auth::user()
        // Ini memberitahu editor: "Hei, $user ini adalah Model User lho, bukan user biasa."
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // LOGIKA A: JIKA USER KLIK "SIMPAN FOTO"
        if ($request->tipe == 'foto') {
            
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Hapus foto lama
            if ($user->avatar && file_exists(public_path('assets/uploads/' . $user->avatar))) {
                unlink(public_path('assets/uploads/' . $user->avatar));
            }

            // Upload baru
            $file = $request->file('avatar');
            $filename = time() . '_avatar_' . $file->getClientOriginalName();
            $file->move(public_path('assets/uploads'), $filename);
            
            $user->avatar = $filename;
            
            // Sekarang garis merah di sini pasti hilang
            $user->save(); 

            return back()->with('success', 'Foto profil berhasil diganti!');
        } 
        
        // LOGIKA B: JIKA USER KLIK "SIMPAN USERNAME"
        elseif ($request->tipe == 'profile') {
            
            $request->validate([
                'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            ]);

            $user->username = $request->username;
            $user->save(); // Ini juga akan bersih dari error
                
            return back()->with('success', 'Username berhasil diperbarui!');
        }

        return back()->with('error', 'Tidak ada perubahan yang disimpan.');
    }

    // 3. Logic Ganti Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diganti!');
    }
}