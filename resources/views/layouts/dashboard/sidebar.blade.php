<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cube text-warning"></i>
        </div>
        <div class="sidebar-brand-text mx-3">WebCraft</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-arrow-left"></i>
            <span>Halaman Utama</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Transaksi
    </div>

    <li class="nav-item {{ request()->routeIs('cart.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('cart.index') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Keranjang Saya</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Seller Area
    </div>

    <li class="nav-item {{ request()->routeIs('user.upload') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.upload') }}">
            <i class="fas fa-fw fa-cloud-upload-alt"></i>
            <span>Upload Produk</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    </ul>