@php
    $appSetting = \App\Models\AppSetting::first();

    $primaryColor = $appSetting->primary_color ?? '#f97316';
    $secondaryColor = $appSetting->secondary_color ?? '#fb923c';

    $adminLogo = !empty($appSetting->login_logo)
        ? asset('storage/'.$appSetting->login_logo)
        : asset('images/logo-javajek.png');

    $favicon = !empty($appSetting->favicon)
        ? asset('storage/'.$appSetting->favicon)
        : asset('favicon.png');

    $faviconVersion = optional($appSetting->updated_at)->timestamp ?? time();

    $appName = $appSetting->app_name ?? 'JavaJek';

    $pendingDrivers = \App\Models\DriverApplication::where('status','pending')->count();
    $pendingMerchants = \App\Models\Restaurant::where('status','pending')->count();

    $pendingOrders = \App\Models\Order::whereIn('status', [
        'searching_driver',
        'waiting_response'
    ])->count();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} - Admin</title>

    <link rel="icon" href="{{ $favicon }}?v={{ $faviconVersion }}">
    <link rel="shortcut icon" href="{{ $favicon }}?v={{ $faviconVersion }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}?v={{ $faviconVersion }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
:root{
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
    --soft-bg: {{ $secondaryColor }}22;
    --soft-bg-2: {{ $secondaryColor }}33;
    --dark:#1f2937;
    --sidebar-width:292px;
    --sidebar-mini:88px;
    --topbar-height:66px;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Segoe UI',Arial,sans-serif;
    background:linear-gradient(135deg,var(--soft-bg),var(--soft-bg-2),#ffffff);
    color:var(--dark);
}

body.menu-open{
    overflow:hidden;
}

.admin-topbar{
    height:var(--topbar-height);
    position:fixed;
    top:0;
    left:0;
    right:0;
    z-index:100002;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 18px;
    box-shadow:0 10px 26px rgba(15,23,42,.22);
}

.topbar-left{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:0;
}

.menu-toggle{
    width:44px;
    height:44px;
    border:none;
    border-radius:15px;
    background:rgba(255,255,255,.20);
    color:white;
    cursor:pointer;
    font-size:24px;
    font-weight:900;
    display:flex;
    align-items:center;
    justify-content:center;
}

.topbar-brand{
    display:flex;
    align-items:center;
    gap:10px;
    min-width:0;
}

.topbar-brand img{
    width:40px;
    height:40px;
    border-radius:14px;
    object-fit:cover;
    background:white;
    padding:2px;
}

.topbar-brand b{
    display:block;
    font-size:16px;
    font-weight:900;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.topbar-brand small{
    display:block;
    font-size:11px;
    opacity:.9;
}

.topbar-logout{
    width:44px;
    height:44px;
    border:none;
    border-radius:15px;
    background:rgba(255,255,255,.20);
    color:white;
    cursor:pointer;
    font-size:18px;
    font-weight:900;
}

.sidebar-overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.48);
    backdrop-filter:blur(3px);
    z-index:100000;
}

