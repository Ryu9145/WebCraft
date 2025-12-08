<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class FrontProductController extends Controller
{
    public function show($id)
    {
        // 1. Ambil Data Produk
        $product = Produk::findOrFail($id);

        // 2. Return View (Langsung ke detail.blade.php)
        return view('detail', compact('product'));
    }
}