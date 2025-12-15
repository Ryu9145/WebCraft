@extends('layouts.master')

@section('content')

<style>
    /* Styling khusus Avatar agar rapi di Light Mode */
    .avatar-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff; /* Border putih agar misah dari background */
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .btn-camera {
        position: absolute;
        bottom: 5px;
        right: 10px;
        width: 40px;
        height: 40px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: 0.2s;
        color: #6c757d;
    }
    .btn-camera:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
    
    /* Styling Tabs agar mirip tombol */
    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 600;
        border-radius: 50rem; /* Pill shape */
        padding: 10px 20px;
        transition: 0.2s;
    }
    .nav-pills .nav-link.active {
        background-color: #0d6efd; /* Primary Blue */
        color: #fff;
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.3);
    }
</style>

<div class="container py-4">
    
    <div class="d-flex align-items-center mb-4">
        <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-white border rounded-circle shadow-sm me-3" 
           style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: #fff;">
            <i class="fa-solid fa-arrow-left text-secondary"></i>
        </a>
        <h3 class="mb-0 fw-bold text-dark">Pengaturan Akun</h3>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-5">
                    
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tipe" value="foto">

                        <div class="avatar-wrapper mb-4">
                            @php
                                $avatar = Auth::user()->avatar ? asset('assets/uploads/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->username).'&background=random&color=fff&size=150';
                            @endphp
                            
                            <img src="{{ $avatar }}" id="avatarPreview" class="avatar-img">
                            
                            {{-- Input File Hidden --}}
                            <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                            
                            {{-- Tombol Kamera Bulat --}}
                            <label for="avatarInput" class="btn-camera" title="Ganti Foto">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                        </div>

                        <h4 class="fw-bold text-dark mb-1">{{ Auth::user()->username }}</h4>
                        <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                            Simpan Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                            <i class="fa-solid fa-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button">
                                <i class="fa-solid fa-user-pen me-2"></i> Edit Username
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button">
                                <i class="fa-solid fa-lock me-2"></i> Ganti Password
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tipe" value="profile">
                                
                                <h5 class="fw-bold mb-4 text-dark">Informasi Dasar</h5>

                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-secondary small">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 text-muted">@</span>
                                            <input type="text" name="username" class="form-control bg-light border-start-0 text-dark" 
                                                   value="{{ Auth::user()->username }}" placeholder="Username unik" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-secondary small">Email Address</label>
                                        <input type="email" class="form-control bg-light text-muted" 
                                               value="{{ Auth::user()->email }}" disabled>
                                        <div class="form-text text-muted small">*Email tidak dapat diubah.</div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-password" role="tabpanel">
                            <form action="{{ route('user.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <h5 class="fw-bold mb-4 text-danger">Keamanan</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-secondary small">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control bg-light" placeholder="Masukkan password lama" required>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary small">Password Baru</label>
                                        <input type="password" name="password" class="form-control bg-light" placeholder="Min. 8 karakter" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary small">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control bg-light" placeholder="Ulangi password baru" required>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
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
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection