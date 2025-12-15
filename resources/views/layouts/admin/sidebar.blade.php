<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-user-shield text-info rotate-n-15"></i> 
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    
    <div class="sidebar-heading">
        Operasional
    </div>

    <li class="nav-item {{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Kelola User</span>
        </a>
    </li>

    <li class="nav-item {{ Request::routeIs('admin.products.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.products.index') }}">
            <i class="fas fa-fw fa-box-open"></i>
            <span>Kelola Produk</span>
        </a>
    </li>

    <li class="nav-item {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Kelola Kategori</span>
        </a>
    </li>

    <li class="nav-item {{ Request::routeIs('admin.orders.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.orders.index') }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Transaksi Masuk</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}" target="_blank">
            <i class="fas fa-fw fa-globe"></i>
            <span>Lihat Website</span>
        </a>
    </li>

    <hr class="sidebar-divider">

</ul>