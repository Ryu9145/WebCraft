<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\File;

class AdminProductController extends Controller
{
    // 1. TAMPILKAN PRODUK
    public function index()
    {
        $products = Produk::orderBy('id', 'desc')->get();
        return view('admin.products', compact('products'));
    }

    // 2. SIMPAN PRODUK BARU
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
            'is_featured' => 0 // Admin tidak bisa set featured saat create
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    // 3. UPDATE PRODUK
    public function update(Request $request)
    {
        $request->validate([
            'id'          => 'required',
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $produk = Produk::findOrFail($request->id);
        $input = $request->all();

        // Admin tidak boleh mengubah 'is_featured' lewat form ini
        // (sudah diproteksi karena tidak ada di $fillable atau tidak dikirim form)

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika bukan default
            if ($produk->gambar != 'default.jpg' && File::exists(public_path('assets/uploads/' . $produk->gambar))) {
                File::delete(public_path('assets/uploads/' . $produk->gambar));
            }

            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $namaGambar = uniqid() . '.' . $ext;
            $file->move(public_path('assets/uploads'), $namaGambar);
            
            $input['gambar'] = $namaGambar;
        }

        $produk->update($input);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    // NOTE: Admin TIDAK DIBERI AKSES DELETE (destroy)
    // Sesuai praktik keamanan data, admin hanya boleh menonaktifkan status.
}