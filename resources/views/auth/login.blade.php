@extends('layouts.master_login')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
</style>

<style>
    /* =========================================
       1. VARIABLES & BASE STYLE
    ========================================= */
    :root {
        --navy-dark:   #0f172a;
        --white:       #ffffff;
        --bg-page:     #f1f5f9;
        --text-muted:  #64748b;
        --danger:      #ef4444;
        --success:     #10b981;
        --font-family: 'Poppins', sans-serif;
        
        /* Smooth Transition Variable */
        --smooth-transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        /* Liquid Transition (Lebih lambat untuk background) */
        --liquid-transition: all 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    body {
        font-family: var(--font-family);
        background-color: var(--bg-page);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        overflow: hidden;
    }

    /* CONTAINER UTAMA */
    .container {
        background: var(--white);
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15);
        position: relative;
        overflow: hidden;
        width: 800px;
        max-width: 100%;
        min-height: 520px;
        z-index: 1;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .container:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.25);
    }

    h1 { font-weight: 700; color: var(--navy-dark); margin-bottom: 5px; font-size: 26px; }
    .overlay-panel h1 { color: var(--white); font-size: 32px; margin-bottom: 10px;}
    p { font-size: 13.5px; line-height: 1.6; font-weight: 400; }
    span.or-text { font-size: 12px; color: #94a3b8; margin: 10px 0; font-weight: 500; display: block; }

    /* =========================================
       2. FORM ELEMENTS
    ========================================= */
    .form-container form {
        background: var(--white); display: flex; flex-direction: column; padding: 0 40px; height: 100%; justify-content: center; align-items: center; text-align: center;
    }

    input {
        background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 15px; margin-top: 5px; margin-bottom: 2px; width: 100%; border-radius: 10px; font-size: 13.5px; color: var(--navy-dark); font-family: var(--font-family); font-weight: 500;
        transition: var(--smooth-transition);
    }
    input:focus {
        background-color: var(--white); border-color: var(--navy-dark); box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08); outline: none;
    }
    input.is-invalid {
        border-color: var(--danger); background-color: #fff5f5; padding-right: 30px; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linecap='round' d='M6 3.5v3M6 8.5h.01'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 10px center; background-size: 16px;
    }
    input:disabled { background-color: #e2e8f0; cursor: not-allowed; opacity: 0.6; }
    .invalid-feedback { display: block; width: 100%; font-size: 11px; color: var(--danger); text-align: left; margin-bottom: 8px; padding-left: 5px; font-weight: 500; animation: slideDown 0.4s ease; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

    /* TOMBOL UTAMA (Dark Glass Rainbow) */
    button.primary-btn {
        border-radius: 50px; background-color: var(--navy-dark); color: var(--white); font-size: 13px; font-weight: 600; padding: 12px 45px; letter-spacing: 0.5px; border: 1px solid transparent; margin-top: 10px; cursor: pointer; background-size: 200% auto;
        transition: var(--smooth-transition); 
    }
    button.primary-btn:hover {
        background-image: linear-gradient(135deg, #000000 0%, #1a1a2e 100%);
        transform: translateY(-3px) scale(1.02);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3), inset 0 0 20px rgba(59, 130, 246, 0.2);
    }
    button.primary-btn:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

    /* =========================================
       3. SOCIAL ICONS
    ========================================= */
    .social-container { margin: 10px 0; }
    .social-container a {
        border: 1px solid #e2e8f0; border-radius: 12px; display: inline-flex; justify-content: center; align-items: center; margin: 0 5px; height: 40px; width: 40px; color: var(--navy-dark); text-decoration: none; background: var(--white);
        transition: var(--smooth-transition);
    }
    .social-container a:hover {
        background: #0f172a; color: var(--white); border-color: transparent; transform: translateY(-4px) scale(1.1); box-shadow: -3px 8px 20px rgba(123, 31, 162, 0.25), 3px 8px 20px rgba(33, 150, 243, 0.25);
    }

    /* =========================================
       4. OVERLAY & DYNAMIC AURORA (REVISI LOGIC)
    ========================================= */
    .overlay-container { overflow: hidden; }
    .overlay {
        background: var(--navy-dark); color: var(--white); position: relative; z-index: 2;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.04'/%3E%3C/svg%3E");
    }

    /* --- ABSTRACT SHAPE BASE --- */
    .abstract-shape {
        position: absolute; 
        border-radius: 50%; 
        z-index: 0; 
        pointer-events: none; 
        mix-blend-mode: screen; 
        
        /* DEFAULT STATE (KECIL & JAUH) */
        opacity: 0.15; /* Sangat redup */
        filter: blur(80px); /* Sangat blur (seperti kabut) */
        
        /* TRANSISI LIQUID (Sangat lambat & smooth) */
        transition: var(--liquid-transition);
    }

    /* --- SHAPE TOP (Awal: Kecil & Sembunyi) --- */
    .shape-top {
        top: -180px; 
        left: -180px; 
        width: 250px; /* Ukuran Setengah */
        height: 250px;
        background: conic-gradient(from 180deg at 50% 50%, #ff0000, #ff8c00, #ffff00, #00ff00, #0000ff, #4b0082, #8f00ff, #ff0000);
        animation: rotateAurora 25s infinite linear;
    }

    /* --- SHAPE BOTTOM (Awal: Kecil & Sembunyi) --- */
    .shape-bottom {
        bottom: -180px; 
        right: -180px; 
        width: 250px; /* Ukuran Setengah */
        height: 250px;
        background: conic-gradient(from 0deg at 50% 50%, #00ffff, #0000ff, #8f00ff, #ff0000, #ff8c00, #ffff00, #00ff00, #00ffff);
        animation: rotateAurora 30s infinite reverse linear;
    }

    @keyframes rotateAurora { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* --- HOVER REACTION (MELEDAK / MEMBESAR) --- */
    .container:hover .abstract-shape {
        opacity: 0.45; /* Menjadi Terang */
        filter: blur(50px); /* Menjadi Lebih Tajam */
    }

    .container:hover .shape-top {
        width: 500px; /* Kembali ke Ukuran Normal (Besar) */
        height: 500px;
        top: -100px; /* Masuk ke area pandang */
        left: -100px;
        border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%; /* Morphing */
    }

    .container:hover .shape-bottom {
        width: 450px; /* Kembali ke Ukuran Normal (Besar) */
        height: 450px;
        bottom: -100px; /* Masuk ke area pandang */
        right: -100px;
        border-radius: 41% 59% 45% 55% / 59% 49% 51% 41%; /* Morphing */
    }

    /* Panel Animation */
    .overlay-panel { 
        position: absolute; display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 0 40px; text-align: center; top: 0; height: 100%; width: 50%; z-index: 3; 
        transition: transform 0.8s cubic-bezier(0.23, 1, 0.32, 1); 
    }
    .overlay-left { transform: translateX(-20%); }
    .overlay-right { right: 0; transform: translateX(0); }
    .container.right-panel-active .overlay-left { transform: translateX(0); }
    .container.right-panel-active .overlay-right { transform: translateX(20%); }

    /* GHOST BUTTON (No BG) */
    button.ghost {
        background: transparent; border: 2px solid rgba(255, 255, 255, 0.8); margin-top: 20px; color: var(--white); font-weight: 700; padding: 12px 45px; border-radius: 50px; cursor: pointer;
        transition: var(--smooth-transition);
    }
    button.ghost:hover {
        background: transparent; border-color: #fff; transform: scale(1.05) translateY(-2px); text-shadow: 0 0 10px rgba(255,255,255,0.7); box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    /* Extras */
    .forgot-pass { color: var(--text-muted); font-size: 12px; font-weight: 500; margin: 15px 0; text-decoration: none; transition: 0.2s; }
    .forgot-pass:hover { color: var(--navy-dark); text-decoration: underline; }

    /* Blocked Alert */
    .blocked-alert {
        width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 12px; background: rgba(254, 226, 226, 0.4); border: 1px solid rgba(239, 68, 68, 0.3); backdrop-filter: blur(5px); color: #b91c1c; display: flex; align-items: center; text-align: left; font-size: 12px; animation: pulseAlert 2s infinite;
    }
    .blocked-alert i { font-size: 18px; margin-right: 10px; }
    @keyframes pulseAlert { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.2); } 70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }
    .timer-count { font-weight: 800; font-size: 14px; }

</style>

<div class="container {{ ($errors->has('username') || $errors->has('email') || $errors->has('password')) ? 'right-panel-active' : '' }}" id="container">
    
    <div class="form-container sign-up-container">
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            <h1>Gabung WebCraft</h1>
            
            <div class="social-container">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ route('google.login') }}"><i class="fab fa-google"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
            </div>
            
            <span class="or-text">atau daftar manual</span>
            
            <input type="text" placeholder="Username" name="username" value="{{ old('username') }}" class="@error('username') is-invalid @enderror" required />
            @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <input type="email" placeholder="Email Address" name="email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" required />
            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <input type="password" placeholder="Password" name="password" class="@error('password') is-invalid @enderror" required />
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <input type="password" placeholder="Confirm Password" name="password_confirmation" required />
            
            <button type="submit" class="primary-btn" id="btnRegisterSubmit">Daftar</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            @if(session('blocked_error'))
                <div class="blocked-alert">
                    <i class="fa-solid fa-lock"></i> 
                    <div><strong>Akses Dikunci Sementara</strong><br>Tunggu <span class="timer-count" id="timer">{{ session('blocked_error') }}</span> detik.</div>
                </div>
            @endif

            @if(session('login_error'))
                <div class="invalid-feedback" style="text-align: center; margin-bottom: 10px;"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('login_error') }}</div>
            @endif
            @if(session('success'))
                <div style="color: var(--success); font-size: 12px; margin-bottom: 10px; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <h1 class="mb-3">Selamat Datang</h1>
            
            <div class="social-container">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ route('google.login') }}"><i class="fab fa-google"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
            </div>
            
            <span class="or-text">masuk dengan email</span>

            <input type="text" name="username" placeholder="Email / Username" value="{{ old('username') }}" {{ session('blocked_error') ? 'disabled' : '' }} required>
            @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <input type="password" placeholder="Password" name="password" {{ session('blocked_error') ? 'disabled' : '' }} required />
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <a href="{{ route('password.request') }}" class="forgot-pass">Lupa Password?</a>
            
            <button type="submit" class="primary-btn" {{ session('blocked_error') ? 'disabled' : '' }}>Masuk</button>
            
            <a href="{{ url('/') }}" style="margin-top: 25px; font-size: 12px; color: var(--text-muted); text-decoration: none; display: flex; align-items: center; transition: 0.3s;">
                <i class="fa fa-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="abstract-shape shape-top"></div>
            <div class="abstract-shape shape-bottom"></div>

            <div class="overlay-panel overlay-left">
                <h1>Member Lama?</h1>
                <p>Login kembali untuk mengelola project dan aset digital Anda.</p>
                <button class="ghost" id="signIn">Masuk Sini</button>
            </div>
            
            <div class="overlay-panel overlay-right">
                <h1>Developer Baru?</h1>
                <p>Bergabunglah dengan WebCraft. Jual karya codingmu atau temukan solusi instan.</p>
                <button class="ghost" id="signUp">Buat Akun</button>
            </div>
        </div>
    </div>
</div>

@if(session('blocked_error'))
<script>
    let timeLeft = parseInt("{{ session('blocked_error') }}") || 30;
    const timerElement = document.getElementById('timer');
    const countdown = setInterval(() => {
        timeLeft--;
        if(timerElement) timerElement.innerText = timeLeft;
        if (timeLeft <= 0) {
            clearInterval(countdown);
            window.location.href = "{{ route('login') }}";
        }
    }, 1000);
</script>
@endif

<script>
    const container = document.getElementById('container');
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');

    if (signUpButton && signInButton && container) {
        signUpButton.addEventListener('click', () => { container.classList.add("right-panel-active"); });
        signInButton.addEventListener('click', () => { container.classList.remove("right-panel-active"); });
    }

    const registerForm = document.getElementById('registerForm');
    const btnRegister = document.getElementById('btnRegisterSubmit');
    if(registerForm && btnRegister) {
        registerForm.addEventListener('submit', function() {
            btnRegister.innerHTML = '<i class="fa fa-circle-notch fa-spin"></i> Loading...';
            btnRegister.style.opacity = '0.7';
            setTimeout(() => { btnRegister.disabled = true; }, 0);
        });
    }
</script>

@endsection