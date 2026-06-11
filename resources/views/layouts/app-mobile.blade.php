@php
    $appSetting = \App\Models\AppSetting::first();

    $primaryColor = $appSetting->primary_color ?? '#f97316';
    $secondaryColor = $appSetting->secondary_color ?? '#fb923c';

    $appLogo = !empty($appSetting->customer_logo)
        ? asset('storage/'.$appSetting->customer_logo)
        : asset('images/logo-javajek.png');

    $favicon = !empty($appSetting->favicon)
        ? asset('storage/'.$appSetting->favicon)
        : asset('favicon.png');

    $faviconVersion = optional($appSetting->updated_at)->timestamp ?? time();

    $appName = $appSetting->app_name ?? 'JavaJek';
@endphp


<!DOCTYPE html>
<html lang="id">
    
<head>
    <meta charset="UTF-8">
   
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $appName }}</title>

   <link rel="icon" href="{{ $favicon }}?v={{ $faviconVersion }}">
<link rel="shortcut icon" href="{{ $favicon }}?v={{ $faviconVersion }}">
<link rel="apple-touch-icon" href="{{ $favicon }}?v={{ $faviconVersion }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
   
    <style>
        :root{
            --primary: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --soft-bg: #fff7ed;
            --soft-border: #fed7aa;
            --dark: #111827;
            --muted: #6b7280;
        }

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            min-height:100vh;
            background:linear-gradient(135deg,var(--soft-bg),#ffedd5,var(--soft-bg));
            font-family:'Segoe UI',sans-serif;
            color:var(--dark);
        }

        .app-topbar{
            position:sticky;
            top:0;
            z-index:999;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:white;
            padding:14px 16px;
            display:grid;
            grid-template-columns:42px 1fr 42px;
            align-items:center;
            gap:10px;
            box-shadow:0 12px 30px rgba(15,23,42,.18);
        }

        .app-back{
            width:42px;
            height:42px;
            border-radius:15px;
            background:rgba(255,255,255,.22);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            font-size:22px;
            font-weight:900;
        }

        .app-title-box{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
            min-width:0;
        }

        .app-logo{
            width:36px;
            height:36px;
            border-radius:13px;
            object-fit:cover;
            background:white;
            padding:3px;
            flex-shrink:0;
        }

        .app-title{
            font-size:17px;
            font-weight:900;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        main{
            max-width:900px;
            margin:auto;
            padding:18px 14px 90px;
        }

        .food-card{
            background:white;
            border:1px solid rgba(255,255,255,.8);
            border-radius:26px;
            padding:18px;
            margin-bottom:16px;
            box-shadow:0 12px 30px rgba(15,23,42,.08);
        }

        .form-control{
            width:100%;
            border:1px solid var(--soft-border);
            border-radius:18px;
            padding:14px 16px;
            outline:none;
            background:white;
        }

        .form-control:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 4px color-mix(in srgb, var(--primary) 18%, transparent);
        }

        .btn-order,
        .btn-mini{
            border:none;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:white;
            padding:10px 14px;
            border-radius:14px;
            font-weight:900;
            text-decoration:none;
            cursor:pointer;
            display:inline-block;
        }

        .btn-mini.green{
            background:#16a34a;
        }

        .btn-mini.red{
            background:#dc2626;
        }

        .btn-mini.blue{
            background:#0ea5e9;
        }

        .btn-mini.orange{
            background:linear-gradient(135deg,var(--primary),var(--secondary));
        }

        .alert-card{
            font-weight:900;
            border-radius:20px;
            padding:14px 16px;
        }

        .alert-success{
            background:#dcfce7;
            color:#166534;
            border-left:6px solid #16a34a;
        }

        .alert-error{
            background:#fee2e2;
            color:#991b1b;
            border-left:6px solid #dc2626;
        }

        @media(max-width:640px){
            .app-topbar{
                padding:12px;
            }

            .app-title{
                font-size:16px;
            }

            main{
                padding:14px 12px 90px;
            }

            .food-card{
                border-radius:22px;
                padding:15px;
            }
        }
    .logout-btn{
    width:42px;
    height:42px;
    border:none;
    cursor:pointer;

    border-radius:15px;

    background:rgba(255,255,255,.22);
    color:white;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:18px;
    font-weight:900;
}

.logout-btn:hover{
    background:rgba(255,255,255,.30);
}
    </style>
</head>

<body>

<div class="app-topbar">

    <a href="{{ $backUrl ?? '/' }}" class="app-back">
        ←
    </a>

    <div class="app-title-box">
        <img src="{{ $logoUrl ?? $appLogo }}"
     class="app-logo"
     alt="{{ $pageTitle ?? $appName }}">
     
        <span class="app-title">
            {{ $pageTitle ?? $appName }}
        </span>
    </div>

    <form method="POST"
          action="{{ route('logout') }}"
          style="margin:0;">
        @csrf

        <button type="submit"
                class="logout-btn">
            ⎋
        </button>
    </form>

</div>

<main>
        @yield('content')
</main>

</body>
</html>