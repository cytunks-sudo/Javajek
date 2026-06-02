@php
    $setting = \App\Models\AppSetting::first();

    $primaryColor = $setting->primary_color ?? '#f97316';
    $secondaryColor = $setting->secondary_color ?? '#fb923c';
    $appName = $setting->app_name ?? 'JavaJek';

    $loginLogo = !empty($setting->login_logo)
        ? asset('storage/'.$setting->login_logo)
        : asset('images/logo-javajek.png');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login {{ $appName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root{
            --primary: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            overflow-x:hidden;
        }

        .bg-circle{
            position:fixed;
            border-radius:50%;
            background:rgba(255,255,255,.12);
            pointer-events:none;
        }

        .bg1{
            width:320px;
            height:320px;
            top:-120px;
            right:-90px;
        }

        .bg2{
            width:240px;
            height:240px;
            bottom:-90px;
            left:-70px;
        }

        .login-card{
            width:100%;
            max-width:430px;
            background:rgba(255,255,255,.96);
            backdrop-filter:blur(18px);
            border-radius:34px;
            padding:32px;
            box-shadow:0 24px 60px rgba(15,23,42,.20);
            position:relative;
            z-index:2;
        }

        .logo-wrap{
            text-align:center;
            margin-bottom:24px;
        }

        .logo-wrap img{
            width:92px;
            height:92px;
            object-fit:contain;
            border-radius:24px;
            background:white;
            padding:8px;
            box-shadow:0 12px 26px rgba(15,23,42,.12);
            margin:auto;
            display:block;
        }

        .logo-wrap h1{
            margin-top:12px;
            font-size:30px;
            font-weight:900;
            color:var(--primary);
        }

        .logo-wrap p{
            color:#6b7280;
            font-size:14px;
            margin-top:4px;
        }

        .form-group{
            margin-bottom:16px;
        }

        .form-group label{
            display:block;
            margin-bottom:7px;
            font-weight:800;
            color:#374151;
            font-size:14px;
        }

        .form-control{
            width:100%;
            padding:15px;
            border:none;
            outline:none;
            background:rgba(15,23,42,.05);
            border:2px solid transparent;
            border-radius:17px;
            font-size:15px;
            color:#111827;
        }

        .form-control:focus{
            border-color:var(--primary);
            background:white;
            box-shadow:0 0 0 4px rgba(15,23,42,.06);
        }

        .remember{
            display:flex;
            align-items:center;
            gap:8px;
            margin-bottom:20px;
            font-size:14px;
            color:#374151;
        }

        .remember input{
            width:16px;
            height:16px;
            accent-color:var(--primary);
        }

        .btn-login{
            width:100%;
            border:none;
            padding:16px;
            border-radius:18px;
            font-weight:900;
            font-size:15px;
            cursor:pointer;
            color:white;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            box-shadow:0 12px 26px rgba(15,23,42,.18);
        }

        .btn-login:hover{
            transform:translateY(-1px);
        }

        .links{
            margin-top:20px;
            text-align:center;
            color:#6b7280;
            font-size:14px;
        }

        .links a{
            text-decoration:none;
            font-weight:900;
            color:var(--primary);
        }

        .forgot{
            display:block;
            margin-bottom:14px;
        }

        .error{
            margin-top:7px;
            font-size:13px;
            color:#dc2626;
            font-weight:800;
        }

        .success-box{
            margin-bottom:15px;
            background:#dcfce7;
            color:#166534;
            font-weight:900;
            padding:12px;
            border-radius:16px;
            font-size:14px;
        }

        @media(max-width:480px){
            .login-card{
                padding:24px;
                border-radius:28px;
            }

            .logo-wrap h1{
                font-size:26px;
            }
        }
    </style>
</head>

<body>

<div class="bg-circle bg1"></div>
<div class="bg-circle bg2"></div>

<div class="login-card">

    <div class="logo-wrap">
        <img src="{{ $loginLogo }}" alt="{{ $appName }}">

        <h1>{{ $appName }}</h1>
        <p>Masuk menggunakan email, username, atau nomor HP.</p>
    </div>

    @if (session('status'))
        <div class="success-box">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>Email / Username / Nomor HP</label>

            <input
                type="text"
                name="login"
                value="{{ old('login') }}"
                class="form-control"
                placeholder="contoh: rio / rio@email.com / 08xxxx"
                required
                autofocus>

            @error('login')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Password</label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Masukkan password"
                required>

            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <label class="remember">
            <input type="checkbox" name="remember">
            <span>Ingat saya</span>
        </label>

        <button class="btn-login">
            🚀 MASUK SEKARANG
        </button>

        <div class="links">
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot">
                    Lupa Password?
                </a>
            @endif

            <div>
                Belum punya akun?
                <a href="{{ route('register') }}">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </form>

</div>

</body>
</html>