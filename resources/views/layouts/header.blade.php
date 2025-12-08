<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fa-solid fa-code me-2 text-primary"></i>ThemeMarket.
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#">HTML & CSS</a></li>
                <li class="nav-item"><a class="nav-link" href="#">WordPress</a></li>
                <li class="nav-item"><a class="nav-link" href="#">React & Vue</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="#" class="text-secondary me-3" title="Search"><i class="fa-solid fa-magnifying-glass"></i></a>
                
                @auth
                    <a href="{{ route('cart.index') }}" class="text-secondary position-relative me-3" title="Keranjang">
                        <i class="fa-solid fa-cart-shopping fs-5"></i>
                        
                        @php
                            $cartCount = 0;
                            // Pastikan model Keranjang ada dan user login
                            if(Auth::check()){
                                $cartCount = \App\Models\Keranjang::where('username', Auth::user()->username)->count();
                            }
                        @endphp

                        <span id="cart-badge" 
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}" 
                            style="font-size: 9px;">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill btn-sm px-3 text-white text-decoration-none me-2" style="font-weight: 600;">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload
                    </a>

                    <div class="vr d-none d-lg-block mx-2" style="height: 25px;"></div>

                    <div class="dropdown">
                        <button class="user-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none; padding: 0;">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}&background=0f172a&color=fff&bold=true" 
                                 alt="User" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                        </button>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="border-radius: 12px; min-width: 200px;">
                            <li><h6 class="dropdown-header">Hai, {{ Auth::user()->username }}! 👋</h6></li>
                            
                            @if(Auth::user()->role == 'superadmin')
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('super_admin.dashboard') }}">
                                        <i class="fa-solid fa-gauge me-2"></i> Admin Panel
                                    </a>
                                </li>
                            @elseif(Auth::user()->role == 'admin')
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                        <i class="fa-solid fa-gauge me-2"></i> Admin Panel
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                                        <i class="fa-solid fa-table-columns me-2"></i> Dashboard Saya
                                    </a>
                                </li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                @else
                    <div class="vr d-none d-lg-block mx-2" style="height: 25px;"></div>
                    <a href="{{ route('login') }}" class="btn btn-login text-decoration-none" style="font-weight: 700; color: #0f172a;">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-register text-decoration-none ms-2" style="background-color: #0f172a; color: white; font-weight: 700; padding: 8px 25px; border-radius: 8px;">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>  