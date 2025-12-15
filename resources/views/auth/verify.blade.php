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
        --font-family: 'Poppins', sans-serif;
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
       2. VERIFY CARD CONTAINER
    ========================================= */
    .verify-card {
        background: var(--white);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
        width: 500px;
        max-width: 90%;
        overflow: hidden; /* Penting agar header tidak bocor */
        position: relative;
        text-align: center;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .verify-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.2);
    }

    /* =========================================
       3. LIQUID AURORA HEADER (Bagian Atas)
    ========================================= */
    .card-header-visual {
        background-color: var(--navy-dark);
        height: 220px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        /* Noise Texture Halus */
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
    }

    /* SHAPES (Aurora Blobs) - Sama persis dengan Login */
    .abstract-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.4;
        z-index: 0;
        mix-blend-mode: screen;
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        pointer-events: none;
    }

    .shape-top {
        top: -100px; left: -80px; width: 350px; height: 350px;
        background: conic-gradient(from 180deg at 50% 50%, #ff0000, #ff8c00, #ffff00, #00ff00, #0000ff, #4b0082, #8f00ff, #ff0000);
        animation: rotateAurora 20s infinite linear;
    }

    .shape-bottom {
        bottom: -100px; right: -80px; width: 300px; height: 300px;
        background: conic-gradient(from 0deg at 50% 50%, #00ffff, #0000ff, #8f00ff, #ff0000, #ff8c00, #ffff00, #00ff00, #00ffff);
        animation: rotateAurora 25s infinite reverse linear;
    }

    @keyframes rotateAurora { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* Hover Reaction pada Header */
    .verify-card:hover .shape-top { transform: scale(1.1) rotate(15deg); }
    .verify-card:hover .shape-bottom { transform: scale(1.15) rotate(-15deg); }


    /* =========================================
       4. ICON WRAPPER (DARK GLASS RAINBOW)
    ========================================= */
    .icon-wrapper {
        width: 90px;
        height: 90px;
        background: rgba(255, 255, 255, 0.1); /* Glassy White */
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        
        /* Persiapan Liquid Hover */
        background-size: 200% auto;
    }

    .icon-wrapper i {
        font-size: 40px;
        color: var(--white);
        transition: all 0.5s ease;
    }

    /* Hover Effect pada Icon (Menjadi Liquid Rainbow) */
    .verify-card:hover .icon-wrapper {
        /* Gradient Pelangi Liquid */
        background-image: linear-gradient(135deg, #00C6FF 0%, #0072FF 51%, #00C6FF 100%);
        border-color: transparent;
        transform: scale(1.1) rotate(-5deg);
        box-shadow: 0 15px 35px rgba(0, 114, 255, 0.4);
    }
    
    .verify-card:hover .icon-wrapper i {
        transform: scale(1.1);
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    /* =========================================
       5. CONTENT BODY
    ========================================= */
    .card-body {
        padding: 40px 30px;
        position: relative;
        z-index: 2;
    }

    h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--navy-dark);
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    p {
        color: var(--text-muted);
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 25px;
        font-weight: 300;
    }

    p strong{
        font-weight: bold;
    }

    strong { color: var(--navy-dark); }

    /* =========================================
       6. BUTTONS (Sama seperti Login)
    ========================================= */
    button.primary-btn {
        border-radius: 50px;
        background-color: var(--navy-dark);
        color: var(--white);
        font-size: 13px; font-weight: 600; padding: 12px 45px; letter-spacing: 0.5px; border: 1px solid transparent; cursor: pointer;
        transition: all 0.4s ease;
        background-size: 200% auto;
        width: 100%;
        margin-bottom: 15px;
    }

    button.primary-btn:hover {
        background-image: linear-gradient(135deg, #000000 0%, #1a1a2e 100%);
        transform: translateY(-3px) scale(1.02);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3), inset 0 0 15px rgba(59, 130, 246, 0.3);
    }

    /* Logout Link */
    .btn-link-logout {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-link-logout:hover {
        color: var(--navy-dark);
        transform: translateY(-1px);
    }

    /* Alert Sukses */
    .alert-success-custom {
        background-color: #ecfdf5;
        color: #047857;
        padding: 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: slideDown 0.4s ease;
        border: 1px solid #a7f3d0;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

</style>

<div class="verify-card">
    
    <div class="card-header-visual">
        <div class="abstract-shape shape-top"></div>
        <div class="abstract-shape shape-bottom"></div>
        
        <div class="icon-wrapper">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
    </div>

    <div class="card-body">
        <h1>Cek Email Anda</h1>
        
        @if (session('message') == 'Link verifikasi telah dikirim ulang!')
            <div class="alert-success-custom">
                <i class="fa-solid fa-circle-check me-2"></i> Link verifikasi baru berhasil dikirim!
            </div>
        @endif

        <p>
            Halo <strong>{{ Auth::user()->username }}</strong>, kami telah mengirimkan link verifikasi ke email Anda.
            Silakan periksa folder <strong>Inbox</strong> atau <strong>Spam</strong>.
        </p>

        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="primary-btn">
                <i class="fa-solid fa-paper-plane me-2"></i> Kirim Ulang Link
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn-link-logout">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout / Ganti Akun
            </button>
        </form>
    </div>

</div>

@endsection