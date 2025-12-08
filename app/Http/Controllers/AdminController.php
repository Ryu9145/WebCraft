<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\Produk;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Pengecekan Role Manual (Layer Keamanan Tambahan)
        // Admin dan Superadmin boleh masuk, User biasa ditendang
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
            return redirect('/dashboard'); // Lempar ke dashboard user
        }

        // 2. Logika Statistik (Sama seperti Super Admin)
        $totalIncome = Order::where('Status_Pesanan', 'Selesai')->sum('Total_Harga');
        $totalUsers = User::where('role', 'user')->count();
        $totalProducts = Produk::where('status', 'active')->count(); // Hanya produk aktif
        $pendingOrders = Order::where('Status_Pesanan', 'Pending')->count();
        $recentOrders = Order::orderBy('id', 'desc')->take(5)->get();

        // 3. Return View ke folder 'admin'
        return view('admin.dashboard', compact(
            'totalIncome', 
            'totalUsers', 
            'totalProducts', 
            'pendingOrders', 
            'recentOrders'
        ));
    }
}