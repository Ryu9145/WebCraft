@extends('layouts.master')

@section('content')

<header class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @guest
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 mb-4 px-3 py-2 rounded-pill fw-bold backdrop-blur">
                    🚀 Marketplace Template Terlengkap
                </span>
                @endguest

                <h1 class="display-4 fw-bold mb-3">Solusi Cepat Untuk<br>Project Website Anda</h1>
                <p class="lead mb-4 text-light opacity-75">Hemat waktu coding dengan ribuan template website premium. Mulai dari Landing Page, Dashboard, hingga E-Commerce.</p>
                
            <div class="d-flex gap-3 justify-content-center">
                <a href="#katalog" class="btn btn-primary rounded-pill px-5 py-3 fw-bold">Lihat Katalog</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light rounded-pill px-4 py-3 fw-bold">
                        Dashboard Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 py-3 fw-bold">
                        Mulai Jualan
                    </a>
                @endauth
            </div>
            </div>
        </div>
    </div>
</header>

<section class="container mb-5">
    <div class="row g-3 justify-content-center">
        @foreach($categories as $kat)
            @php
                // Logika Icon berdasarkan Slug (Sama seperti kode asli Anda)
                $slug_lower = strtolower($kat->slug);
                $icon_class = 'fa-solid fa-layer-group'; // Default icon

                if (strpos($slug_lower, 'portofolio') !== false) $icon_class = 'fa-solid fa-briefcase';
                elseif (strpos($slug_lower, 'admin') !== false) $icon_class = 'fa-solid fa-chart-pie';
                elseif (strpos($slug_lower, 'shop') !== false) $icon_class = 'fa-solid fa-bag-shopping';
                elseif (strpos($slug_lower, 'company') !== false) $icon_class = 'fa-solid fa-building';
                elseif (strpos($slug_lower, 'commerce') !== false) $icon_class = 'fa-solid fa-cart-shopping';
                elseif (strpos($slug_lower, 'medical') !== false) $icon_class = 'fa-solid fa-user-doctor';
            @endphp

            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ url('/?kategori=' . $kat->slug) }}" class="category-pill">
                    <i class="{{ $icon_class }}"></i>
                    <small class="fw-bold mt-2">{{ $kat->nama_kategori }}</small>
                </a>
            </div>
        @endforeach
    </div>
</section>

<section class="product-area pt-20 pb-40" id="katalog">
    <div class="container">
        
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    @if($kategori_terpilih)
                        Kategori: {{ $kategori_terpilih }}
                    @else
                        Produk Terbaru 🔥
                    @endif
                </h2>
                <p class="text-muted m-0">
                    {{ $kategori_terpilih ? "Menampilkan hasil filter kategori." : "Template yang paling banyak dicari minggu ini." }}
                </p>
            </div>
            
            @if($kategori_terpilih)
                <a href="{{ url('/') }}" class="text-decoration-none fw-bold text-danger">
                    <i class="fa-solid fa-times"></i> Reset Filter
                </a>
            @endif
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse($products as $prod)
                @php
                    // Logika Path Gambar
                    // Menggunakan asset() yang mengarah ke public/assets/uploads/
                    $gambar_path = asset('assets/uploads/' . $prod->gambar);
                    
                    // Cek jika gambar kosong atau file tidak ada (Optional logic)
                    if (empty($prod->gambar)) {
                        $gambar_path = 'https://placehold.co/600x400?text=No+Image';
                    }
                @endphp

                <div class="col">
                    <div class="product-card h-100">
                        <div class="product-img-wrapper" onclick="window.location.href='{{ url('/product/' . $prod->id) }}';" style="cursor: pointer;">
                            
                            <span class="badge-tech">
                                <i class="fa-solid fa-tag text-primary"></i> {{ $prod->kategori }}
                            </span>
                            
                            @if($prod->is_featured)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark z-2">Featured</span>
                            @endif

                            <img src="{{ $gambar_path }}" alt="{{ $prod->nama_produk }}">
                        </div>

                        <div class="product-details">
                            <div class="category-text">{{ $prod->kategori }}</div>
                            
                            <h3 class="product-title" title="{{ $prod->nama_produk }}">
                                <a href="{{ url('/product/' . $prod->id) }}">{{ $prod->nama_produk }}</a>
                            </h3>
                            
                            <div class="card-footer-action">
                                <div class="price-final">Rp {{ number_format($prod->harga, 0, ',', '.') }}</div>
                                
                            <button type="button" 
                                    onclick="addToCart('{{ $prod->id }}', false)" 
                                    class="btn btn-sm btn-primary rounded-circle shadow-sm" 
                                    title="Tambah ke Keranjang">
                                <i class="fa-solid fa-cart-plus"></i>
                            </button>
                            </div>
                        </div>
                    </div>
                </div>      

            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center py-5 shadow-sm border-0">
                        <i class="fa-solid fa-box-open fa-3x mb-3 text-warning"></i><br>
                        <h5>Oops, belum ada produk ditemukan.</h5>
                        <p>Cobalah kategori lain atau reset filter.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

@guest
<section class="cta-section text-center">
    <div class="container position-relative z-1">
        <h2 class="display-6 fw-bold mb-3">Siap Membangun Project Berikutnya?</h2>
        <p class="lead mb-4 text-white opacity-75">Bergabung dengan ribuan developer lain. Beli template atau jual karya Anda sekarang.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light fw-bold px-4 py-2 rounded-pill">Masuk Akun</a>
        </div>
    </div>
</section>
@endguest

@endsection