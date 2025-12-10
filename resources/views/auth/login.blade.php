@extends('layouts.master_login')

@section('content')

<style>
    /* =========================================
       1. CSS CUSTOM (Warna & Animasi)
    ========================================= */
    .custom-alert {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 15px; /* Jarak ke elemen judul */
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-align: left;
        display: flex;
        align-items: center;
        
        /* Animasi Slide Down */
        position: relative; 
        animation: slideDown 0.5s ease forwards;
    }

    .custom-alert i {
        font-size: 16px;
        margin-right: 10px;
    }

    /* Warna Alert Error */
    .alert-error {
        background-color: #ffe6e6;
        color: #d63031;
        border-left: 5px solid #d63031;
    }

    /* Warna Alert Sukses */
    .alert-success {
        background-color: #e6fffa;
        color: #00b894;
        border-left: 5px solid #00b894;
    }

    /* Animasi Slide Down */
    @keyframes slideDown {
        from { top: -20px; opacity: 0; }
        to { top: 0; opacity: 1; }
    }

    /* =========================================
       2. STYLE INPUT DISABLED (SAAT DIBLOKIR)
    ========================================= */
    input:disabled, button:disabled {
        background-color: #f3f4f6 !important;
        color: #9ca3af !important;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }

    /* Teks Hitung Mundur */
    .countdown-timer {
        color: #d63031;
        font-weight: bold;
        font-size: 13px;
        margin-top: -5px;
        margin-bottom: 20px;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>

<div class="container" id="container">
    
    <div class="form-container sign-up-container">
        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <h1>Create Account</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ route('google.login') }}" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your email for registration</span>
            <input type="text" placeholder="Username" name="username" required />
            <input type="email" placeholder="Email" name="email" required />
            <input type="password" placeholder="Password" name="password" required />
            <input type="password" placeholder="Confirm Password" name="password_confirmation" required />
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            @if(session('blocked_error'))
                <div class="custom-alert alert-error">
                    <i class="fa-solid fa-lock"></i>
                    <div>
                        <strong>Akses Dibatasi Sementara!</strong><br>
                        Terlalu banyak percobaan gagal.
                    </div>
                </div>
                <div class="countdown-timer">
                    Silakan tunggu <span id="timer">{{ session('blocked_error') }}</span> detik lagi.
                </div>
            @endif

            @if(session('login_error'))
                <div class="custom-alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> 
                    {{ session('login_error') }}
                </div>
            @endif

            @error('username')
                <div class="custom-alert alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ $message }}
                </div>
            @enderror

            @if(session('success'))
                <div class="custom-alert alert-success">
                    <i class="fa-solid fa-check-circle"></i> 
                    {{ session('success') }}
                </div>
            @endif
            
            <h1 class="mb-3">Sign in</h1>

            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ route('google.login') }}" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>

            <span>or use your account</span>

            <input type="text" name="username" 
                   placeholder="Email Address (@gmail.com)" 
                   value="{{ old('username') }}" 
                   {{ session('blocked_error') ? 'disabled' : '' }} 
                   required>
            
            <input type="password" placeholder="Password" name="password" 
                   {{ session('blocked_error') ? 'disabled' : '' }} 
                   required />
            
            <a href="#">Forgot your password?</a>
            
            <button type="submit" {{ session('blocked_error') ? 'disabled' : '' }}>
                Sign In
            </button>
            
            <a href="{{ url('/') }}" style="margin-top: 15px; font-size: 12px; text-decoration: none;">
                <span><i class="fa fa-arrow-left"></i> Kembali ke Beranda</span>
            </a>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Sobat Dev!</h1>
                <p>Enter your personal details and start journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
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
        if(timerElement) {
            timerElement.innerText = timeLeft;
        }

        if (timeLeft <= 0) {
            clearInterval(countdown);
            window.location.href = "{{ route('login') }}"; 
        }
    }, 1000);
</script>
@endif

@endsection