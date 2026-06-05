<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>JavaJek Food</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@php
    $appSetting = \App\Models\AppSetting::first();

    $primaryColor = $appSetting->primary_color ?? '#f97316';
    $secondaryColor = $appSetting->secondary_color ?? '#fb923c';

    $customerLogo = !empty($appSetting->customer_logo)
        ? asset('storage/'.$appSetting->customer_logo)
        : asset('images/logo-javajek.png');

    $appName = $appSetting->app_name ?? 'JavaJek';
@endphp

<style>
:root{
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
    --text-color: {{ $primaryColor }};
    --soft-bg: #fff7ed;
    --soft-bg-2: #ffedd5;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    min-height:100vh;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,var(--soft-bg),var(--soft-bg-2),var(--soft-bg));
    color:#1f2937;
}

.hero{
    position:relative;
    overflow:hidden;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    border-radius:0 0 34px 34px;
    padding:22px 16px 24px;
    box-shadow:0 18px 40px rgba(15,23,42,.22);
}

.hero::before{
    content:"";
    position:absolute;
    width:190px;
    height:190px;
    border-radius:50%;
    background:rgba(255,255,255,.16);
    top:-80px;
    right:-60px;
}

.hero-inner{
    position:relative;
    z-index:2;
    max-width:1100px;
    margin:auto;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
}

.brand{
    display:flex;
    align-items:center;
    gap:12px;
}

