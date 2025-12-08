@extends('layouts.master')

@section('content')

<div class="container py-5">
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('cart.index') }}" class="btn btn-white border rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left text-secondary"></i>
        </a>
        <h3 class="mb-0 fw-bold">Konfirmasi Pembayaran</h3>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Item yang Dibeli</h6>
                </div>
                <div class="card-body">
                    @foreach($items as $item)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('assets/uploads/' . ($item->produk->gambar ?? 'default.jpg')) }}" class="rounded-3 border" width="60" height="60" style="object-fit: cover;">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-0 fw-bold">{{ $item->produk->nama_produk }}</h6>
                                <small class="text-muted">{{ $item->produk->kategori }}</small>
                            </div>
                            <div class="fw-bold text-primary">
                                Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                    
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Tagihan</span>
                        <span class="fw-bold fs-4 text-primary">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-credit-card me-2"></i>Transfer Pembayaran</h5>
                    <p class="mb-2 opacity-75">Silakan transfer sesuai total tagihan ke rekening berikut:</p>
                    <div class="d-flex align-items-center bg-white bg-opacity-10 p-3 rounded-3 mb-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" width="60" class="bg-white p-1 rounded me-3">
                        <div>
                            <div class="fs-5 fw-bold">123-456-7890</div>
                            <small>a.n. ThemeMarket Official</small>
                        </div>
                        <button class="btn btn-sm btn-light ms-auto" onclick="navigator.clipboard.writeText('1234567890'); alert('No Rekening Disalin!')">Salin</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Metode Pembayaran</h5>
                    
                    <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                        <i class="fa-solid fa-shield-halved fs-4 me-3"></i>
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

                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('preview').classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection