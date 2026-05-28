@extends('layouts.customer-page')

@section('content')

<div class="food-card">

    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Daftar Sebagai Driver
    </h2>

    <form method="POST" action="/apply-driver">
        @csrf

        <p>No HP</p>
        <input type="text"
               name="phone"
               style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <p>Jenis Kendaraan</p>
        <input type="text"
               name="vehicle_type"
               placeholder="Motor / Mobil"
               style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <p>Nomor Plat</p>
        <input type="text"
               name="plate_number"
               style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;">

        <p>Alamat</p>
        <textarea name="address"
                  style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;"></textarea>

        {{-- GPS --}}
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <br>

        <div id="gps-status"
             style="color:#ff5a00;font-weight:bold;">
            Mengambil lokasi GPS...
        </div>

        <br>

        <button class="btn-order">
            Kirim Pengajuan
        </button>

    </form>

</div>

<script>

navigator.geolocation.getCurrentPosition(

    function(position) {

        document.getElementById('latitude').value =
            position.coords.latitude;

        document.getElementById('longitude').value =
            position.coords.longitude;

        document.getElementById('gps-status').innerHTML =
            'Lokasi GPS berhasil didapatkan';

    },

    function(error) {

        document.getElementById('gps-status').innerHTML =
            'GPS gagal didapatkan. Aktifkan lokasi HP.';

    }

);

</script>

@endsection