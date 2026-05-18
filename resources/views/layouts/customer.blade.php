<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>JavaJek Food</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            background:linear-gradient(135deg,#fff7ed,#ffedd5,#fff7ed);
            min-height:100vh;
            font-family:'Segoe UI',sans-serif;
            color:#1f2937;
        }

        .hero{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg,#f97316,#fb923c,#fdba74);
            color:white;
            border-radius:0 0 36px 36px;
            padding:28px 18px 24px;
            box-shadow:0 18px 40px rgba(249,115,22,.35);
        }

        .hero::before{
            content:"";
            position:absolute;
            width:180px;
            height:180px;
            border-radius:50%;
            background:rgba(255,255,255,.18);
            top:-70px;
            right:-50px;
        }

        .hero::after{
            content:"";
            position:absolute;
            width:110px;
            height:110px;
            border-radius:50%;
            background:rgba(255,255,255,.14);
            bottom:-35px;
            left:25px;
        }

        .hero-inner{
            position:relative;
            z-index:2;
            max-width:1100px;
            margin:auto;
        }

        .topbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            flex-wrap:wrap;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .brand-icon{
            width:48px;
            height:48px;
            border-radius:18px;
            background:white;
            color:#f97316;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:26px;
            box-shadow:0 10px 25px rgba(0,0,0,.15);
        }

        .brand h1{
            margin:0;
            font-size:28px;
            font-weight:900;
            letter-spacing:-.5px;
        }

        .brand p{
            margin:2px 0 0;
            font-size:14px;
            opacity:.95;
        }

        .nav-menu{
            margin-top:22px;
            display:flex;
            gap:10px;
            overflow-x:auto;
            padding-bottom:4px;
            scrollbar-width:none;
        }

        .nav-menu::-webkit-scrollbar{
            display:none;
        }

        .nav-link,
        .logout-btn{
            white-space:nowrap;
            color:white;
            font-weight:800;
            text-decoration:none;
            background:rgba(255,255,255,.18);
            border:1px solid rgba(255,255,255,.25);
            padding:10px 14px;
            border-radius:999px;
            backdrop-filter:blur(10px);
            transition:.2s;
            font-size:14px;
        }

        .nav-link:hover,
        .logout-btn:hover{
            background:white;
            color:#f97316;
            transform:translateY(-2px);
        }

        .logout-form{
            display:inline;
            margin:0;
        }

        .logout-btn{
            cursor:pointer;
        }

        main{
            padding:22px 16px 90px;
            max-width:1100px;
            margin:auto;
        }

        .food-card{
            background:rgba(255,255,255,.92);
            border:1px solid rgba(255,255,255,.75);
            border-radius:24px;
            padding:18px;
            box-shadow:0 12px 30px rgba(15,23,42,.08);
            margin-bottom:18px;
        }

        .btn-order{
            background:linear-gradient(135deg,#f97316,#fb923c);
            color:white;
            padding:11px 16px;
            border-radius:16px;
            font-weight:800;
            display:inline-block;
            text-decoration:none;
            border:none;
            box-shadow:0 10px 22px rgba(249,115,22,.28);
        }

        .btn-order:hover{
            transform:translateY(-2px);
            box-shadow:0 14px 28px rgba(249,115,22,.35);
        }

        .form-control{
            width:100%;
            border:1px solid #fed7aa;
            border-radius:16px;
            padding:12px 14px;
            outline:none;
            background:#fff;
            margin-top:6px;
        }

        .form-control:focus{
            border-color:#f97316;
            box-shadow:0 0 0 4px rgba(249,115,22,.12);
        }

        .bottom-nav{
            position:fixed;
            left:12px;
            right:12px;
            bottom:12px;
            z-index:50;
            background:white;
            border-radius:24px;
            box-shadow:0 18px 40px rgba(15,23,42,.18);
            display:none;
            grid-template-columns:repeat(4,1fr);
            overflow:hidden;
        }

        .bottom-nav a{
            text-align:center;
            padding:11px 5px;
            font-size:12px;
            font-weight:800;
            color:#9a3412;
            text-decoration:none;
        }

        .bottom-nav span{
            display:block;
            font-size:20px;
            line-height:20px;
            margin-bottom:3px;
        }

        @media(max-width:640px){
            .hero{
                padding:22px 15px 20px;
                border-radius:0 0 28px 28px;
            }

            .brand h1{
                font-size:24px;
            }

            .brand p{
                font-size:13px;
            }

            .brand-icon{
                width:44px;
                height:44px;
                border-radius:16px;
            }

            .nav-menu{
                gap:8px;
            }

            .nav-link,
            .logout-btn{
                font-size:13px;
                padding:9px 12px;
            }

            main{
                padding:18px 12px 95px;
            }

            .food-card{
                border-radius:20px;
                padding:15px;
            }

            .bottom-nav{
                display:grid;
            }
        }

.notif-badge{
    background:#dc2626;
    color:white;
    font-size:11px;
    font-weight:900;
    padding:2px 7px;
    border-radius:999px;
    margin-left:5px;
}
    </style>
</head>

<body>

<div class="hero">
    <div class="hero-inner">

        <div class="topbar">
            <div class="brand">
                <div class="brand-icon">🍜</div>
                <div>
                    <h1>JavaJek Food</h1>
                    <p>Pesan makanan favoritmu dengan cepat</p>
                </div>
            </div>
        </div>

        <div class="nav-menu">
            <a href="/" class="nav-link">🏠 Home</a>
            <a href="/cart" class="nav-link">🛒 Keranjang</a>
            <a href="/my-orders" class="nav-link">📦 Pesanan Saya</a>
            <a href="/driver" class="nav-link">
    🛵 Driver
    <span id="driverNotif" class="notif-badge" style="display:none;">0</span>
</a>

<a href="/merchant" class="nav-link">
    🏪 Merchant
    <span id="merchantNotif" class="notif-badge" style="display:none;">0</span>
</a>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    Logout
                </button>
            </form>
        </div>

    </div>
</div>

<main>
    @if(session('success'))
        <div class="food-card" style="border-left:6px solid #16a34a;color:#166534;font-weight:700;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="food-card" style="border-left:6px solid #dc2626;color:#991b1b;font-weight:700;">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

<div class="bottom-nav">
    <a href="/">
        <span>🏠</span>
        Home
    </a>
    <a href="/cart">
        <span>🛒</span>
        Cart
    </a>
    <a href="/my-orders">
        <span>📦</span>
        Order
    </a>
    <a href="/merchant">
        <span>🏪</span>
        Merchant
    </a>
</div>
<script>
function loadNotifications()
{
    fetch('/notifications/count')
        .then(res => res.json())
        .then(data => {
            const driverBadge = document.getElementById('driverNotif');
            const merchantBadge = document.getElementById('merchantNotif');

            if(driverBadge){
                if(data.driver > 0){
                    driverBadge.innerText = data.driver;
                    driverBadge.style.display = 'inline-block';
                }else{
                    driverBadge.style.display = 'none';
                }
            }

            if(merchantBadge){
                if(data.merchant > 0){
                    merchantBadge.innerText = data.merchant;
                    merchantBadge.style.display = 'inline-block';
                }else{
                    merchantBadge.style.display = 'none';
                }
            }
        });
}

loadNotifications();
setInterval(loadNotifications, 5000);
</script>
</body>
</html>