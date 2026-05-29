@extends('layouts.customer-page')

@section('content')

@php
    $subtotal = collect($cart)->sum(function($item){
        return $item['price'] * $item['qty'];
    });
@endphp

<div class="checkout-wrapper">

    <div class="checkout-card">
        <h2 class="checkout-title">📍 Lokasi Pengiriman</h2>
        <p class="checkout-subtitle">Klik peta atau geser marker. Alamat akan terisi otomatis.</p>

        <div id="map"></div>

        <form method="POST" action="{{ route('checkout.calculate') }}">
            @csrf

            <input type="hidden" name="latitude" id="latitude" required>
<input type="hidden" name="longitude" id="longitude" required>
            <textarea
                name="address"
                id="address"
                class="address-input"
                placeholder="Alamat lengkap pengiriman..."
                required></textarea>

            <button type="submit" class="checkout-btn">
                Hitung Ongkir
            </button>
        </form>
    </div>

    <div class="summary-card">
        <h3>Total Produk</h3>

        <div class="summary-total">
            Rp {{ number_format($subtotal) }}
        </div>

        <div class="summary-note">
            Ongkir dihitung setelah lokasi dipilih.
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let defaultLat = -7.797068;
    let defaultLng = 110.370529;

    let map = L.map('map', {
        zoomControl: true
    }).setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

    async function getAddress(lat, lng) {
        let addressInput = document.getElementById('address');
        addressInput.value = 'Mengambil alamat...';

        try {
            let response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
            );

            let data = await response.json();

            if (data && data.display_name) {
                addressInput.value = data.display_name;
            } else {
                addressInput.value = lat + ', ' + lng;
            }
        } catch (e) {
            addressInput.value = lat + ', ' + lng;
        }
    }

    function setLocation(lat, lng, zoom = false) {
    console.log('LOKASI DIPILIH:', lat, lng);

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    marker.setLatLng([lat, lng]);

    if (zoom) {
        map.setView([lat, lng], 16);
    }

    getAddress(lat, lng);
}
    marker.on('dragend', function () {
        let pos = marker.getLatLng();
        setLocation(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        setLocation(e.latlng.lat, e.latlng.lng);
    });

    setTimeout(function () {
        map.invalidateSize();
    }, 500);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            setLocation(
                position.coords.latitude,
                position.coords.longitude,
                true
            );

            setTimeout(function () {
                map.invalidateSize();
            }, 500);

        }, function () {
            setLocation(defaultLat, defaultLng, true);
        });
    } else {
        setLocation(defaultLat, defaultLng, true);
    }

});
</script>

<style>
.checkout-wrapper{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.checkout-card,
.summary-card{
    background:white;
    border-radius:28px;
    padding:18px;
    border:1px solid #fed7aa;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.checkout-title{
    margin:0;
    font-size:26px;
    color:#9a3412;
    font-weight:900;
}

.checkout-subtitle{
    margin-top:6px;
    color:#6b7280;
    margin-bottom:16px;
}

#map{
    width:100%;
    height:360px;
    border-radius:22px;
    margin-bottom:16px;
    overflow:hidden;
    z-index:1;
    background:#e5e7eb;
}

.address-input{
    width:100%;
    min-height:100px;
    border:none;
    background:#fff7ed;
    border-radius:18px;
    padding:14px;
    font-size:14px;
    margin-bottom:14px;
    outline:none;
}

.checkout-btn{
    width:100%;
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:16px;
    border-radius:18px;
    font-size:15px;
    font-weight:900;
}

.summary-card h3{
    margin:0;
    color:#6b7280;
    font-size:14px;
}

.summary-total{
    margin-top:8px;
    font-size:34px;
    font-weight:900;
    color:#9a3412;
}

.summary-note{
    margin-top:10px;
    color:#6b7280;
    font-size:13px;
}
</style>

@endsection