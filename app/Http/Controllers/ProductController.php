<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth; // Wajib import Auth untuk ambil user login

class ProductController extends Controller
{
    // 1. TAMPILKAN SEMUA PRODUK (Halaman Admin)
    public function index()
    {
        $products = Produk::orderBy('id', 'desc')->get();
        return view('super_admin.products', compact('products'));
    }

    // 2. TAMPILKAN DETAIL PRODUK (Halaman Frontend/User)
    // Ini wajib ada karena route('product.detail') dipanggil di Keranjang & Home
// 2. TAMPILKAN DETAIL PRODUK
    public function detail($id)
    {
        // Ambil data dari database
        $data = Produk::findOrFail($id);
        
        // PENTING: Kita kirim dengan nama 'product' (Inggris)
        // agar cocok dengan view detail.blade.php
        return view('detail', ['product' => $data]); 
    }

    // 3. SIMPAN PRODUK BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kategori'    => 'required',
            'harga'       => 'required|numeric',
            'link_github' => 'required|url',
            'status'      => 'required',
            'gambar'      => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Logic Upload Gambar
        $namaGambar = 'default.jpg';
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $namaGambar = uniqid() . '.' . $ext;
            $file->move(public_path('assets/uploads'), $namaGambar);
        }

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'deskripsi'   => $request->deskripsi,
            'link_github' => $request->link_github,
            'status'      => $request->status,
            'gambar'      => $namaGambar,
            'is_featured' => 0,
            
            // PENTING: Simpan username admin yang mengupload
            'username'    => Auth::user()->username 
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    // 4. UPDATE PRODUK
    public function update(Request $request)
    {
        $request->validate([
            'id'          => 'required',
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $produk = Produk::findOrFail($request->id);
        
        // Kita gunakan only() agar data sensitif tidak tertimpa sembarangan
        $input = $request->only(['nama_produk', 'kategori', 'harga', 'deskripsi', 'link_github', 'status']);

        // Cek Upload Gambar Baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($produk->gambar != 'default.jpg' && File::exists(public_path('assets/uploads/' . $produk->gambar))) {
                File::delete(public_path('assets/uploads/' . $produk->gambar));
            }

            // Upload baru
            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $namaGambar = uniqid() . '.' . $ext;
            $file->move(public_path('assets/uploads'), $namaGambar);
            
            $input['gambar'] = $namaGambar;
        }

        $produk->update($input);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    // 5. HAPUS PRODUK
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar != 'default.jpg' && File::exists(public_path('assets/uploads/' . $produk->gambar))) {
            File::delete(public_path('assets/uploads/' . $produk->gambar));
        }

        $produk->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    // 6. QUICK ACTIONS
    public function updateStatus($id, $action)
    {
        $produk = Produk::findOrFail($id);

        if ($action == 'approve') {
            $produk->update(['status' => 'active']);
            $msg = 'Produk disetujui (Active).';
        } elseif ($action == 'reject') {
            $produk->update(['status' => 'rejected']);
            $msg = 'Produk ditolak (Rejected).';
        } elseif ($action == 'feature') {
            $produk->update(['is_featured' => !$produk->is_featured]);
            $msg = 'Status Featured diubah.';
        }

        return redirect()->back()->with('success', $msg ?? 'Status diubah');
    }
}