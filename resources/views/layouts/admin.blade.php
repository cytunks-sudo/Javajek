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
        body{
            margin:0;
            font-family:Arial,sans-serif;
            background:linear-gradient(135deg,#fff7f2,#ffe7d6,#fff0e5);
        }

        .wrapper{
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:280px;
            background:linear-gradient(180deg,#ff5a00,#ff7b00,#ff914d);
            color:white;
            padding:22px;
            position:fixed;
            top:0;
            bottom:0;
            left:0;
            overflow-y:auto;
        }

        .brand{
            font-size:28px;
            font-weight:800;
            margin-bottom:25px;
        }

        .menu-title{
            font-size:12px;
            font-weight:bold;
            opacity:.75;
            margin:22px 0 8px;
            text-transform:uppercase;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            padding:11px 14px;
            margin-bottom:6px;
            border-radius:12px;
            font-size:15px;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,.18);
        }

        .logout-btn{
            width:100%;
            background:#dc2626;
            color:white;
            padding:12px;
            border:none;
            border-radius:12px;
            font-weight:bold;
            cursor:pointer;
            margin-top:20px;
        }

        .content{
            margin-left:320px;
            flex:1;
            padding:30px;
        }

        .card-box{
            background:white;
            border-radius:22px;
            padding:24px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
        }

        .btn-primary{
            background:#ff5a00;
            color:white;
            padding:10px 15px;
            border-radius:12px;
            text-decoration:none;
            border:none;
            cursor:pointer;
            display:inline-block;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            padding:14px;
            border-bottom:1px solid #eee;
            text-align:left;
        }

        .badge-open{
            background:#dcfce7;
            color:#166534;
            padding:5px 10px;
            border-radius:999px;
        }

        .badge-close{
            background:#fee2e2;
            color:#991b1b;
            padding:5px 10px;
            border-radius:999px;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">

        <div class="brand">JavaJek</div>

        <div class="menu-title">Utama</div>
        <a href="/admin">Dashboard</a>
        <a href="/admin/orders">Order</a>

        <div class="menu-title">Restoran & Menu</div>
        <a href="/restaurants">Restoran</a>
        <a href="/foods">Menu Makanan</a>

        <div class="menu-title">Driver</div>
        <a href="/admin/drivers">Driver Aktif</a>
        <a href="/admin/driver-applications">Pengajuan Driver</a>
        <a href="/admin/drivers/stopped">Driver Diberhentikan</a>
        <a href="/admin/drivers/penalty">Driver Penalti</a>

        <div class="menu-title">Merchant</div>
        <a href="/admin/merchant-applications">Pengajuan Merchant</a>

        <div class="menu-title">User</div>
        <a href="/admin/users">User Approval</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>

    </aside>

    <main class="content">
        @yield('content')
    </main>

</div>

</body>
</html>