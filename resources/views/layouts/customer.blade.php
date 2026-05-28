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
    width:74px;
    height:74px;
    border-radius:18px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
    overflow:hidden;
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
    left:14px;
    right:14px;
    bottom:14px;
    z-index:999;
    background:rgba(255,255,255,.96);
    border:1px solid rgba(255,255,255,.8);
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
    color:#9a3412;
    text-decoration:none;
}

.bottom-nav a.active{
    color:#f97316;
}

.bottom-nav span{
    display:block;
    font-size:22px;
    line-height:22px;
    margin-bottom:3px;
}

.bottom-nav .nav-dot{
    position:absolute;
    top:8px;
    right:23%;
    background:#dc2626;
    color:white;
    font-size:10px;
    font-weight:900;
    min-width:17px;
    height:17px;
    border-radius:999px;
    display:none;
    align-items:center;
    justify-content:center;
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
.menu-item{
    width:100%;
    display:flex;
    align-items:center;
    gap:10px;
    padding:13px 14px;
    border-radius:16px;
    text-decoration:none;
    color:#9a3412;
    font-weight:800;
    transition:.2s;
    margin-bottom:6px;
}

.menu-item:hover{
    background:#fff7ed;
}

.logout-btn{
    border:none;
    background:#fee2e2;
    color:#b91c1c;
    cursor:pointer;
    font-size:14px;
}
    </style>
</head>

<body>

<div class="hero">

    <div class="hero-inner">

        {{-- =========================
           TOP HEADER
        ========================= --}}

        <div class="topbar">

            <div class="brand">

                <div class="brand-icon">
    <img src="{{ asset('images/logo-javajek.png') }}"
     alt="JavaJek"
     style="
        width:75px;
        height:75px;
        object-fit:cover;
        border-radius:14px;
     ">
</div>

                <div>
                    <h1 id="greetingText">
                        JavaJek Food
                    </h1>

                    <p>
                        Halo,
                        {{ auth()->user()->name }}
                    </p>
                </div>

            </div>

            <div style="position:relative;">

    <button onclick="toggleUserMenu()"
            id="menuButton"
            style="
                width:48px;
                height:48px;
                border:none;
                border-radius:16px;
                background:rgba(255,255,255,.18);
                color:white;
                font-size:24px;
                cursor:pointer;
                backdrop-filter:blur(10px);
            ">
        ☰
    </button>

    <div id="userDropdown"
         style="
            position:absolute;
            top:60px;
            right:0;
            width:220px;
            background:white;
            border-radius:24px;
            padding:12px;
            box-shadow:0 20px 45px rgba(0,0,0,.18);
            display:none;
            z-index:9999;
         ">

        <div style="
            padding:12px;
            border-bottom:1px solid #fed7aa;
            margin-bottom:10px;
        ">

            <div style="
                font-size:17px;
                font-weight:900;
                color:#9a3412;
            ">
                {{ auth()->user()->name }}
            </div>

            <div style="
                font-size:13px;
                color:#6b7280;
                margin-top:3px;
            ">
                {{ auth()->user()->email }}
            </div>

        </div>

        <a href="/profile"
           class="menu-item">
            👤 Profile
        </a>

        <a href="/driver"
           class="menu-item">
            🛵 Driver
        </a>

        <a href="/merchant"
           class="menu-item">
            🏪 Merchant
        </a>

        <a href="/my-orders"
           class="menu-item">
            📦 Pesanan Saya
        </a>

        <a href="/cart"
           class="menu-item">
            🛒 Keranjang
        </a>

        <form method="POST"
              action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                    class="menu-item logout-btn">
                🚪 Logout
            </button>
        </form>

    </div>

</div>

        </div>

        {{-- =========================
           SEARCH
        ========================= --}}

        <div style="margin-top:18px;">

            <form action="/" method="GET">

                <div style="
                    background:white;
                    border-radius:20px;
                    padding:10px 14px;
                    display:flex;
                    align-items:center;
                    gap:10px;
                    box-shadow:0 12px 25px rgba(0,0,0,.12);
                ">

                    <span style="font-size:20px;">
                        🔍
                    </span>

                    <input type="text"
                           name="search"
                           placeholder="Cari makanan favoritmu..."
                           style="
                                border:none;
                                outline:none;
                                width:100%;
                                font-size:15px;
                                background:none;
                           ">

                </div>

            </form>

        </div>

        {{-- =========================
           WALLET CARD
        ========================= --}}

        <div style="
            margin-top:18px;
            background:linear-gradient(135deg,#ea580c,#fb923c,#fdba74);
            border-radius:28px;
            padding:20px;
            box-shadow:0 18px 35px rgba(249,115,22,.35);
            position:relative;
            overflow:hidden;
        ">

            <div style="
                position:absolute;
                width:140px;
                height:140px;
                background:rgba(255,255,255,.12);
                border-radius:50%;
                right:-40px;
                top:-40px;
            "></div>

            <div style="
                position:relative;
                z-index:2;
            ">

                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                ">

                    <div>

                        <div style="
                            font-size:14px;
                            opacity:.9;
                        ">
                            💳 Saldo JavaPay
                        </div>

                        <div id="saldoText"
                             style="
                                font-size:30px;
                                font-weight:900;
                                margin-top:6px;
                            ">
                            ********
                        </div>

                    </div>

                    <button onclick="toggleSaldo()"
                            style="
                                border:none;
                                width:44px;
                                height:44px;
                                border-radius:50%;
                                background:rgba(255,255,255,.2);
                                color:white;
                                font-size:20px;
                                cursor:pointer;
                            ">
                        👁️
                    </button>

                </div>

                <div style="
                    margin-top:18px;
                    display:grid;
                    grid-template-columns:repeat(2,1fr);
                    gap:10px;
                ">

                    <a href="/topup"
                       class="btn-order"
                       style="
                            text-align:center;
                            background:white;
                            color:#ea580c;
                            box-shadow:none;
                       ">
                        ➕ Topup
                    </a>

                    <a href="/withdraw"
                       class="btn-order"
                       style="
                            text-align:center;
                            background:rgba(255,255,255,.18);
                            border:1px solid rgba(255,255,255,.25);
                            box-shadow:none;
                       ">
                        💸 Tarik
                    </a>

                </div>

            </div>

        </div>

        {{-- =========================
           MENU ICON
        ========================= --}}

        <div style="
            margin-top:20px;
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:12px;
        ">

            <a href="/"
               class="food-card"
               style="text-align:center;padding:14px;text-decoration:none;">

                <div style="font-size:34px;">🍔</div>

                <div style="
                    margin-top:8px;
                    font-size:13px;
                    font-weight:800;
                    color:#9a3412;
                ">
                    J-Food
                </div>

            </a>

            <a href="/driver"
               class="food-card"
               style="text-align:center;padding:14px;text-decoration:none;">

                <div style="font-size:34px;">🛵</div>

                <div style="
                    margin-top:8px;
                    font-size:13px;
                    font-weight:800;
                    color:#9a3412;
                ">
                    Driver
                </div>

            </a>

            <a href="/merchant"
               class="food-card"
               style="text-align:center;padding:14px;text-decoration:none;">

                <div style="font-size:34px;">🏪</div>

                <div style="
                    margin-top:8px;
                    font-size:13px;
                    font-weight:800;
                    color:#9a3412;
                ">
                    Merchant
                </div>

            </a>

            <a href="/my-orders"
               class="food-card"
               style="text-align:center;padding:14px;text-decoration:none;">

                <div style="font-size:34px;">📦</div>

                <div style="
                    margin-top:8px;
                    font-size:13px;
                    font-weight:800;
                    color:#9a3412;
                ">
                    Pesanan
                </div>

            </a>

        </div>

    </div>

</div>

<script>

let saldoVisible = false;

function toggleSaldo()
{
    const saldo =
        document.getElementById('saldoText');

    saldoVisible = !saldoVisible;

    saldo.innerText = saldoVisible
        ? 'Rp 0'
        : '********';
}

function updateGreeting()
{
    const hour = new Date().getHours();

    const greeting =
        document.getElementById('greetingText');

    if (!greeting) return;

    if (hour >= 5 && hour < 10) {

        greeting.innerText =
            '☀️ Selamat Pagi';

    } else if (hour >= 10 && hour < 15) {

        greeting.innerText =
            '🌤️ Selamat Siang';

    } else if (hour >= 15 && hour < 18) {

        greeting.innerText =
            '🌇 Selamat Sore';

    } else {

        greeting.innerText =
            '🌙 Selamat Malam';
    }
}

updateGreeting();

</script>



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

    <a href="/driver"
   class="food-card"
   style="position:relative;text-align:center;padding:14px;text-decoration:none;">

    <i id="driverNotif" class="nav-dot">0</i>

    <div style="font-size:34px;">🛵</div>

    <div style="margin-top:8px;font-size:13px;font-weight:800;color:#9a3412;">
        Driver
    </div>
</a>

    <a href="/merchant"
   class="food-card"
   style="position:relative;text-align:center;padding:14px;text-decoration:none;">

    <i id="merchantNotif" class="nav-dot">0</i>

    <div style="font-size:34px;">🏪</div>

    <div style="margin-top:8px;font-size:13px;font-weight:800;color:#9a3412;">
        Merchant
    </div>
</a>

</div>

<script>
function setBadge(id, count)
{
    const badge = document.getElementById(id);

    if (!badge) return;

    if (count > 0) {
        badge.innerText = count;
        badge.style.display = 'inline-flex';
    } else {
        badge.style.display = 'none';
    }
}

function loadNotifications()
{
    fetch('/notifications/count')
        .then(res => res.json())
        .then(data => {
            const driverCount = parseInt(data.driver ?? 0);
            const merchantCount = parseInt(data.merchant ?? 0);

            setBadge('driverBottomNotif', driverCount);
setBadge('merchantBottomNotif', merchantCount);
        })
        .catch(err => {
            console.log('Notif layout error:', err);
        });
}

loadNotifications();
setInterval(loadNotifications, 5000);

function toggleUserMenu()
{
    const menu =
        document.getElementById('userDropdown');

    if (!menu) return;

    menu.style.display =
        menu.style.display === 'block'
        ? 'none'
        : 'block';
}

window.addEventListener('click', function(e){

    const dropdown =
        document.getElementById('userDropdown');

    const button =
        document.getElementById('menuButton');

    if (!dropdown || !button) return;

    if (!dropdown.contains(e.target)
        && !button.contains(e.target)) {

        dropdown.style.display = 'none';
    }
});

</script>
</body>
</html>