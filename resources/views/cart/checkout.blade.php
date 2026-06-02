@extends('layouts.customer-page')

@section('content')

@php
    $subtotal = collect($cart)->sum(function($item){
        return $item['price'] * $item['qty'];
    });
@endphp

<div class="checkout-wrapper">

    <div class="checkout-card">

        <div class="checkout-head">
            <h2>📍 Lokasi Pengiriman</h2>
            <p>Klik peta atau geser marker. Alamat akan terisi otomatis.</p>
        </div>

        <div class="map-box">
            <div id="map"></div>
        </div>

        <form method="POST" action="{{ route('checkout.calculate') }}" class="checkout-form">
            @csrf

            <input type="hidden" name="latitude" id="latitude" required>
            <input type="hidden" name="longitude" id="longitude" required>

            <label>Alamat Pengiriman</label>
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
        <span>Total Produk</span>

        <h3>
            Rp {{ number_format($subtotal) }}
        </h3>

        <p>
            Ongkir dihitung setelah lokasi dipilih.
        </p>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
    padding:20px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.checkout-head{
    margin-bottom:16px;
}

.checkout-head h2{
    margin:0;
    font-size:28px;
    font-weight:900;
    color:var(--primary);
}

.checkout-head p{
    margin:7px 0 0;
    color:#6b7280;
    font-size:14px;
    line-height:1.5;
}

.map-box{
    border-radius:24px;
    overflow:hidden;
    background:#e5e7eb;
    border:2px solid rgba(15,23,42,.06);
    margin-bottom:18px;
}

#map{
    width:100%;
    height:390px;
    z-index:1;
}

.checkout-form{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.checkout-form label{
    color:var(--primary);
    font-size:13px;
    font-weight:900;
}

.address-input{
    width:100%;
    min-height:100px;
    border:none;
    outline:none;
    resize:vertical;
    background:rgba(15,23,42,.05);
    border-radius:18px;
    padding:14px;
    font-size:14px;
    font-weight:700;
    color:#111827;
}

.address-input:focus{
    box-shadow:0 0 0 4px rgba(15,23,42,.06);
}

.checkout-btn{
    width:100%;
    border:none;
    border-radius:20px;
    padding:16px;
    cursor:pointer;

    color:white;
    font-size:15px;
    font-weight:900;

    background:linear-gradient(
        135deg,
        var(--primary),
        var(--secondary)
    );

    box-shadow:0 12px 24px rgba(15,23,42,.16);
}

.checkout-btn:hover{
    transform:translateY(-1px);
}

.summary-card span{
    color:#6b7280;
    font-size:13px;
    font-weight:900;
}

.summary-card h3{
    margin:8px 0 0;
    color:var(--primary);
    font-size:34px;
    font-weight:900;
}

.summary-card p{
    margin:10px 0 0;
    color:#6b7280;
    font-size:13px;
}

@media(max-width:640px){

    .checkout-card,
    .summary-card{
        padding:16px;
        border-radius:24px;
    }

    .checkout-head h2{
        font-size:24px;
    }

    #map{
        height:340px;
    }

    .summary-card h3{
        font-size:30px;
    }
}
</style>

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
    }).addTo(map).bindPopup('📍 Lokasi Pengiriman');

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

@endsection