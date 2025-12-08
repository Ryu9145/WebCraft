<?php

namespace App\Http\Controllers; // Namespace Standar (Tanpa \SuperAdmin)

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Produk;

class SuperAdminController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Utama Super Admin
     */
    public function index()
    {
        // 1. Total Pendapatan (Status 'Selesai' atau 'Paid')
        $totalIncome = Order::whereIn('Status_Pesanan', ['Selesai', 'Paid'])->sum('Total_Harga');

        // 2. Total User (Hanya Role 'user')
        $totalUsers = User::where('role', 'user')->count();

        // 3. Total Produk
        $totalProducts = Produk::count();

        // 4. Pesanan Pending
        $pendingOrders = Order::where('Status_Pesanan', 'Pending')->count();

        // 5. 5 Transaksi Terakhir (Untuk tabel ringkasan)
        $recentOrders = Order::orderBy('id', 'desc')->take(5)->get();

        // Return ke View
        // Folder: resources/views/super_admin/
        // File: dashboard.blade.php
        return view('super_admin.dashboard', compact(
            'totalIncome', 
            'totalUsers', 
            'totalProducts', 
            'pendingOrders', 
            'recentOrders'
        ));
    }
}