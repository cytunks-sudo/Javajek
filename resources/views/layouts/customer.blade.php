<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>JavaJek</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body{
            background:linear-gradient(135deg,#fff7f2,#ffe0c7,#fff2e8);
            min-height:100vh;
        }

        .hero{
            background:linear-gradient(135deg,#ff5a00,#ff7b00,#ffb36b);
            color:white;
            border-radius:0 0 35px 35px;
            padding:35px 24px;
        }

        .food-card{
            background:white;
            border-radius:22px;
            padding:18px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
            margin-bottom:18px;
        }

        .btn-order{
            background:linear-gradient(135deg,#ff5a00,#ff7b00);
            color:white;
            padding:10px 16px;
            border-radius:14px;
            font-weight:700;
            display:inline-block;
        }
    </style>
</head>
<body>

<div class="hero">
    <h1 class="text-3xl font-bold">JavaJek Food</h1>
    <p class="mt-2">Pesan makanan favoritmu dengan cepat</p>
<div style="margin-top:15px;">
    <a href="/" style="color:white;font-weight:bold;margin-right:15px;">Home</a>
    <a href="/cart" style="color:white;font-weight:bold;margin-right:15px;">Keranjang</a>
    <a href="/my-orders" style="color:white;font-weight:bold;margin-right:15px;">Pesanan Saya</a>
    <a href="/apply-driver" style="color:white;font-weight:bold;margin-right:15px;">
    Daftar Driver
    </a>
    <a href="/apply-merchant" style="color:white;font-weight:bold;margin-right:15px;">
    Daftar Merchant
    </a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="color:white;font-weight:bold;background:none;border:none;">
            Logout
        </button>
    </form>
</div>

</div>

<main class="p-5 max-w-5xl mx-auto">
    @yield('content')
</main>

</body>
</html>