.admin-sidebar{
    position:fixed;
    top:var(--topbar-height);
    left:0;
    bottom:0;
    width:var(--sidebar-width);
    z-index:100001;
    background:linear-gradient(180deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:18px;
    overflow-y:auto;
    box-shadow:12px 0 35px rgba(15,23,42,.18);
    transition:.25s ease;
}

.admin-sidebar::-webkit-scrollbar{
    width:6px;
}

.admin-sidebar::-webkit-scrollbar-thumb{
    background:rgba(255,255,255,.35);
    border-radius:999px;
}

.sidebar-brand{
    display:flex;
    align-items:center;
    gap:12px;
    padding-bottom:16px;
    margin-bottom:16px;
    border-bottom:1px solid rgba(255,255,255,.18);
}

.sidebar-brand img{
    width:52px;
    height:52px;
    border-radius:18px;
    background:white;
    object-fit:cover;
    box-shadow:0 10px 22px rgba(0,0,0,.14);
    flex-shrink:0;
}

.sidebar-brand h1{
    margin:0;
    font-size:23px;
    line-height:1.1;
    font-weight:900;
}

.sidebar-brand p{
    margin:3px 0 0;
    font-size:12px;
    opacity:.9;
}

.sidebar-close{
    display:none;
    margin-left:auto;
    width:38px;
    height:38px;
    border:none;
    border-radius:14px;
    background:rgba(255,255,255,.18);
    color:white;
    font-size:22px;
    cursor:pointer;
}

.menu-title{
    font-size:10px;
    font-weight:900;
    opacity:.78;
    margin:18px 0 8px;
    text-transform:uppercase;
    letter-spacing:.9px;
    padding-left:4px;
}

.admin-sidebar a{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    color:white;
    text-decoration:none;
    padding:12px 13px;
    margin-bottom:7px;
    border-radius:16px;
    font-size:14px;
    font-weight:800;
    background:rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.10);
    transition:.2s;
}

.admin-sidebar a:hover{
    background:rgba(255,255,255,.22);
    transform:translateX(4px);
}

.admin-sidebar a.active-menu{
    background:white;
    color:var(--primary-color);
    transform:translateX(4px);
    box-shadow:0 10px 24px rgba(255,255,255,.22);
}

.menu-left{
    display:flex;
    align-items:center;
    gap:9px;
    min-width:0;
}

.menu-icon{
    width:24px;
    text-align:center;
    flex-shrink:0;
}

