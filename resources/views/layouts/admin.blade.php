<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaJek Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        *{box-sizing:border-box}

        body{
            margin:0;
            font-family:'Segoe UI',Arial,sans-serif;
            background:linear-gradient(135deg,#fff7ed,#ffedd5,#fff7ed);
            color:#1f2937;
        }

        .wrapper{
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:290px;
            background:linear-gradient(180deg,#f97316,#fb923c,#fdba74);
            color:white;
            padding:22px;
            position:fixed;
            inset:0 auto 0 0;
            overflow-y:auto;
            box-shadow:12px 0 35px rgba(249,115,22,.22);
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:26px;
        }

        .brand-logo{
            width:48px;
            height:48px;
            border-radius:18px;
            background:white;
            color:#f97316;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:25px;
            box-shadow:0 10px 22px rgba(0,0,0,.14);
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
            opacity:.75;
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
            padding:12px 14px;
            margin-bottom:7px;
            border-radius:15px;
            font-size:15px;
            font-weight:700;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.08);
            transition:.2s;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,.22);
            transform:translateX(4px);
        }

        .menu-badge{
            background:white;
            color:#f97316;
            font-size:11px;
            font-weight:900;
            padding:3px 8px;
            border-radius:999px;
        }

        .logout-btn{
            width:100%;
            background:#dc2626;
            color:white;
            padding:13px;
            border:none;
            border-radius:15px;
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

        .top-content{
            background:white;
            border-radius:24px;
            padding:18px 22px;
            margin-bottom:22px;
            box-shadow:0 12px 30px rgba(15,23,42,.08);
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:15px;
            flex-wrap:wrap;
        }

        .top-content h2{
            margin:0;
            font-size:22px;
            font-weight:900;
            color:#c2410c;
        }

        .top-content p{
            margin:3px 0 0;
            font-size:13px;
            color:#6b7280;
        }

        .card-box{
            background:white;
            border-radius:24px;
            padding:24px;
            box-shadow:0 12px 30px rgba(15,23,42,.08);
            overflow:hidden;
        }

        .btn-primary{
            background:linear-gradient(135deg,#f97316,#fb923c);
            color:white;
            padding:10px 15px;
            border-radius:13px;
            text-decoration:none;
            border:none;
            cursor:pointer;
            display:inline-block;
            font-weight:800;
            box-shadow:0 8px 18px rgba(249,115,22,.22);
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
            font-weight:800;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#fff7ed;
            color:#9a3412;
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

        .alert-success{
            background:#dcfce7;
            color:#166534;
            border-left:6px solid #16a34a;
            padding:14px 16px;
            border-radius:16px;
            margin-bottom:18px;
            font-weight:800;
        }

        .alert-error{
            background:#fee2e2;
            color:#991b1b;
            border-left:6px solid #dc2626;
            padding:14px 16px;
            border-radius:16px;
            margin-bottom:18px;
            font-weight:800;
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
        }
    </style>
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-logo">🍜</div>
            <div class="brand-text">
                <h1>JavaJek</h1>
                <p>Admin Panel</p>
            </div>
        </div>

        <div class="menu-title">Utama</div>
        <a href="/admin">📊 Dashboard</a>
        <a href="/admin/orders">
            <span>📦 Order</span>
            <span class="menu-badge">Live</span>
        </a>
        <a href="/admin/delivery-setting"
   class="{{ request()->is('admin/delivery-setting') ? 'active' : '' }}">
    🚚 Setting Ongkir
</a>
        <div class="menu-title">Restoran & Menu</div>
        <a href="/restaurants">🏪 Restoran</a>
        <a href="/foods">🍔 Menu Makanan</a>

        <div class="menu-title">Driver</div>
        <a href="/admin/drivers">🛵 Driver Aktif</a>
        <a href="/admin/driver-applications">📝 Pengajuan Driver</a>
        <a href="/admin/drivers/stopped">⛔ Driver Diberhentikan</a>
        <a href="/admin/drivers/penalty">⚠️ Driver Penalti</a>

        <div class="menu-title">Merchant</div>
        <a href="/admin/merchant-applications">📝 Pengajuan Merchant</a>

        <div class="menu-title">User</div>
        <a href="/admin/users">👤 User Approval</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>

    </aside>

    <main class="content">

        <div class="top-content">
            <div>
                <h2>Admin JavaJek</h2>
                <p>Kelola order, merchant, driver, restoran, dan user.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')

    </main>

</div>

</body>
</html>