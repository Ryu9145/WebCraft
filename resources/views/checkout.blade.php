@extends('layouts.master')

@section('content')

<div class="container py-5" style="margin-top: 60px;">
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('cart.index') }}" class="btn btn-white border rounded-circle shadow-sm me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left text-secondary"></i>
        </a>
        <h3 class="mb-0 fw-bold">Konfirmasi Pembayaran</h3>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold">Item yang Dibeli</h6>
                </div>
                <div class="card-body">
                    @foreach($items as $item)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('assets/uploads/' . ($item->produk->gambar ?? 'default.jpg')) }}" 
                                 class="rounded-3 border" width="70" height="70" style="object-fit: cover;">
                            
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $item->produk->nama_produk }}</h6>
                                <small class="text-muted">{{ $item->produk->kategori }}</small>
                            </div>
                            
                            <div class="fw-bold text-primary">
                                Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted">Total Tagihan</span>
                        <span class="fw-bold fs-3 text-dark">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px; z-index: 1;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Metode Pembayaran</h5>
                    
                    <div class="alert alert-info border-0 d-flex align-items-start mb-4">
                        <i class="fa-solid fa-shield-halved fs-4 me-3 mt-1"></i>
                        <div class="small">
                            Pembayaran aman & otomatis menggunakan <strong>Midtrans Payment Gateway</strong>.
                        </div>
                    </div>

                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_ids" value="{{ $items->pluck('id')->implode(',') }}">

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                            Lanjut ke Pembayaran <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted"><i class="fa-solid fa-lock me-1"></i> Transaksi Terenkripsi SSL</small>
                    </div>

                </div>
            </div>
        </div>
        </div> </div>

@endsection