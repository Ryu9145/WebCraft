<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-smile"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Member Area</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard Saya</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Menu</div>

    <li class="nav-item {{ Request::routeIs('user.upload') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.upload') }}">
            <i class="fas fa-fw fa-cloud-upload-alt"></i>
            <span>Upload Produk</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <a href="{{ route('home') }}" class="btn btn-circle-custom bg-white text-info shadow-sm mx-auto" title="Ke Marketplace">
            <i class="fas fa-store"></i>
        </a>
    </div>

</ul>