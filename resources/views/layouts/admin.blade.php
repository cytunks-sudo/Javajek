<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaJek Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@php
    $appSetting = \App\Models\AppSetting::first();

    $primaryColor = $appSetting->primary_color ?? '#f97316';
    $secondaryColor = $appSetting->secondary_color ?? '#fb923c';

    $adminLogo = !empty($appSetting->login_logo)
        ? asset('storage/'.$appSetting->login_logo)
        : asset('images/logo-javajek.png');

    $appName = $appSetting->app_name ?? 'JavaJek';

    $pendingDrivers = \App\Models\DriverApplication::where('status','pending')->count();
    $pendingMerchants = \App\Models\Restaurant::where('status','pending')->count();

    $pendingOrders = \App\Models\Order::whereIn('status',[
        'searching_driver',
        'waiting_response'
    ])->count();
@endphp

<style>
:root{
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
    --text-color: {{ $primaryColor }};
    --soft-bg: {{ $secondaryColor }}22;
    --soft-bg-2: {{ $secondaryColor }}33;
    --white:#ffffff;
    --dark:#1f2937;
}

*{box-sizing:border-box;}

body{
    margin:0;
    font-family:'Segoe UI',Arial,sans-serif;
    background:linear-gradient(135deg,var(--soft-bg),var(--soft-bg-2),#ffffff);
    color:var(--dark);
}

.wrapper{
    display:flex;
    min-height:100vh;
}

.sidebar{
    width:290px;
    background:linear-gradient(180deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:22px;
    position:fixed;
    inset:0 auto 0 0;
    overflow-y:auto;
    box-shadow:12px 0 35px rgba(15,23,42,.18);
}

.brand{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:26px;
}

.brand-logo{
    width:52px;
    height:52px;
    border-radius:18px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    box-shadow:0 10px 22px rgba(0,0,0,.14);
}

.brand-logo img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.brand-text h1{
    margin:0;
    font-size:25px;
    font-weight:900;
}

.brand-text p{
    margin:2px 0 0;
    font-size:12px;
    opacity:.9;
}

.menu-title{
    font-size:11px;
    font-weight:900;
    opacity:.85;
    margin:22px 0 8px;
    text-transform:uppercase;
    letter-spacing:.8px;
}

.sidebar a{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    color:white;
    text-decoration:none;
    padding:13px 14px;
    margin-bottom:8px;
    border-radius:16px;
    font-size:15px;
    font-weight:800;
    background:rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.10);
    transition:.2s;
}

.sidebar a:hover{
    background:rgba(255,255,255,.22);
    transform:translateX(4px);
}

.sidebar a.active-menu{
    background:white;
    color:var(--primary-color);
    transform:translateX(4px);
    box-shadow:0 10px 24px rgba(255,255,255,.22);
}

.sidebar a.active-menu .menu-badge{
    background:var(--primary-color);
    color:white;
}

.menu-badge{
    background:white;
    color:var(--primary-color);
    font-size:11px;
    font-weight:900;
    padding:4px 9px;
    border-radius:999px;
}

.menu-badge-gray{
    background:#e2e8f0;
    color:#475569;
    padding:4px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
}

.menu-notif{
    min-width:22px;
    height:22px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#ef4444;
    color:white;
    font-size:11px;
    font-weight:900;
    border-radius:999px;
    box-shadow:0 4px 10px rgba(239,68,68,.4);
}

.logout-btn{
    width:100%;
    background:#dc2626;
    color:white;
    padding:14px;
    border:none;
    border-radius:16px;
    font-weight:900;
    cursor:pointer;
    margin-top:22px;
    box-shadow:0 10px 22px rgba(220,38,38,.25);
}

.content{
    margin-left:290px;
    flex:1;
    padding:28px;
}

.admin-toast{
    position:fixed;
    top:28px;
    left:50%;
    transform:translateX(-50%);
    z-index:999999;
    min-width:360px;
    max-width:560px;
    padding:20px 28px;
    border-radius:22px;
    color:white;
    font-size:18px;
    font-weight:900;
    text-align:center;
    box-shadow:0 22px 55px rgba(15,23,42,.25);
    animation:adminToastDrop .45s ease;
}

.admin-toast.success{background:#16a34a;}
.admin-toast.error{background:#dc2626;}

@keyframes adminToastDrop{
    from{transform:translate(-50%,-90px);opacity:0;}
    to{transform:translate(-50%,0);opacity:1;}
}

.card-box{
    background:white;
    border-radius:24px;
    padding:24px;
    box-shadow:0 12px 30px rgba(15,23,42,.08);
    overflow:hidden;
}

.btn-primary{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:10px 15px;
    border-radius:13px;
    text-decoration:none;
    border:none;
    cursor:pointer;
    display:inline-block;
    font-weight:900;
    box-shadow:0 8px 18px rgba(15,23,42,.18);
}

.btn-danger{
    background:#dc2626;
    color:white;
    padding:10px 15px;
    border-radius:13px;
    text-decoration:none;
    border:none;
    cursor:pointer;
    display:inline-block;
    font-weight:900;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:var(--soft-bg);
    color:var(--text-color);
    font-size:13px;
    text-transform:uppercase;
}

table th,
table td{
    padding:14px;
    border-bottom:1px solid #f1f1f1;
    text-align:left;
    vertical-align:top;
}

.badge-open,
.badge-active{
    background:#dcfce7;
    color:#166534;
    padding:6px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.badge-close,
.badge-rejected{
    background:#fee2e2;
    color:#991b1b;
    padding:6px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.badge-pending{
    background:#fef3c7;
    color:#92400e;
    padding:6px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.merchant-photo{
    width:74px;
    height:74px;
    border-radius:18px;
    object-fit:cover;
    background:#fed7aa;
}

.map-box{
    width:100%;
    height:220px;
    border-radius:18px;
    overflow:hidden;
    margin-top:12px;
    border:1px solid #fed7aa;
}

@media(max-width:900px){
    .sidebar{
        position:relative;
        width:100%;
        min-height:auto;
        border-radius:0 0 28px 28px;
    }

    .wrapper{
        display:block;
    }

    .content{
        margin-left:0;
        padding:16px;
    }

    table{
        min-width:760px;
    }

    .card-box{
        overflow-x:auto;
    }

    .admin-toast{
        min-width:auto;
        width:calc(100% - 32px);
        max-width:calc(100% - 32px);
        font-size:15px;
        padding:16px 18px;
    }
}
</style>
</head>

<body>

<div class="wrapper">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-logo">
                <img src="{{ $adminLogo }}" alt="{{ $appName }}">
            </div>

            <div class="brand-text">
                <h1>{{ $appName }}</h1>
                <p>Admin Panel</p>
            </div>
        </div>

        <div class="menu-title">Utama</div>

        <a href="/admin" class="{{ request()->is('admin') ? 'active-menu' : '' }}">
            <span>📊 Dashboard</span>
        </a>

        <a href="/admin/finance" class="{{ request()->is('admin/finance*') ? 'active-menu' : '' }}">
            <span>💰 Keuangan</span>
        </a>

        <a href="/admin/orders" class="{{ request()->is('admin/orders') ? 'active-menu' : '' }}">
            <span>📦 Order Aktif</span>

            @if($pendingOrders > 0)
                <span class="menu-notif">{{ $pendingOrders }}</span>
            @else
                <span class="menu-badge">Live</span>
            @endif
        </a>

        <a href="/admin/orders/history" class="{{ request()->is('admin/orders/history') ? 'active-menu' : '' }}">
            <span>📜 History Order</span>
            <span class="menu-badge-gray">Arsip</span>
        </a>

        <div class="menu-title">Pengaturan</div>

        <a href="/admin/app-appearance" class="{{ request()->is('admin/app-appearance*') ? 'active-menu' : '' }}">
            <span>⚙️ Pengaturan Aplikasi</span>
        </a>

        <a href="/admin/delivery-setting" class="{{ request()->is('admin/delivery-setting*') ? 'active-menu' : '' }}">
            <span>🚚 Setting Ongkir</span>
        </a>

        <a href="/admin/ride-setting" class="{{ request()->is('admin/ride-setting*') ? 'active-menu' : '' }}">
            <span>🏍️ Tarif Ride & Car</span>
        </a>

        <div class="menu-title">Voucher</div>

        <a href="/admin/vouchers" class="{{ request()->is('admin/vouchers*') ? 'active-menu' : '' }}">
            <span>🎁 Voucher Promo</span>
        </a>

        <a href="/admin/voucher-usages" class="{{ request()->is('admin/voucher-usages*') ? 'active-menu' : '' }}">
            <span>📊 Voucher Usage</span>
            <span class="menu-badge-gray">Report</span>
        </a>

        <div class="menu-title">Merchant</div>

        <a href="/restaurants" class="{{ request()->is('restaurants*') ? 'active-menu' : '' }}">
            <span>🏪 Restoran</span>
        </a>

        <a href="/foods" class="{{ request()->is('foods*') ? 'active-menu' : '' }}">
            <span>🍔 Menu Makanan</span>
        </a>

        <a href="/admin/merchant-applications" class="{{ request()->is('admin/merchant-applications*') ? 'active-menu' : '' }}">
            <span>📝 Pengajuan Merchant</span>

            @if($pendingMerchants > 0)
                <span class="menu-notif">{{ $pendingMerchants }}</span>
            @endif
        </a>

        <div class="menu-title">Driver</div>

        <a href="{{ route('admin.driver.monitor') }}" class="{{ request()->is('admin/driver-monitor*') ? 'active-menu' : '' }}">
            <span>🗺️ Monitoring Driver</span>
        </a>

        <a href="/admin/drivers" class="{{ request()->is('admin/drivers') ? 'active-menu' : '' }}">
            <span>🛵 Driver Aktif</span>
        </a>

        <a href="/admin/driver-wallet" class="{{ request()->is('admin/driver-wallet*') ? 'active-menu' : '' }}">
            <span>💰 Saldo Driver</span>
        </a>

        <a href="/admin/driver-applications" class="{{ request()->is('admin/driver-applications*') ? 'active-menu' : '' }}">
            <span>📝 Pengajuan Driver</span>

            @if($pendingDrivers > 0)
                <span class="menu-notif">{{ $pendingDrivers }}</span>
            @endif
        </a>

        <a href="/admin/drivers/stopped" class="{{ request()->is('admin/drivers/stopped*') ? 'active-menu' : '' }}">
            <span>⛔ Driver Diberhentikan</span>
        </a>

        <a href="/admin/drivers/penalty" class="{{ request()->is('admin/drivers/penalty*') ? 'active-menu' : '' }}">
            <span>⚠️ Driver Penalti</span>
        </a>

        <div class="menu-title">User</div>

        <a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active-menu' : '' }}">
            <span>👤 Data Pengguna</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>

    </aside>

    <main class="content">

        @if(session('success'))
            <div id="toastSuccess" class="admin-toast success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="toastError" class="admin-toast error">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

</div>

<script>
setTimeout(function(){
    let success = document.getElementById('toastSuccess');
    let error = document.getElementById('toastError');

    if(success){
        success.remove();
    }

    if(error){
        error.remove();
    }
},3000);
</script>

</body>
</html>