<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori; // Pastikan Model Kategori sudah ada
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // 1. TAMPILKAN SEMUA KATEGORI
    public function index()
    {
        // Urutkan berdasarkan kolom 'urutan' ASC (kecil ke besar)
        $categories = Kategori::orderBy('urutan', 'asc')->get();
        return view('super_admin.categories', compact('categories'));
    }

    // 2. SIMPAN KATEGORI BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:tb_kategori,nama_kategori',
            'urutan'        => 'required|integer'
        ]);

        // Buat slug otomatis (Contoh: "Web Design" -> "web-design")
        $slug = Str::slug($request->nama_kategori);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'slug'          => $slug,
            'urutan'        => $request->urutan
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 3. UPDATE KATEGORI
    public function update(Request $request)
    {
        $request->validate([
            'id'            => 'required',
            'nama_kategori' => 'required',
            'urutan'        => 'required|integer'
        ]);

        $kategori = Kategori::findOrFail($request->id);
        
        // Update slug jika nama berubah
        $slug = Str::slug($request->nama_kategori);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug'          => $slug,
            'urutan'        => $request->urutan
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. HAPUS KATEGORI
    public function destroy($id)
    {
        Kategori::destroy($id);
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}