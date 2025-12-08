<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. TAMPILKAN SEMUA ORDER
    public function index()
    {
        // Urutkan dari yang terbaru (ID DESC)
        $orders = Order::orderBy('id', 'desc')->get();
        return view('super_admin.orders', compact('orders'));
    }

    // 2. UPDATE STATUS PESANAN
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'             => 'required',
            'status_pesanan' => 'required'
        ]);

        $order = Order::findOrFail($request->id);
        
        $order->update([
            'Status_Pesanan' => $request->status_pesanan
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // 3. HAPUS PESANAN
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Hapus file bukti bayar fisik jika ada (Opsional, agar server tidak penuh)
        if ($order->bukti_bayar && file_exists(public_path('assets/uploads/' . $order->bukti_bayar))) {
            unlink(public_path('assets/uploads/' . $order->bukti_bayar));
        }

        $order->delete();

        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus!');
    }
}