<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-crown"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Super Admin</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::routeIs('super_admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('super_admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Menu Utama</div>

    <li class="nav-item {{ Request::routeIs('super_admin.users.*') ? 'active' : '' }}"> 
        <a class="nav-link" href="{{ route('super_admin.users.index') }}">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Kelola Pengguna</span>
        </a>
    </li>

    <li class="nav-item {{ Request::routeIs('super_admin.products.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('super_admin.products.index') }}">
            <i class="fas fa-fw fa-box-open"></i>
            <span>Kelola Produk</span>
        </a>
    </li>

    <li class="nav-item {{ Request::routeIs('super_admin.orders.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('super_admin.orders.index') }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Transaksi & Order</span>
        </a>
    </li>

    <li class="nav-item {{ Request::routeIs('super_admin.categories.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('super_admin.categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Kelola Kategori</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>