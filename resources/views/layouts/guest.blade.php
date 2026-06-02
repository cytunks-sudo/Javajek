@php
    $setting = \App\Models\AppSetting::first();

    $primary = $setting->primary_color ?? '#f97316';
    $secondary = $setting->secondary_color ?? '#fb923c';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $setting->app_name ?? 'JavaJek' }}</title>

@vite(['resources/css/app.css','resources/js/app.js'])

<style>
:root{
    --primary: {{ $primary }};
    --secondary: {{ $secondary }};
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