.brand-icon{
    width:58px;
    height:58px;
    border-radius:20px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.brand-icon img{
    width:58px;
    height:58px;
    object-fit:cover;
}

.brand h1{
    margin:0;
    font-size:25px;
    font-weight:900;
}

.brand p{
    margin:3px 0 0;
    font-size:13px;
    opacity:.95;
}

.hamburger{
    width:48px;
    height:48px;
    border:none;
    border-radius:18px;
    background:rgba(255,255,255,.2);
    color:white;
    font-size:25px;
    cursor:pointer;
    backdrop-filter:blur(10px);
}

.user-menu-wrap{
    position:relative;
}

.user-dropdown{
    position:fixed;
    top:92px;
    right:22px;
    width:270px;
    max-height:calc(100vh - 120px);
    overflow-y:auto;
    background:rgba(255,255,255,.98);
    backdrop-filter:blur(18px);
    border-radius:26px;
    padding:14px;
    box-shadow:0 20px 45px rgba(15,23,42,.22);
    display:none;
    z-index:99999;
}

.user-dropdown.show{
    display:block;
    animation:menuPop .22s ease;
}

@keyframes menuPop{
    from{
        opacity:0;
        transform:translateY(-8px) scale(.96);
    }
    to{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}

.user-info{
    padding:12px;
    border-bottom:1px solid #fed7aa;
    margin-bottom:10px;
}

.user-info b{
    display:block;
    color:var(--text-color);
    font-size:16px;
}

.user-info small{
    color:#6b7280;
    font-size:12px;
}

.menu-item{
    width:100%;
    display:flex;
    align-items:center;
    gap:10px;
    padding:13px 14px;
    border-radius:16px;
    text-decoration:none;
    color:var(--text-color);
    font-weight:900;
    background:white;
    margin-bottom:7px;
    border:none;
    cursor:pointer;
    text-align:left;
}

.menu-item:hover{
    background:var(--soft-bg);
}

.logout-btn{
    background:#fee2e2;
    color:#b91c1c;
}

.search-box{
    margin-top:18px;
    background:white;
    border-radius:22px;
    padding:12px 15px;
    display:flex;
    gap:10px;
    align-items:center;
    box-shadow:0 12px 25px rgba(0,0,0,.12);
}

.search-box input{
    border:none;
    outline:none;
    width:100%;
    font-size:15px;
    background:none;
}

.wallet-card{
    margin-top:18px;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    border-radius:30px;
    padding:20px;
    box-shadow:0 18px 35px rgba(15,23,42,.20);
    position:relative;
    overflow:hidden;
}

.wallet-card::after{
    content:"";
    position:absolute;
    width:150px;
    height:150px;
    border-radius:50%;
    background:rgba(255,255,255,.13);
    right:-45px;
    top:-45px;
}

.wallet-content{
    position:relative;
    z-index:2;
}

.wallet-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.wallet-label{
    font-size:14px;
    opacity:.92;
}

.wallet-balance{
    font-size:30px;
    font-weight:900;
    margin-top:6px;
}

.eye-btn{
    border:none;
    width:44px;
    height:44px;
    border-radius:50%;
    background:rgba(255,255,255,.22);
    color:white;
    font-size:20px;
    cursor:pointer;
}

.wallet-actions{
    margin-top:18px;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
}

.wallet-actions a{
    text-align:center;
    padding:12px;
    border-radius:17px;
    text-decoration:none;
    font-weight:900;
}

.topup-btn{
    background:white;
    color:var(--primary-color);
}

.withdraw-btn{
    background:rgba(255,255,255,.2);
    color:white;
    border:1px solid rgba(255,255,255,.28);
}

.quick-menu{
    margin-top:20px;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
}

.quick-menu a{
    background:rgba(255,255,255,.94);
    border:1px solid rgba(255,255,255,.7);
    border-radius:24px;
    padding:15px 8px;
    text-align:center;
    text-decoration:none;
    box-shadow:0 10px 24px rgba(15,23,42,.08);
}

.quick-icon{
    font-size:32px;
    line-height:1;
}

.quick-title{
    margin-top:8px;
    color:var(--text-color);
    font-size:13px;
    font-weight:900;
}

main{
    max-width:1100px;
    margin:auto;
    padding:22px 14px 100px;
}

.food-card{
    background:rgba(255,255,255,.94);
    border:1px solid rgba(255,255,255,.75);
    border-radius:24px;
    padding:18px;
    box-shadow:0 12px 30px rgba(15,23,42,.08);
    margin-bottom:18px;
}

.alert-success{
    border-left:6px solid #16a34a;
    color:#166534;
    font-weight:800;
}

.alert-error{
    border-left:6px solid #dc2626;
    color:#991b1b;
    font-weight:800;
}

.bottom-nav{
    position:fixed;
    left:14px;
    right:14px;
    bottom:14px;
    z-index:999;
    background:rgba(255,255,255,.97);
    border:1px solid rgba(255,255,255,.85);
    border-radius:28px;
    box-shadow:0 18px 45px rgba(15,23,42,.22);
    display:none;
    grid-template-columns:repeat(5,1fr);
    overflow:hidden;
    backdrop-filter:blur(14px);
}

.bottom-nav a{
    position:relative;
    text-align:center;
    padding:10px 4px 9px;
    font-size:11px;
    font-weight:900;
    color:var(--text-color);
    text-decoration:none;
}

.bottom-nav a.active{
    color:var(--primary-color);
}

.bottom-nav span{
    display:block;
    font-size:22px;
    line-height:22px;
    margin-bottom:3px;
}

.nav-dot{
    position:absolute;
    top:7px;
    right:25%;
    background:#dc2626;
    color:white;
    font-size:10px;
    min-width:17px;
    height:17px;
    border-radius:999px;
    display:none;
    align-items:center;
    justify-content:center;
    font-style:normal;
}

@media(max-width:640px){
    .hero{
        border-radius:0 0 28px 28px;
    }

    .brand h1{
        font-size:22px;
    }

    .brand-icon{
        width:48px;
        height:48px;
        border-radius:16px;
    }

    .brand-icon img{
        width:48px;
        height:48px;
    }

    .quick-menu{
        gap:10px;
    }

    .quick-menu a{
        border-radius:20px;
        padding:13px 6px;
    }

    main{
        padding:18px 12px 96px;
    }

    .bottom-nav{
        display:grid;
    }

    .user-dropdown{
        top:78px;
        right:12px;
        left:12px;
        width:auto;
        max-height:calc(100vh - 100px);
    }
}
</style>
</head>

<body>

<div class="hero">
    <div class="hero-inner">

        <div class="topbar">
            <div class="brand">
                <div class="brand-icon">
                    <img src="{{ $customerLogo }}" alt="{{ $appName }}">
                </div>

                <div>
                    <h1 id="greetingText">{{ $appName }}</h1>
                    <p>Halo, {{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="user-menu-wrap">
                <button type="button" class="hamburger" id="menuButton" onclick="toggleUserMenu()">☰</button>

                <div id="userDropdown" class="user-dropdown">
                    <div class="user-info">
                        <b>{{ auth()->user()->name }}</b>
                        <small>{{ auth()->user()->email }}</small>
                    </div>

                    <a href="/profile" class="menu-item">👤 Profile</a>
<a href="/driver" class="menu-item">🛵 Driver</a>
<a href="/merchant" class="menu-item">🏪 Merchant</a>
<a href="/my-orders" class="menu-item">📦 Pesanan Saya</a>
<a href="/customer/vouchers" class="menu-item">🎁 Voucher Saya</a>
<a href="/cart" class="menu-item">🛒 Keranjang</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="menu-item logout-btn">🚪 Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <form action="/" method="GET" class="search-box">
            <span>🔍</span>
            <input type="text" name="search" placeholder="Cari makanan favoritmu...">
        </form>

        <div class="wallet-card">
            <div class="wallet-content">
                <div class="wallet-top">
                    <div>
                        <div class="wallet-label">💳 Saldo JavaPay</div>
                        <div id="saldoText" class="wallet-balance">********</div>
                    </div>

                    <button type="button" onclick="toggleSaldo()" class="eye-btn">👁️</button>
                </div>

                <div class="wallet-actions">
                    <a href="/topup" class="topup-btn">➕ Topup</a>
                    <a href="/withdraw" class="withdraw-btn">💸 Tarik</a>
                </div>
            </div>
        </div>

        <div class="quick-menu">
            <a href="/">
                <div class="quick-icon">🍔</div>
                <div class="quick-title">J-Food</div>
            </a>

            <a href="{{ route('ojek.page') }}">
                <div class="quick-icon">🏍️</div>
                <div class="quick-title">J-Ride</div>
            </a>

            <a href="/car">
                <div class="quick-icon">🚗</div>
                <div class="quick-title">J-Car</div>
            </a>

            <a href="/my-orders">
                <div class="quick-icon">📦</div>
                <div class="quick-title">Pesanan</div>
            </a>
        </div>

    </div>
</div>

<main>
    @if(session('success'))
        <div class="food-card alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="food-card alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<div class="bottom-nav">
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
        <span>🏠</span>
        Home
    </a>

    <a href="/cart" class="{{ request()->is('cart') ? 'active' : '' }}">
        <span>🛒</span>
        Cart
    </a>

    <a href="/my-orders" class="{{ request()->is('my-orders*') ? 'active' : '' }}">
        <span>📦</span>
        Order
    </a>

    <a href="/driver" class="{{ request()->is('driver*') ? 'active' : '' }}">
        <i id="driverNotif" class="nav-dot">0</i>
        <span>🛵</span>
        Driver
    </a>

    <a href="/merchant" class="{{ request()->is('merchant*') ? 'active' : '' }}">
        <i id="merchantNotif" class="nav-dot">0</i>
        <span>🏪</span>
        Merchant
    </a>
</div>

<script>
let saldoVisible = false;

function toggleSaldo(){
    const saldo = document.getElementById('saldoText');
    saldoVisible = !saldoVisible;
    saldo.innerText = saldoVisible ? 'Rp 0' : '********';
}

function updateGreeting(){
    const hour = new Date().getHours();
    const greeting = document.getElementById('greetingText');

    if (!greeting) return;

    if (hour >= 5 && hour < 10) {
        greeting.innerText = '☀️ Selamat Pagi';
    } else if (hour >= 10 && hour < 15) {
        greeting.innerText = '🌤️ Selamat Siang';
    } else if (hour >= 15 && hour < 18) {
        greeting.innerText = '🌇 Selamat Sore';
    } else {
        greeting.innerText = '🌙 Selamat Malam';
    }
}

function toggleUserMenu(){
    document.getElementById('userDropdown')?.classList.toggle('show');
}

window.addEventListener('click', function(e){
    const dropdown = document.getElementById('userDropdown');
    const button = document.getElementById('menuButton');

    if (!dropdown || !button) return;

    if (!dropdown.contains(e.target) && !button.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

function setBadge(id, count){
    const badge = document.getElementById(id);

    if (!badge) return;

    if (count > 0) {
        badge.innerText = count;
        badge.style.display = 'inline-flex';
    } else {
        badge.style.display = 'none';
    }
}

function loadNotifications(){
    fetch('/notifications/count')
        .then(res => res.json())
        .then(data => {
            setBadge('driverNotif', parseInt(data.driver ?? 0));
            setBadge('merchantNotif', parseInt(data.merchant ?? 0));
        })
        .catch(err => console.log('Notif layout error:', err));
}

updateGreeting();
loadNotifications();
setInterval(loadNotifications, 5000);
</script>

</body>
</html>