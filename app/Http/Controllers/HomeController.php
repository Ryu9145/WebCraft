<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Logika Filter Kategori
        $kategoriTerpilih = null;
        $query = Produk::where('status', 'active');

        if ($request->has('kategori')) {
            $slug = $request->kategori;
            // Cari nama kategori berdasarkan slug
            $kategoriDb = Kategori::where('slug', $slug)->first();
            
            if ($kategoriDb) {
                $kategoriTerpilih = $kategoriDb->nama_kategori;
                // Filter produk berdasarkan nama kategori
                $query->where('kategori', $kategoriTerpilih);
            }
        }

        $products = $query->orderBy('id', 'desc')->get();
        $categories = Kategori::orderBy('urutan', 'asc')->get();

        // 2. Hitung Keranjang (Badge)
        $countCart = 0;
        if (Auth::check()) {
            $countCart = Keranjang::where('username', Auth::user()->username)->count();
        }

        return view('home', [
            'products' => $products,
            'categories' => $categories,
            'kategori_terpilih' => $kategoriTerpilih,
            'count_cart' => $countCart
        ]);
    }
}