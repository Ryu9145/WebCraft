@extends('layouts.master') 

@section('content')

<style>
    /* 1. SETUP GAMBAR RASIO 16:9 */
    .product-image-wrapper {
        width: 100%;
        aspect-ratio: 16 / 9; /* KUNCI: Rasio 16:9 Otomatis */
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px; /* Sudut lebih membulat modern */
        overflow: hidden;
        border: 1px solid #eef2f6;
        position: relative;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Gambar mengisi penuh kotak tanpa gepeng */
        transition: transform 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.03); /* Zoom halus saat hover */
    }

    /* Typography & Spacing */
    .product-title { font-size: 1.8rem; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; line-height: 1.2; }
    .product-category { font-weight: 600; letter-spacing: 0.5px; font-size: 0.85rem; text-transform: uppercase; color: #3b82f6; }
    .product-price { font-size: 1.6rem; font-weight: 700; color: #0f172a; }
    
    .creator-box { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; transition: 0.2s; }
    .creator-box:hover { border-color: #3b82f6; }
    
    .description-text { font-size: 1rem; color: #64748b; line-height: 1.7; }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('home') }}" class="btn btn-white border btn-sm rounded-circle shadow-sm me-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left text-secondary"></i>
        </a>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium">Home</a></li>
                <li class="breadcrumb-item text-muted">{{ $product->kategori }}</li>
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ Str::limit($product->nama_produk, 30) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-5"> <div class="col-lg-7">
            <div class="product-image-wrapper shadow-sm">
                @php 
                    $imgPath = asset('assets/uploads/' . $product->gambar);
                    if(empty($product->gambar) || $product->gambar == 'default.jpg') {
                        $imgPath = 'https://placehold.co/800x450?text=No+Image'; // Placeholder 16:9
                    }
                @endphp
                
                <img src="{{ $imgPath }}" class="product-image" alt="{{ $product->nama_produk }}">
                
                <span class="position-absolute top-0 end-0 m-3 badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill fw-bold">
                    {{ $product->kategori }}
                </span>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="ps-lg-2">
                
                <div class="mb-2 product-category">{{ $product->kategori }}</div>

                <h1 class="product-title mb-3">{{ $product->nama_produk }}</h1>
                
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <span class="product-price me-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                    @if($product->is_featured)
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2">
                            <i class="fa-solid fa-star me-1"></i> Featured Product
                        </span>
                    @endif
                </div>

                <div class="d-flex align-items-center mb-4 creator-box w-100">
                    <img src="https://ui-avatars.com/api/?name={{ $product->username }}&background=random&size=40&bold=true" class="rounded-circle me-3" width="45" height="45">
                    <div>
                        <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px;">Creator / Seller</small>
                        <span class="fw-bold text-dark fs-6">{{ $product->username }}</span>
                    </div>
                    <div class="ms-auto">
                        <a href="#" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-muted">Lihat Profil</a>
                    </div>
                </div>

                <div class="mb-5">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi</h6>
                    <div class="description-text">
                        {{ $product->deskripsi }}
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <form action="#" method="POST"> @csrf
                        <button type="button" class="btn btn-primary btn-lg rounded-pill fw-bold w-100 py-3 shadow-sm shadow-primary-hover">
                            Beli Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </form>

                <button type="button" 
                            class="btn btn-outline-primary btn-lg rounded-pill fw-bold w-100 py-2 mt-2" 
                        onclick="addToCart('{{ $product->id }}', false)"
                        <i class="fa-solid fa-cart-plus me-2"></i> Masukkan Keranjang
                </button>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection