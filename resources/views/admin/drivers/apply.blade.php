@extends('layouts.customer')

@section('content')

<div class="food-card">
    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Daftar Sebagai Driver
    </h2>

    <form method="POST" action="/apply-driver">
        @csrf

        <p>No HP</p>
        <input type="text" name="phone" style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <p>Jenis Kendaraan</p>
        <input type="text" name="vehicle_type" placeholder="Motor / Mobil" style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <p>Nomor Plat</p>
        <input type="text" name="plate_number" style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <p>Alamat</p>
        <textarea name="address" style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;"></textarea>

        <p>Latitude</p>
        <input type="text" name="latitude" style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <p>Longitude</p>
        <input type="text" name="longitude" style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <br><br>

        <button class="btn-order">
            Kirim Pengajuan
        </button>
    </form>
</div>

@endsection