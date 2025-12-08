<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ThemeMarket') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        :root { --primary-color: #0f172a; --accent-color: #3b82f6; --bg-light: #f1f5f9; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: var(--primary-color); }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px; }
        .nav-link { font-weight: 600; color: var(--primary-color); margin: 0 10px; font-size: 0.95rem; }
        .nav-link:hover { color: var(--accent-color); }
        
        /* Tombol Auth */
        .btn-login { color: var(--primary-color); font-weight: 700; border: 2px solid transparent; padding: 8px 20px; border-radius: 8px; text-decoration: none; }
        .btn-login:hover { color: var(--accent-color); background: rgba(59, 130, 246, 0.05); }
        .btn-register { background-color: var(--primary-color); color: white; font-weight: 700; padding: 8px 25px; border-radius: 8px; text-decoration: none; border: 2px solid var(--primary-color); }
        .btn-register:hover { background-color: var(--accent-color); border-color: var(--accent-color); color: white; }

        /* User Avatar & Cart */
        .user-menu-btn { border: none; background: transparent; padding: 0; cursor: pointer; }
        .cart-animate { animation: cartBounce 0.3s ease-in-out; }
        @keyframes cartBounce { 0% { transform: scale(1); } 50% { transform: scale(1.5); } 100% { transform: scale(1); } }
    </style>

    @stack('styles')
</head>
<body>

    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <div id="app-config" 
         data-auth="{{ auth()->check() ? '1' : '0' }}" 
         data-login-url="{{ route('login') }}"
         style="display: none;">
    </div>

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="{{ asset('assets/js/script.js') }}"></script>

    @stack('scripts') @yield('scripts') <script>
        $(document).ready(function() {
            // 1. Inisialisasi Config
            var config = $('#app-config');
            var cartUrl = "{{ route('cart.index') }}"; 
            
            // Baca status login
            var userIsLoggedIn = config.attr('data-auth') === '1'; 
            var loginUrl = config.attr('data-login-url');

            // 2. Fungsi Add To Cart Global
            window.addToCart = function(produkId, redirect = false) {
                
                // A. Cek Login
                if (!userIsLoggedIn) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Anda belum login',
                        text: 'Silakan login terlebih dahulu untuk berbelanja.',
                        showCancelButton: true,
                        confirmButtonText: 'Login Sekarang',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#0f172a',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = loginUrl;
                    });
                    return; 
                }

                // B. Eksekusi AJAX
                var url = "{{ url('/cart/add-ajax') }}/" + produkId;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // SUKSES
                    if (data.status === 'success') {
                        var badge = $('#cart-badge');
                        if (badge.length) {
                            badge.text(data.new_count);
                            badge.removeClass('d-none');
                            badge.addClass('cart-animate');
                            setTimeout(() => { badge.removeClass('cart-animate'); }, 500);
                        }

                        if (redirect === true) {
                            window.location.href = cartUrl;
                        } else {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Produk ditambahkan ke keranjang.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    } 
                    // SUDAH ADA
                    else if (data.status === 'exist') {
                        if (redirect === true) {
                            window.location.href = cartUrl;
                        } else {
                            Swal.fire({
                                title: 'Sudah di Keranjang',
                                text: 'Produk ini sebelumnya sudah Anda tambahkan.',
                                icon: 'info',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            };
        });
    </script>

</body>
</html>