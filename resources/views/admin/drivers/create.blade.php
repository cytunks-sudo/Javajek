@extends('layouts.admin')

@section('content')

<div class="card-box">
    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Tambah Driver
    </h2>

    <form method="POST" action="/admin/drivers/store">
        @csrf

        <p>User Driver</p>
        <select name="user_id" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;">
            @foreach($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }} - {{ $user->email }}
                </option>
            @endforeach
        </select>

        <p class="mt-4">Jenis Kendaraan</p>
        <input type="text" name="vehicle_type" placeholder="Motor"
               style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;">

        <p class="mt-4">Nomor Plat</p>
        <input type="text" name="plate_number" placeholder="P 1234 ABC"
               style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;">

        <br><br>

        <button class="btn-primary">
            Simpan
        </button>
    </form>
</div>

@endsection