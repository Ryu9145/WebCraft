<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// Import Library Midtrans
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    // 1. TAMPILKAN HALAMAN CHECKOUT
    public function process(Request $request)
    {
        // Validasi
        if (!$request->has('selected_items') || empty($request->selected_items)) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk!');
        }

        // Ambil data keranjang
        $ids = $request->selected_items;
        $items = Keranjang::whereIn('id', $ids)
                    ->where('username', Auth::user()->username)
                    ->with('produk')
                    ->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'Produk tidak valid.');
        }

        // Hitung Total
        $totalHarga = 0;
        foreach ($items as $item) {
            $totalHarga += $item->produk->harga;
        }

        return view('checkout', compact('items', 'totalHarga'));
    }

    // 2. PROSES SIMPAN ORDER & GET TOKEN MIDTRANS
    public function store(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();
        
        // Ambil Item dari Keranjang
        $cartIds = explode(',', $request->cart_ids); 
        $items = Keranjang::whereIn('id', $cartIds)->with('produk')->get();

        // Hitung Total & Siapkan Nama Produk
        $totalTagihan = 0;
        $namaProdukGabungan = [];
        
        foreach($items as $item){
            $totalTagihan += $item->produk->harga;
            $namaProdukGabungan[] = $item->produk->nama_produk;
        }

        // Buat Kode Order Unik (Wajib String untuk Midtrans)
        // Format: ORD-TIMESTAMP-RANDOM (Contoh: ORD-1738493-X7Z)
        $kodeOrder = 'ORD-' . time() . '-' . Str::upper(Str::random(3));

        // Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $kodeOrder,
                'gross_amount' => (int) $totalTagihan,
            ],
            'customer_details' => [
                'first_name' => $user->username,
                'email' => $user->email ?? 'user@example.com', // Fallback email
            ],
        ];

        // Minta Snap Token
        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Midtrans Error: ' . $e->getMessage());
        }

        // Simpan ke Database
        Order::create([
            'kode_order'      => $kodeOrder,
            'nama_pemesan'    => $user->username,
            'Nama_Produk'     => implode(', ', $namaProdukGabungan),
            'Total_Harga'     => $totalTagihan,
            'Tanggal_Pesan'   => now(),
            'snap_token'      => $snapToken,
            
            // GUNAKAN KOLOM YANG ANDA PUNYA
            'Status_Pesanan'  => 'Pending' 
        ]);

        // Hapus Keranjang (Karena sudah masuk order)
        Keranjang::destroy($cartIds);

        // Redirect ke Halaman Bayar
        return redirect()->route('payment.show', ['kode_order' => $kodeOrder]);
    }

    // 3. TAMPILKAN HALAMAN BAYAR
    public function showPayment($kode_order)
    {
        $order = Order::where('kode_order', $kode_order)->firstOrFail();
        
        if(empty($order->snap_token)){
            return redirect()->route('home')->with('error', 'Token pembayaran hilang.');
        }

        return view('payment', compact('order'));
    }
    
    // 4. SUKSES BAYAR (Redirect dari Midtrans)
    public function success(Request $request)
    {
        $kodeOrder = $request->order_id; // Midtrans mengirim parameter order_id di URL
        
        $order = Order::where('kode_order', $kodeOrder)->first();
        
        if($order) {
            // Update Status Pesanan menjadi "Dibayar"
            $order->update([
                'Status_Pesanan' => 'Dibayar' 
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Pembayaran Berhasil! Terima kasih.');
    }
}