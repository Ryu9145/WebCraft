<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    // 1. TAMPILKAN ORDER
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    // 2. UPDATE STATUS (DENGAN PROTEKSI)
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'             => 'required',
            'status_pesanan' => 'required'
        ]);

        // --- PROTEKSI KHUSUS ADMIN ---
        // Admin tidak boleh melakukan Refund
        if ($request->status_pesanan == 'Refunded') {
            return redirect()->back()->with('error', 'Akses Ditolak! Admin tidak memiliki izin untuk melakukan Refund.');
        }

        $order = Order::findOrFail($request->id);
        
        $order->update([
            'Status_Pesanan' => $request->status_pesanan
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
    
    // Note: Admin biasanya tidak diberi akses Hapus (Delete), jadi kita tidak buat fungsi destroy.
}