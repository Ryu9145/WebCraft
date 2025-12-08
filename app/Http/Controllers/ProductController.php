<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // 1. TAMPILKAN SEMUA PRODUK
    public function index()
    {
        $products = Produk::orderBy('id', 'desc')->get();
        return view('super_admin.products', compact('products'));
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
            'gambar'      => 'image|mimes:jpeg,png,jpg|max:2048' // Max 2MB
        ]);

        // Logic Upload Gambar
        $namaGambar = 'default.jpg';
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $namaGambar = uniqid() . '.' . $ext;
            
            // Simpan ke public/assets/uploads
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
            'is_featured' => 0 // Default tidak featured
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

        // Cek jika ada upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika bukan default
            if ($produk->gambar != 'default.jpg' && File::exists(public_path('assets/uploads/' . $produk->gambar))) {
                File::delete(public_path('assets/uploads/' . $produk->gambar));
            }

            // Upload gambar baru
            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $namaGambar = uniqid() . '.' . $ext;
            $file->move(public_path('assets/uploads'), $namaGambar);
            
            $input['gambar'] = $namaGambar;
        }

        $produk->update($input);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    // 4. HAPUS PRODUK
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus file gambar fisik
        if ($produk->gambar != 'default.jpg' && File::exists(public_path('assets/uploads/' . $produk->gambar))) {
            File::delete(public_path('assets/uploads/' . $produk->gambar));
        }

        $produk->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    // 5. QUICK ACTIONS (Approve / Reject / Feature)
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
            // Toggle Featured (0 jadi 1, 1 jadi 0)
            $produk->update(['is_featured' => !$produk->is_featured]);
            $msg = 'Status Featured diubah.';
        }

        return redirect()->back()->with('success', $msg);
    }
}