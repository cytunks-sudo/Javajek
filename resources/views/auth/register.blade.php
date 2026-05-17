<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h2 style="font-size:26px;font-weight:bold;color:#ff5a00;text-align:center;margin-bottom:20px;">
            Daftar Akun JavaJek
        </h2>

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" value="Nomor HP" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                :value="old('phone')" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="address" value="Alamat Lengkap" />
            <textarea id="address" name="address"
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                required>{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div id="gps-status" class="mt-3" style="color:#ff5a00;font-weight:bold;">
            Mengambil lokasi GPS...
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" style="color:#ff5a00;font-weight:bold;">
                Sudah punya akun?
            </a>

            <x-primary-button>
                Daftar
            </x-primary-button>
        </div>

        <script>
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                document.getElementById('gps-status').innerHTML = 'Lokasi GPS berhasil didapatkan';
            },
            function(error) {
                document.getElementById('gps-status').innerHTML = 'GPS gagal. Aktifkan izin lokasi.';
            }
        );
        </script>
    </form>
</x-guest-layout>