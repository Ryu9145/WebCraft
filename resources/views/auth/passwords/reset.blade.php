@extends('layouts.master_login')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
</style>

<style>
    /* =========================================
       VARIABLES & BASE STYLE
    ========================================= */
    :root {
        --navy-dark:   #0f172a;
        --white:       #ffffff;
        --bg-page:     #f1f5f9;
        --text-muted:  #64748b;
        --danger:      #ef4444;
        --success:     #10b981;
        --font-family: 'Poppins', sans-serif;
        
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

    /* CARD CONTAINER */
    .reset-card {
        background: var(--white);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
        width: 500px;
        max-width: 90%;
        overflow: hidden;
        position: relative;
        text-align: center;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .reset-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.25);
    }

    /* HEADER VISUAL */
    .card-header-visual {
        background-color: var(--navy-dark);
        height: 180px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
    }

    /* SHAPES */
    .abstract-shape {
        position: absolute; border-radius: 50%; z-index: 0; pointer-events: none; mix-blend-mode: screen; 
        opacity: 0.15; filter: blur(80px); transition: var(--liquid-transition);
    }
    .shape-top {
        top: -150px; left: -100px; width: 250px; height: 250px;
        background: conic-gradient(from 180deg at 50% 50%, #00ff99, #00b8ff, #00ff99, #ffff00, #00ff00);
        animation: rotateAurora 20s infinite linear;
    }
    .shape-bottom {
        bottom: -150px; right: -100px; width: 250px; height: 250px;
        background: conic-gradient(from 0deg at 50% 50%, #00ffff, #0000ff, #8f00ff, #00ffff, #007cf0);
        animation: rotateAurora 25s infinite reverse linear;
    }
    @keyframes rotateAurora { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* Hover Reaction */
    .reset-card:hover .abstract-shape { opacity: 0.45; filter: blur(50px); }
    .reset-card:hover .shape-top { width: 450px; height: 450px; top: -100px; left: -80px; border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%; }
    .reset-card:hover .shape-bottom { width: 450px; height: 450px; bottom: -100px; right: -80px; border-radius: 41% 59% 45% 55% / 59% 49% 51% 41%; }

    /* ICON WRAPPER */
    .icon-wrapper {
        width: 80px; height: 80px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px;
        display: flex; justify-content: center; align-items: center; z-index: 2; backdrop-filter: blur(10px);
        transition: var(--smooth-transition); background-size: 200% auto;
    }
    .icon-wrapper i { font-size: 32px; color: var(--white); transition: var(--smooth-transition); }

    .reset-card:hover .icon-wrapper {
        background-image: linear-gradient(135deg, #00b8ff 0%, #00ff99 100%); border-color: transparent; transform: scale(1.1) rotate(-5deg); box-shadow: 0 15px 35px rgba(0, 184, 255, 0.4);
    }
    .reset-card:hover .icon-wrapper i { transform: scale(1.1); text-shadow: 0 2px 10px rgba(0,0,0,0.2); }

    /* BODY */
    .card-body { padding: 30px 40px; position: relative; z-index: 2; }
    h1 { font-size: 24px; font-weight: 700; color: var(--navy-dark); margin-bottom: 5px; letter-spacing: -0.5px; }
    p { color: var(--text-muted); font-size: 13px; line-height: 1.6; margin-bottom: 20px; }

    input {
        background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 15px; margin-top: 5px; margin-bottom: 2px; width: 100%; border-radius: 10px; font-size: 13.5px; color: var(--navy-dark); font-family: var(--font-family); font-weight: 500; transition: var(--smooth-transition);
    }
    input:focus { background-color: var(--white); border-color: var(--navy-dark); box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08); outline: none; }
    input.is-invalid { border-color: var(--danger); background-color: #fff5f5; }
    
    .invalid-feedback { display: block; width: 100%; font-size: 11px; color: var(--danger); text-align: left; margin-bottom: 8px; padding-left: 5px; font-weight: 500; animation: slideDown 0.4s ease; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

    button.primary-btn {
        border-radius: 50px; background-color: var(--navy-dark); color: var(--white); font-size: 13px; font-weight: 600; padding: 12px 45px; letter-spacing: 0.5px; border: 1px solid transparent; cursor: pointer; width: 100%; margin-top: 15px; transition: var(--smooth-transition); background-size: 200% auto;
    }
    button.primary-btn:hover {
        background-image: linear-gradient(135deg, #000000 0%, #1a1a2e 100%); transform: translateY(-3px) scale(1.02); border-color: rgba(255, 255, 255, 0.2); box-shadow: 0 10px 20px rgba(0,0,0,0.3), inset 0 0 15px rgba(59, 130, 246, 0.3);
    }
</style>

<div class="reset-card">
    <div class="card-header-visual">
        <div class="abstract-shape shape-top"></div>
        <div class="abstract-shape shape-bottom"></div>
        <div class="icon-wrapper">
            <i class="fa-solid fa-lock-open"></i>
        </div>
    </div>

    <div class="card-body">
        <h1>Buat Password Baru</h1>
        <p>Hampir selesai! Masukkan password baru Anda yang kuat dan mudah diingat.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly 
                   style="background-color: #e2e8f0; color: #64748b; cursor: not-allowed;">
            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <input type="password" name="password" placeholder="Password Baru" required autofocus class="@error('password') is-invalid @enderror">
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" required>

            <button type="submit" class="primary-btn">Ubah Password</button>
        </form>
    </div>
</div>

@endsection