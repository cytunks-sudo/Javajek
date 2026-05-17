<x-guest-layout>

    <h2 style="font-size:24px;font-weight:bold;text-align:center;color:#ff5a00;margin-bottom:20px;">
        Login {{ ucfirst($role) }} JavaJek
    </h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <input type="hidden" name="login_role" value="{{ $role }}">

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
        </div>

        <div class="mt-4">
            <x-primary-button>
                Login {{ ucfirst($role) }}
            </x-primary-button>
        </div>
        <div style="margin-top:15px;text-align:center;">
    Belum punya akun?
    <a href="{{ route('register') }}" style="color:#ff5a00;font-weight:bold;">
        Daftar di sini
    </a>
</div>
    </form>

</x-guest-layout>