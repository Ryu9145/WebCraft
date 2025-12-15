<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Order;
use App\Models\Produk; // <--- UBAH DI SINI (Sesuai nama file Anda)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// Import Library Midtrans
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    // ==========================================================
    // 1. HALAMAN CHECKOUT (Review Pesanan Sebelum Bayar)
    // ==========================================================
    public function process(Request $request)
    {
        $user = Auth::user();
        $items = collect(); 
        $totalHarga = 0;
        $isDirect = false;

        // --- SKENARIO A: DARI KERANJANG ---
        if ($request->has('selected_items') && !empty($request->selected_items)) {
            $ids = $request->selected_items;
            
            // Ambil data dari tabel keranjang
            $items = Keranjang::whereIn('id', $ids)
                        ->where('username', $user->username) 
                        ->with('produk') // Pastikan di model Keranjang nama fungsinya public function produk()
                        ->get();
            
            if ($items->isEmpty()) {
                return back()->with('error', 'Produk tidak valid.');
            }
        } 
        
        // --- SKENARIO B: BELI LANGSUNG (DARI DETAIL PRODUK) ---
        elseif ($request->has('product_id')) {
            // UBAH DI SINI: Pakai Model Produk
            $product = Produk::find($request->product_id);
            $qty = $request->quantity ?? 1;

            if (!$product) {
                return back()->with('error', 'Produk tidak ditemukan.');
            }

            // Buat objek palsu agar view tidak error
            $fakeItem = new \stdClass();
            $fakeItem->id = null; 
            $fakeItem->produk = $product;
            $fakeItem->jumlah = $qty;
            
            $items->push($fakeItem);
            $isDirect = true; 
        } 
        
        else {
            return back()->with('error', 'Pilih minimal satu produk atau klik Beli Sekarang.');
        }

        // Hitung Total
        foreach ($items as $item) {
            $qty = $item->jumlah ?? 1; 
            // Akses harga lewat relasi produk
            $totalHarga += ($item->produk->harga * $qty);
        }

        return view('checkout', compact('items', 'totalHarga', 'isDirect'));
    }

    // ==========================================================
    // 2. PROSES SIMPAN ORDER & REQUEST MIDTRANS
    // ==========================================================
    public function store(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY'); 
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();
        $namaProdukGabungan = [];
        $totalTagihan = 0;
        
        // A. CEK SUMBER DATA
        if ($request->is_direct == 1) {
            // --- JIKA BELI LANGSUNG ---
            $productId = $request->product_id; 
            $qty = $request->quantity;
            
            // UBAH DI SINI: Pakai Model Produk
            $product = Produk::find($productId);
            $totalTagihan = $product->harga * $qty;
            $namaProdukGabungan[] = $product->nama_produk . " (x$qty)";

        } else {
            // --- JIKA DARI KERANJANG ---
            $cartIds = explode(',', $request->cart_ids);
            $items = Keranjang::whereIn('id', $cartIds)->with('produk')->get();

            if ($items->isEmpty()) {
                return back()->with('error', 'Data keranjang tidak ditemukan.');
            }

            foreach($items as $item){
                $qty = $item->jumlah ?? 1;
                $totalTagihan += ($item->produk->harga * $qty);
                $namaProdukGabungan[] = $item->produk->nama_produk . " (x$qty)";
            }
        }

        $kodeOrder = 'TRX-' . time() . '-' . mt_rand(100, 999);

        // Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $kodeOrder,
                'gross_amount' => (int) $totalTagihan,
            ],
            'customer_details' => [
                'first_name' => $user->username,
                'email' => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan Order
            Order::create([
                'kode_order'      => $kodeOrder,
                'user_id'         => $user->id, 
                'nama_pemesan'    => $user->username,
                'Nama_Produk'     => implode(', ', $namaProdukGabungan),
                'Total_Harga'     => $totalTagihan,
                'Tanggal_Pesan'   => now(),
                'Status_Pesanan'  => 'Pending',
                'snap_token'      => $snapToken,
            ]);

            // Hapus Keranjang jika bukan beli langsung
            if ($request->is_direct != 1 && isset($cartIds)) {
                Keranjang::destroy($cartIds);
            }

            return redirect()->route('payment.show', ['kode_order' => $kodeOrder]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // 3. HALAMAN PEMBAYARAN
    // ==========================================================
    public function showPayment($kode_order)
    {
        $order = Order::where('kode_order', $kode_order)->firstOrFail();
        
        if ($order->nama_pemesan !== Auth::user()->username) {
            abort(403, 'Akses ditolak');
        }

        return view('payment', compact('order'));
    }
}