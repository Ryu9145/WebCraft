<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang; // Pastikan Model Keranjang Ada
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. TAMPILKAN KERANJANG
    public function index()
    {
        $username = Auth::user()->username;
        
        // Join tabel keranjang & produk
        $cartItems = Keranjang::where('username', $username)
                        ->with('produk') // Asumsi relasi 'produk' sudah ada di model Keranjang
                        ->get();

        return view('cart', compact('cartItems'));
    }

    // 2. TAMBAH KE KERANJANG (VIA FORM BIASA)
    public function store($id)
    {
        $this->saveToCart($id);
        return redirect()->route('cart.index')->with('success', 'Produk masuk keranjang!');
    }

    // 3. TAMBAH KE KERANJANG (VIA AJAX) - Untuk Tombol di Detail & Home
    public function addToCartAjax($id)
    {
        $result = $this->saveToCart($id);
        
        // Hitung total item baru
        $newCount = Keranjang::where('username', Auth::user()->username)->count();

        return response()->json([
            'status'    => $result, // 'success' atau 'exist'
            'new_count' => $newCount
        ]);
    }

    // 4. HAPUS ITEM KERANJANG
    public function destroy($id)
    {
        $item = \App\Models\Keranjang::findOrFail($id);
        
        // Pastikan user menghapus keranjangnya sendiri (Keamanan)
        if ($item->username == \Illuminate\Support\Facades\Auth::user()->username) {
            $item->delete();
            return redirect()->back()->with('success', 'Item berhasil dihapus.');
        }
        
        return redirect()->back()->with('error', 'Gagal menghapus item.');
    }

    // --- FUNGSI PRIVAT: LOGIKA SIMPAN ---
    private function saveToCart($produkId)
    {
        $username = Auth::user()->username;

        // Cek apakah produk sudah ada di keranjang user ini?
        $cek = Keranjang::where('username', $username)
                        ->where('produk_id', $produkId)
                        ->first();

        if ($cek) {
            return 'exist'; // Sudah ada, jangan duplikat
        }

        Keranjang::create([
            'username'  => $username,
            'produk_id' => $produkId
        ]);

        return 'success';
    }
}   