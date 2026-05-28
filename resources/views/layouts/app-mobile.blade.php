<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'JavaJek' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *{box-sizing:border-box}

        body{
            margin:0;
            background:#fff7ed;
            font-family:'Segoe UI',sans-serif;
            color:#111827;
        }

        .app-topbar{
            position:sticky;
            top:0;
            z-index:999;
            background:linear-gradient(135deg,#ff6b00,#ff8a1f);
            color:white;
            padding:14px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0 10px 30px rgba(249,115,22,.25);
        }

        .app-back{
            width:40px;
            height:40px;
            border-radius:14px;
            background:rgba(255,255,255,.2);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            font-weight:900;
        }

        .app-title{
            font-weight:900;
            font-size:16px;
        }

        main{
            max-width:900px;
            margin:auto;
            padding:18px 14px 90px;
        }

        .food-card{
            background:white;
            border-radius:26px;
            padding:18px;
            margin-bottom:16px;
            box-shadow:0 12px 30px rgba(15,23,42,.08);
        }

        .form-control{
            width:100%;
            border:1px solid #fed7aa;
            border-radius:18px;
            padding:14px 16px;
            outline:none;
        }

        .btn-order,
        .btn-mini{
            border:none;
            background:#f97316;
            color:white;
            padding:10px 14px;
            border-radius:14px;
            font-weight:900;
            text-decoration:none;
            cursor:pointer;
            display:inline-block;
        }

        .btn-mini.green{background:#16a34a}
        .btn-mini.red{background:#dc2626}
        .btn-mini.blue{background:#0ea5e9}

        @media(max-width:640px){
            main{padding:14px 12px 90px}
            .food-card{border-radius:22px;padding:15px}
        }
    </style>
</head>

<body>

<div class="app-topbar">
    <a href="{{ $backUrl ?? '/' }}" class="app-back">←</a>
    <div class="app-title">{{ $pageTitle ?? 'JavaJek' }}</div>
    <div style="width:40px;"></div>
</div>

<main>
    @if(session('success'))
        <div class="food-card" style="color:#166534;font-weight:900;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="food-card" style="color:#991b1b;font-weight:900;">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>