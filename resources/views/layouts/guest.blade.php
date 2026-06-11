@php
    $appSetting = \App\Models\AppSetting::first();

    $primaryColor = $appSetting->primary_color ?? '#f97316';
    $secondaryColor = $appSetting->secondary_color ?? '#fb923c';

    $loginLogo = !empty($appSetting->login_logo)
        ? asset('storage/'.$appSetting->login_logo)
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
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $appName }}</title>

<link rel="icon" href="{{ $favicon }}?v={{ $faviconVersion }}">
<link rel="shortcut icon" href="{{ $favicon }}?v={{ $faviconVersion }}">
<link rel="apple-touch-icon" href="{{ $favicon }}?v={{ $faviconVersion }}">

@vite(['resources/css/app.css','resources/js/app.js'])

<style>
:root{
    --primary: {{ $primaryColor }};
    --secondary: {{ $secondaryColor }};
}

body{
    margin:0;
    min-height:100vh;

    background:
        linear-gradient(
            135deg,
            var(--primary),
            var(--secondary)
        );

    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;

    font-family:'Segoe UI',sans-serif;
}

.auth-card{
    width:100%;
    max-width:500px;

    background:white;
    border-radius:30px;
    padding:30px;

    box-shadow:
        0 25px 60px rgba(0,0,0,.15);
}
</style>

</head>
<body>

<div class="auth-card">
    {{ $slot }}
</div>

</body>
</html>