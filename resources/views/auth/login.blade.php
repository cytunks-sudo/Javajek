@php
$setting = \App\Models\AppSetting::first();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>JavaJek Login</title>

<style>

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

background:
linear-gradient(135deg,#ff6b00,#ff8c24,#ffb15c);
}

.bg-circle{
position:fixed;
border-radius:50%;
background:rgba(255,255,255,.08);
}

.bg1{
width:300px;
height:300px;
top:-100px;
right:-80px;
}

.bg2{
width:220px;
height:220px;
bottom:-80px;
left:-60px;
}

.login-card{
width:100%;
max-width:430px;

background:rgba(255,255,255,.95);
backdrop-filter:blur(18px);

border-radius:35px;

padding:35px;

box-shadow:
0 20px 60px rgba(0,0,0,.15);
}

.logo-wrap{
text-align:center;
margin-bottom:20px;
}

.logo-wrap img{
width:95px;
height:95px;
object-fit:contain;
border-radius:24px;
background:white;
padding:10px;
box-shadow:0 10px 25px rgba(0,0,0,.1);
}

.logo-wrap h1{
margin-top:12px;
font-size:30px;
font-weight:900;
color:#ea580c;
}

.logo-wrap p{
color:#6b7280;
font-size:14px;
}

.form-group{
margin-bottom:16px;
}

.form-group label{
display:block;
margin-bottom:7px;
font-weight:700;
color:#9a3412;
}

.form-control{
width:100%;
padding:15px;
border:none;
outline:none;

background:#fff7ed;

border:2px solid transparent;

border-radius:16px;

font-size:15px;
}

.form-control:focus{
border-color:#fb923c;
}

.btn-login{
width:100%;
border:none;

padding:15px;

border-radius:16px;

font-weight:900;
font-size:16px;

cursor:pointer;

color:white;

background:
linear-gradient(
135deg,
#f97316,
#fb923c
);
}

.btn-login:hover{
opacity:.95;
}

.remember{
display:flex;
align-items:center;
gap:8px;
margin-bottom:20px;
font-size:14px;
}

.links{
margin-top:18px;
text-align:center;
}

.links a{
text-decoration:none;
font-weight:800;
color:#ea580c;
}

.forgot{
display:block;
margin-bottom:14px;
}

.error{
margin-top:6px;
font-size:13px;
color:#dc2626;
font-weight:700;
}

</style>

</head>
<body>

<div class="bg-circle bg1"></div>
<div class="bg-circle bg2"></div>

<div class="login-card">

<div class="logo-wrap">

@if(!empty($setting?->login_logo))
    <img src="{{ asset('storage/'.$setting->login_logo) }}">
@else
    <img src="{{ asset('images/logo-javajek.png') }}">
@endif

<h1>JavaJek</h1>
<p>Masuk ke akun Anda</p>

</div>

@if (session('status'))
<div style="margin-bottom:15px;color:green;font-weight:bold;">
{{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}">
@csrf

<div class="form-group">
<label>Email / Nomor HP</label>

<input
type="text"
name="email"
value="{{ old('email') }}"
class="form-control"
placeholder="Masukkan email atau nomor HP"
required>

@error('email')
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

<div class="remember">
<input type="checkbox" name="remember">
<span>Ingat saya</span>
</div>

<button class="btn-login">
🚀 MASUK SEKARANG
</button>

<div class="links">

@if(Route::has('password.request'))
<a href="{{ route('password.request') }}"
class="forgot">
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