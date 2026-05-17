<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
            :value="old('name', $user->name)" required autofocus autocomplete="name" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
            :value="old('email', $user->email)" required autocomplete="username" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="phone" value="Nomor HP" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
            value="{{ old('phone', $user->phone) }}" />
    </div>

    <div>
        <x-input-label for="address" value="Alamat Lengkap" />
        <textarea id="address" name="address"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address', $user->address) }}</textarea>
    </div>

    <div>
        <x-input-label for="photo" value="Foto Profil" />
        <input id="photo" name="photo" type="file" class="mt-1 block w-full">
    </div>

    @if($user->photo)
        <img src="{{ asset('storage/' . $user->photo) }}" width="100" style="border-radius:12px;">
    @endif

    <input type="hidden" name="latitude" id="latitude" value="{{ $user->latitude }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ $user->longitude }}">

    <div id="gps-status" class="mt-3 text-orange-600 font-bold">
        Mengambil lokasi GPS...
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>

        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400"
            >{{ __('Saved.') }}</p>
        @endif
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
</section>