.menu-text{
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.menu-badge{
    background:white;
    color:var(--primary-color);
    font-size:11px;
    font-weight:900;
    padding:4px 9px;
    border-radius:999px;
    flex-shrink:0;
}

.active-menu .menu-badge{
    background:var(--primary-color);
    color:white;
}

.menu-badge-gray{
    background:#e2e8f0;
    color:#475569;
    padding:4px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
    flex-shrink:0;
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
    flex-shrink:0;
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
    margin-top:18px;
    box-shadow:0 10px 22px rgba(220,38,38,.25);
}

.admin-content{
    margin-left:var(--sidebar-width);
    padding:calc(var(--topbar-height) + 28px) 28px 28px;
    min-height:100vh;
    transition:.25s ease;
}

/* DESKTOP COLLAPSE */
body.sidebar-collapsed .admin-sidebar{
    width:var(--sidebar-mini);
    padding:18px 12px;
}

body.sidebar-collapsed .admin-content{
    margin-left:var(--sidebar-mini);
}

body.sidebar-collapsed .sidebar-brand{
    justify-content:center;
}

body.sidebar-collapsed .sidebar-brand img{
    width:50px;
    height:50px;
}

body.sidebar-collapsed .sidebar-brand div,
body.sidebar-collapsed .menu-title,
body.sidebar-collapsed .menu-text,
body.sidebar-collapsed .menu-badge,
body.sidebar-collapsed .menu-badge-gray,
body.sidebar-collapsed .menu-notif,
body.sidebar-collapsed .logout-btn{
    display:none;
}

body.sidebar-collapsed .admin-sidebar a{
    justify-content:center;
    padding:13px 10px;
}

body.sidebar-collapsed .menu-left{
    justify-content:center;
}

body.sidebar-collapsed .menu-icon{
    font-size:20px;
}

/* GLOBAL ADMIN UI */
.admin-toast{
    position:fixed;
    top:82px;
    left:50%;
    transform:translateX(-50%);
    z-index:999999;
    min-width:360px;
    max-width:560px;
    padding:18px 24px;
    border-radius:20px;
    color:white;
    font-size:17px;
    font-weight:900;
    text-align:center;
    box-shadow:0 22px 55px rgba(15,23,42,.25);
}

.admin-toast.success{background:#16a34a;}
.admin-toast.error{background:#dc2626;}

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
    color:var(--primary-color);
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

/* MOBILE */
@media(max-width:900px){
    .admin-topbar{
        padding:0 12px;
    }

    .topbar-brand b{
        font-size:14px;
    }

    .admin-sidebar{
        top:0;
        width:286px;
        max-width:86vw;
        transform:translateX(-110%);
        border-radius:0 28px 28px 0;
    }

    body.menu-open .admin-sidebar{
        transform:translateX(0);
    }

    body.menu-open .sidebar-overlay{
        display:block;
    }

    .sidebar-close{
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .admin-content{
        margin-left:0;
        padding:calc(var(--topbar-height) + 16px) 16px 16px;
    }

    body.sidebar-collapsed .admin-sidebar{
        width:286px;
        max-width:86vw;
        padding:18px;
    }

    body.sidebar-collapsed .admin-content{
        margin-left:0;
    }

    body.sidebar-collapsed .sidebar-brand div,
    body.sidebar-collapsed .menu-title,
    body.sidebar-collapsed .menu-text,
    body.sidebar-collapsed .menu-badge,
    body.sidebar-collapsed .menu-badge-gray,
    body.sidebar-collapsed .menu-notif,
    body.sidebar-collapsed .logout-btn{
        display:block;
    }

    body.sidebar-collapsed .admin-sidebar a{
        justify-content:space-between;
        padding:12px 13px;
    }

    body.sidebar-collapsed .menu-left{
        justify-content:flex-start;
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

@media(max-width:520px){
    .admin-content{
        padding:calc(var(--topbar-height) + 12px) 12px 12px;
    }

    .topbar-brand small{
        display:none;
    }

    .admin-sidebar{
        width:276px;
        max-width:88vw;
    }

    .admin-sidebar a{
        padding:11px 12px;
        font-size:13px;
    }
}
</style>
</head>

<body>

<header class="admin-topbar">
    <div class="topbar-left">
        <button type="button" class="menu-toggle" onclick="toggleAdminSidebar()">☰</button>

        <div class="topbar-brand">
            <img src="{{ $adminLogo }}" alt="{{ $appName }}">
            <div>
                <b>{{ $appName }}</b>
                <small>Admin Panel</small>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="topbar-logout">⎋</button>
    </form>
</header>

<div class="sidebar-overlay" onclick="closeMobileSidebar()"></div>

<aside class="admin-sidebar" id="adminSidebar">

    <div class="sidebar-brand">
        <img src="{{ $adminLogo }}" alt="{{ $appName }}">

        <div>
            <h1>{{ $appName }}</h1>
            <p>Admin Panel</p>
        </div>

        <button type="button" class="sidebar-close" onclick="closeMobileSidebar()">×</button>
    </div>

    <div class="menu-title">Utama</div>

    <a href="/admin" class="{{ request()->is('admin') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">📊</span>
            <span class="menu-text">Dashboard</span>
        </span>
    </a>

    <a href="/admin/finance" class="{{ request()->is('admin/finance*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">💰</span>
            <span class="menu-text">Keuangan</span>
        </span>
    </a>

    <a href="/admin/orders" class="{{ request()->is('admin/orders') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">📦</span>
            <span class="menu-text">Order Aktif</span>
        </span>

        @if($pendingOrders > 0)
            <span class="menu-notif">{{ $pendingOrders }}</span>
        @else
            <span class="menu-badge">Live</span>
        @endif
    </a>

    <a href="/admin/orders/history" class="{{ request()->is('admin/orders/history') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">📜</span>
            <span class="menu-text">History Order</span>
        </span>
        <span class="menu-badge-gray">Arsip</span>
    </a>

    <div class="menu-title">Pengaturan</div>

    <a href="/admin/app-appearance" class="{{ request()->is('admin/app-appearance*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">⚙️</span>
            <span class="menu-text">Pengaturan Aplikasi</span>
        </span>
    </a>

    <a href="/admin/delivery-setting" class="{{ request()->is('admin/delivery-setting*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">🚚</span>
            <span class="menu-text">Setting Ongkir</span>
        </span>
    </a>

    <a href="/admin/ride-setting" class="{{ request()->is('admin/ride-setting*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">🏍️</span>
            <span class="menu-text">Tarif Ride & Car</span>
        </span>
    </a>

    <div class="menu-title">Voucher</div>

    <a href="/admin/vouchers" class="{{ request()->is('admin/vouchers*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">🎁</span>
            <span class="menu-text">Voucher Promo</span>
        </span>
    </a>

    <a href="/admin/voucher-usages" class="{{ request()->is('admin/voucher-usages*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">📊</span>
            <span class="menu-text">Voucher Usage</span>
        </span>
        <span class="menu-badge-gray">Report</span>
    </a>

    <div class="menu-title">Merchant</div>

    <a href="/restaurants" class="{{ request()->is('restaurants*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">🏪</span>
            <span class="menu-text">Restoran</span>
        </span>
    </a>

    <a href="/foods" class="{{ request()->is('foods*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">🍔</span>
            <span class="menu-text">Menu Makanan</span>
        </span>
    </a>

    <a href="/admin/merchant-applications" class="{{ request()->is('admin/merchant-applications*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">📝</span>
            <span class="menu-text">Pengajuan Merchant</span>
        </span>

        @if($pendingMerchants > 0)
            <span class="menu-notif">{{ $pendingMerchants }}</span>
        @endif
    </a>

    <div class="menu-title">Driver</div>

    <a href="{{ route('admin.driver.monitor') }}" class="{{ request()->is('admin/driver-monitor*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">🗺️</span>
            <span class="menu-text">Monitoring Driver</span>
        </span>
    </a>

    <a href="/admin/drivers" class="{{ request()->is('admin/drivers') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">🛵</span>
            <span class="menu-text">Driver Aktif</span>
        </span>
    </a>

    <a href="/admin/driver-wallet" class="{{ request()->is('admin/driver-wallet*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">💰</span>
            <span class="menu-text">Saldo Driver</span>
        </span>
    </a>

    <a href="/admin/driver-applications" class="{{ request()->is('admin/driver-applications*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">📝</span>
            <span class="menu-text">Pengajuan Driver</span>
        </span>

        @if($pendingDrivers > 0)
            <span class="menu-notif">{{ $pendingDrivers }}</span>
        @endif
    </a>

    <a href="/admin/drivers/stopped" class="{{ request()->is('admin/drivers/stopped*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">⛔</span>
            <span class="menu-text">Driver Diberhentikan</span>
        </span>
    </a>

    <a href="/admin/drivers/penalty" class="{{ request()->is('admin/drivers/penalty*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">⚠️</span>
            <span class="menu-text">Driver Penalti</span>
        </span>
    </a>

    <div class="menu-title">User</div>

    <a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active-menu' : '' }}">
        <span class="menu-left">
            <span class="menu-icon">👤</span>
            <span class="menu-text">Data Pengguna</span>
        </span>
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>

</aside>

<main class="admin-content">

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

<script>
function toggleAdminSidebar(){
    if(window.innerWidth <= 900){
        document.body.classList.add('menu-open');
        return;
    }

    document.body.classList.toggle('sidebar-collapsed');
    localStorage.setItem(
        'javajek_admin_sidebar_collapsed',
        document.body.classList.contains('sidebar-collapsed') ? 'yes' : 'no'
    );
}

function closeMobileSidebar(){
    document.body.classList.remove('menu-open');
}

document.addEventListener('DOMContentLoaded', function(){
    if(window.innerWidth > 900){
        const collapsed = localStorage.getItem('javajek_admin_sidebar_collapsed');

        if(collapsed === 'yes'){
            document.body.classList.add('sidebar-collapsed');
        }
    }

    document.querySelectorAll('.admin-sidebar a').forEach(function(link){
        link.addEventListener('click', function(){
            if(window.innerWidth <= 900){
                closeMobileSidebar();
            }
        });
    });

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
});

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeMobileSidebar();
    }
});
</script>

</body>
</html>