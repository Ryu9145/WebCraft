@extends('layouts.master_login')

@section('content')

<style>
    /* =========================================
       1. VARIABLES & BASE STYLE
    ========================================= */
    :root {
        --navy-dark:   #0f172a;
        --white:       #ffffff;
        --bg-page:     #f1f5f9;
        --text-main:   #334155;
        --text-muted:  #64748b;
        --blue-brand:  #3b82f6;
        --danger:      #ef4444;
        --success:     #10b981;
        --font-family: 'Poppins', sans-serif;
        
        /* Smooth Transition */
        --smooth-transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
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

    /* =========================================
       2. FORGOT CARD CONTAINER
    ========================================= */
    .forgot-card {
        background: var(--white);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
        width: 480px; /* Lebar ideal untuk form tunggal */
        max-width: 90%;
        overflow: hidden;
        position: relative;
        text-align: center;
        
        /* Smooth Entrance & Hover */
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .forgot-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.25);
    }

    /* =========================================
       3. LIQUID AURORA HEADER
    ========================================= */
    .card-header-visual {
        background-color: var(--navy-dark);
        height: 200px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
    }

    /* ABSTRACT SHAPES (Aurora Logic) */
    .abstract-shape {
        position: absolute; 
        border-radius: 50%; 
        z-index: 0; 
        pointer-events: none; 
        mix-blend-mode: screen; 
        
        /* DEFAULT STATE (KECIL & JAUH) */
        opacity: 0.15; 
        filter: blur(80px); 
        transition: var(--liquid-transition);
    }

    /* Shape Top (Magenta/Purple Bias) */
    .shape-top {
        top: -180px; left: -100px; width: 250px; height: 250px;
        background: conic-gradient(from 180deg at 50% 50%, #ff0080, #7928ca, #ff0080, #ff0000, #4b0082);
        animation: rotateAurora 20s infinite linear;
    }

    /* Shape Bottom (Cyan/Blue Bias) */
    .shape-bottom {
        bottom: -180px; right: -100px; width: 250px; height: 250px;
        background: conic-gradient(from 0deg at 50% 50%, #00ffff, #007cf0, #00ffff, #0000ff, #8f00ff);
        animation: rotateAurora 25s infinite reverse linear;
    }

    @keyframes rotateAurora { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* HOVER REACTION (EXPAND) */
    .forgot-card:hover .abstract-shape { opacity: 0.45; filter: blur(50px); }
    
    .forgot-card:hover .shape-top {
        width: 450px; height: 450px; top: -100px; left: -80px;
        border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
    }
    
    .forgot-card:hover .shape-bottom {
        width: 450px; height: 450px; bottom: -100px; right: -80px;
        border-radius: 41% 59% 45% 55% / 59% 49% 51% 41%;
    }


    /* =========================================
       4. ICON WRAPPER (DARK GLASS RAINBOW)
    ========================================= */
    .icon-wrapper {
        width: 80px; height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex; justify-content: center; align-items: center;
        z-index: 2; backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transition: var(--smooth-transition);
        background-size: 200% auto;
    }

    .icon-wrapper i { font-size: 32px; color: var(--white); transition: var(--smooth-transition); }

    /* Hover Effect pada Icon */
    .forgot-card:hover .icon-wrapper {
        background-image: linear-gradient(135deg, #FF0080 0%, #7928CA 100%); /* Warna Kunci (Key) */
        border-color: transparent;
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 35px rgba(121, 40, 202, 0.4);
    }
    .forgot-card:hover .icon-wrapper i { transform: scale(1.1); text-shadow: 0 2px 10px rgba(0,0,0,0.2); }

    /* =========================================
       5. CONTENT BODY
    ========================================= */
    .card-body { padding: 40px 35px; position: relative; z-index: 2; }

    h1 {
        font-size: 24px; font-weight: 700; color: var(--navy-dark); margin-bottom: 10px; letter-spacing: -0.5px;
    }

    p { color: var(--text-muted); font-size: 13.5px; line-height: 1.6; margin-bottom: 25px; }

    /* INPUT STYLE */
    input {
        background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 15px; margin-bottom: 5px; width: 100%; border-radius: 10px; font-size: 13.5px; color: var(--navy-dark); font-family: var(--font-family); font-weight: 500;
        transition: var(--smooth-transition);
    }
    input:focus {
        background-color: var(--white); border-color: var(--navy-dark); box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08); outline: none;
    }
    input.is-invalid {
        border-color: var(--danger); background-color: #fff5f5;
    }
    
    .invalid-feedback {
        display: block; width: 100%; font-size: 11px; color: var(--danger); text-align: left; margin-bottom: 15px; padding-left: 5px; font-weight: 500; animation: slideDown 0.4s ease;
    }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

    /* BUTTON STYLE */
    button.primary-btn {
        border-radius: 50px; background-color: var(--navy-dark); color: var(--white); font-size: 13px; font-weight: 600; padding: 12px 45px; letter-spacing: 0.5px; border: 1px solid transparent; cursor: pointer; width: 100%; margin-bottom: 20px; margin-top: 10px;
        transition: var(--smooth-transition); background-size: 200% auto;
    }
    button.primary-btn:hover {
        background-image: linear-gradient(135deg, #000000 0%, #1a1a2e 100%);
        transform: translateY(-3px) scale(1.02);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3), inset 0 0 15px rgba(59, 130, 246, 0.3);
    }

    /* ALERT SUCCESS */
    .alert-success-custom {
        background-color: #ecfdf5; color: #047857; padding: 12px; border-radius: 12px; font-size: 12px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; animation: slideDown 0.4s ease; border: 1px solid #a7f3d0;
    }

    /* BACK LINK */
    .back-link {
        color: var(--text-muted); font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; transition: 0.3s;
    }
    .back-link:hover { color: var(--navy-dark); transform: translateX(-3px); }

</style>

<div class="forgot-card">
    
    <div class="card-header-visual">
        <div class="abstract-shape shape-top"></div>
        <div class="abstract-shape shape-bottom"></div>
        
        <div class="icon-wrapper">
            <i class="fa-solid fa-key"></i>
        </div>
    </div>

    <div class="card-body">
        <h1>Lupa Password?</h1>
        
        <p>
            Masukkan alamat email yang terdaftar. Kami akan mengirimkan link untuk mereset password Anda.
        </p>

        @if (session('status'))
            <div class="alert-success-custom">
                <i class="fa-solid fa-paper-plane me-2"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <input type="email" name="email" placeholder="Contoh: user@gmail.com" value="{{ old('email') }}" required autofocus class="@error('email') is-invalid @enderror">
            
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror

            <button type="submit" class="primary-btn">
                Kirim Link Reset
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Login
        </a>
    </div>

</div>

@endsection