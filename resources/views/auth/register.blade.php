@php
    $setting = \App\Models\AppSetting::first();

    $primaryColor = $setting->primary_color ?? '#f97316';
    $secondaryColor = $setting->secondary_color ?? '#fb923c';
    $logo = $setting->login_logo ?? null;
    $appName = $setting->app_name ?? 'JavaJek';
@endphp

<x-guest-layout>

    <div style="text-align:center;margin-bottom:25px;">

        @if($logo)
            <img src="{{ asset('storage/'.$logo) }}"
                 style="
                    width:90px;
                    height:90px;
                    object-fit:contain;
                    margin:auto;
                    display:block;
                    margin-bottom:12px;
                 ">
        @endif

        <h2 style="
            font-size:28px;
            font-weight:900;
            color:{{ $primaryColor }};
            margin:0;
        ">
            Daftar Akun
        </h2>

        <p style="
            color:#6b7280;
            margin-top:6px;
            font-size:14px;
        ">
            Bergabung bersama {{ $appName }}
        </p>

    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mb-4">
    <x-input-label
        for="username"
        value="Username" />

    <x-text-input
        id="username"
        class="block mt-1 w-full"
        type="text"
        name="username"
        :value="old('username')"
        required />

    <x-input-error
        :messages="$errors->get('username')"
        class="mt-2" />
</div>

        <div class="mb-4">
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="phone" value="Nomor HP" />
            <x-text-input
                id="phone"
                class="block mt-1 w-full"
                type="text"
                name="phone"
                :value="old('phone')"
                required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="address" value="Alamat Lengkap" />

            <textarea
                id="address"
                name="address"
                rows="3"
                style="
                    width:100%;
                    border:1px solid #d1d5db;
                    border-radius:14px;
                    padding:12px;
                "
                required>{{ old('address') }}</textarea>

            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div id="gps-status"
             style="
                margin-bottom:18px;
                color:{{ $primaryColor }};
                font-weight:800;
             ">
            📍 Mengambil lokasi GPS...
        </div>

        <div class="mb-4">
            <x-input-label for="password" value="Password" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label
                for="password_confirmation"
                value="Konfirmasi Password" />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2" />
        </div>

        <button
            type="submit"
            style="
                width:100%;
                border:none;
                padding:14px;
                border-radius:16px;
                color:white;
                font-weight:900;
                font-size:15px;
                cursor:pointer;
                background:linear-gradient(
                    135deg,
                    {{ $primaryColor }},
                    {{ $secondaryColor }}
                );
                box-shadow:0 10px 24px rgba(0,0,0,.15);
            ">
            Daftar Sekarang
        </button>

        <div style="
            text-align:center;
            margin-top:18px;
        ">
            <a href="{{ route('login') }}"
               style="
                    color:{{ $primaryColor }};
                    font-weight:800;
                    text-decoration:none;
               ">
                Sudah punya akun? Login
            </a>
        </div>

    </form>

    <script>
    navigator.geolocation.getCurrentPosition(

        function(position){

            document.getElementById('latitude').value =
                position.coords.latitude;

            document.getElementById('longitude').value =
                position.coords.longitude;

            document.getElementById('gps-status').innerHTML =
                '✅ Lokasi GPS berhasil didapatkan';

        },

        function(){

            document.getElementById('gps-status').innerHTML =
                '⚠️ GPS gagal didapatkan. Aktifkan izin lokasi.';

        },

        {
            enableHighAccuracy:true,
            timeout:10000,
            maximumAge:0
        }
    );
    </script>

</x-guest-layout>