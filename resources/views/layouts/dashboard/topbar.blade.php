<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars text-primary"></i>
    </button>

    <div class="d-none d-sm-block ml-3">
        <h1 class="h5 mb-0 text-gray-800" style="font-weight: 600;">
            Halo, <span class="text-primary">{{ Auth::user()->username }}!</span> 👋
        </h1>
        <small class="text-muted">Selamat datang kembali di dashboard Anda.</small>
    </div>

    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ Auth::user()->username }}
                </span>

                {{-- LOGIKA PHP: Cek apakah user punya foto di database --}}
                @php
                    $imgSrc = Auth::user()->avatar 
                        ? asset('assets/uploads/' . Auth::user()->avatar) 
                        : 'https://ui-avatars.com/api/?name=' . Auth::user()->username . '&background=4e73df&color=fff&bold=true';
                @endphp

                <img class="img-profile rounded-circle shadow-sm"
                    src="{{ $imgSrc }}"
                    style="object-fit: cover;"> {{-- object-fit penting agar foto tidak gepeng --}}
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                
                <a class="dropdown-item" href="{{ route('profile.index') }}"> 
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil Saya
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Keluar
                </a>
            </div>
        </li>

    </ul>

</nav>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Logout" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
            <div class="modal-footer border-0">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" style="border-radius: 50px;">Batal</button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="border-radius: 50px;">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>