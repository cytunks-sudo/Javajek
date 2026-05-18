@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 class="text-3xl font-bold text-orange-600 mb-2">
        Daftar Merchant
    </h2>

    <p class="mb-6 text-gray-600">
        Daftarkan restoran Anda ke JavaJek Food.
    </p>

    <form method="POST"
          action="/apply-merchant"
          enctype="multipart/form-data">

        @csrf

        {{-- FOTO --}}
        <div class="mb-5">

            <label class="font-bold">
                Foto Restoran
            </label>

            <input type="file"
                   name="photo"
                   class="form-control"
                   accept="image/*">

        </div>

        {{-- NAMA --}}
        <div class="mb-5">

            <label class="font-bold">
                Nama Restoran
            </label>

            <input type="text"
                   name="name"
                   class="form-control"
                   placeholder="Contoh: Ayam Geprek Nusantara"
                   required>

        </div>

        {{-- KATEGORI --}}
        <div class="mb-5">

            <label class="font-bold">
                Kategori Restoran
            </label>

            <select name="category"
                    class="form-control"
                    required>

                <option value="">-- Pilih Kategori --</option>

                <option value="Makanan Indonesia">
                    Makanan Indonesia
                </option>

                <option value="Ayam & Bebek">
                    Ayam & Bebek
                </option>

                <option value="Seafood">
                    Seafood
                </option>

                <option value="Bakso & Mie">
                    Bakso & Mie
                </option>

                <option value="Minuman">
                    Minuman
                </option>

                <option value="Cafe">
                    Cafe
                </option>

                <option value="Fast Food">
                    Fast Food
                </option>

            </select>

        </div>

        {{-- JAM BUKA --}}
        <div style="
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:14px;
        ">

            <div>

                <label class="font-bold">
                    Jam Buka
                </label>

                <input type="time"
                       name="open_time"
                       class="form-control">

            </div>

            <div>

                <label class="font-bold">
                    Jam Tutup
                </label>

                <input type="time"
                       name="close_time"
                       class="form-control">

            </div>

        </div>

        <br>

        {{-- ALAMAT --}}
        <div class="mb-5">

            <label class="font-bold">
                Alamat Restoran
            </label>

            <textarea name="address"
                      id="address"
                      class="form-control"
                      rows="4"
                      placeholder="Alamat lengkap restoran..."
                      required></textarea>

        </div>

        {{-- LAT LNG --}}
        <input type="hidden"
               name="latitude"
               id="latitude">

        <input type="hidden"
               name="longitude"
               id="longitude">

        {{-- MAP --}}
        <div class="mb-5">

            <label class="font-bold block mb-2">
                Lokasi Merchant
            </label>

            <div id="mapMerchant"
                 style="
                    height:320px;
                    border-radius:22px;
                    overflow:hidden;
                    border:2px solid #fed7aa;
                 ">
            </div>

        </div>

        {{-- INFO GPS --}}
        <div id="gpsInfo"
             style="
                background:#fff7ed;
                padding:14px;
                border-radius:16px;
                margin-bottom:18px;
                color:#9a3412;
                font-weight:600;
             ">
            Mengambil lokasi GPS...
        </div>

        <button type="submit" class="btn-order">
            Daftar Merchant
        </button>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const defaultLat = -7.7956;
    const defaultLng = 110.3695;

    let map;
    let marker;

    function setLatLng(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    function initMap(lat, lng) {
        setLatLng(lat, lng);

        map = L.map('mapMerchant').setView([lat, lng], 16);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            setLatLng(pos.lat, pos.lng);
        });

        setTimeout(function () {
            map.invalidateSize();
        }, 500);
    }

    initMap(defaultLat, defaultLng);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {

            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            setLatLng(lat, lng);

            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 16);

            document.getElementById('gpsInfo').innerHTML =
                'Lokasi berhasil ditemukan. Geser marker jika lokasi belum tepat.';

        }, function () {

            document.getElementById('gpsInfo').innerHTML =
                'GPS belum diizinkan. Geser marker pada map untuk menentukan lokasi merchant.';

        }, {
            enableHighAccuracy: false,
            timeout: 5000,
            maximumAge: 60000
        });
    } else {
        document.getElementById('gpsInfo').innerHTML =
            'Browser tidak mendukung GPS. Geser marker pada map.';
    }

});
</script>

@endsection