<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Produk;
use App\Models\User;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $username = $user->username;

        // A. Total Belanja (Status: Selesai atau Paid)
        $mySpending = Order::where('nama_pemesan', $username)
            ->whereIn('Status_Pesanan', ['Selesai', 'Paid'])
            ->sum('Total_Harga');

        // B. Total Transaksi
        $myOrders = Order::where('nama_pemesan', $username)->count();

        // C. Produk Saya (Jika user pernah upload produk)
        $myProducts = Produk::where('username', $username)->count();

        // D. Status Akun
        $accountStatus = $user->status;

        // E. Riwayat Pesanan (5 Terakhir)
        $recentOrders = Order::where('nama_pemesan', $username)
            ->orderBy('created_at', 'desc') // Asumsi pakai created_at / ID
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'user', 
            'mySpending', 
            'myOrders', 
            'myProducts', 
            'accountStatus', 
            'recentOrders'
        ));
    }

    public function upload()
    {
        $user = Auth::user();
        
        // Ambil produk milik user ini saja, urutkan dari terbaru
        $products = Produk::where('username', $user->username)
                          ->orderBy('id', 'desc')
                          ->get();

        return view('user.upload', compact('products'));
    }

    // 2. PROSES SIMPAN PRODUK
    public function store(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori'    => 'required',
            'harga'       => 'required|numeric',
            'link_github' => 'required|url',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $user = Auth::user();

        // B. Logic Upload Gambar (Sesuai kode legacy Anda)
        $namaGambar = 'default.jpg';
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $namaGambar = uniqid() . '.' . $ext;
            
            // Pindahkan ke public/assets/uploads
            $file->move(public_path('assets/uploads'), $namaGambar);
        }

        // C. Simpan ke Database
        Produk::create([
            'username'    => $user->username,
            'nama_produk' => $request->nama_produk,
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'deskripsi'   => $request->deskripsi,
            'link_github' => $request->link_github,
            'status'      => 'pending', // Default pending
            'gambar'      => $namaGambar,
            'is_featured' => 0
        ]);

        // D. Redirect dengan pesan sukses
        return redirect()->route('user.upload')->with('success', 'Produk berhasil diupload! Menunggu moderasi.');
    }
}

