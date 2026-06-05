@extends('layouts.customer-page')

@section('content')

<div class="ojek-wrapper">

    <div class="ojek-card">
        <h2 class="ojek-title">🏍️ JavaJek Ojek</h2>
        <p class="ojek-subtitle">
            Pilih lokasi jemput dan tujuan di peta.
        </p>

        <div class="mode-box">
            <button type="button" id="pickupBtn" class="mode-btn active">
                Titik Jemput
            </button>

            <button type="button" id="destinationBtn" class="mode-btn">
                Titik Tujuan
            </button>
        </div>

        <div id="map"></div>

        <form method="POST" action="{{ route('ojek.calculate') }}" id="ojekForm">
            @csrf

            <input type="hidden" name="pickup_latitude" id="pickup_latitude">
            <input type="hidden" name="pickup_longitude" id="pickup_longitude">
            <input type="hidden" name="destination_latitude" id="destination_latitude">
            <input type="hidden" name="destination_longitude" id="destination_longitude">

            <label>Alamat Jemput</label>
            <textarea name="pickup_address"
                      id="pickup_address"
                      class="ojek-input"
                      placeholder="Alamat jemput..."
                      required></textarea>

            <label>Alamat Tujuan</label>
            <textarea name="destination_address"
                      id="destination_address"
                      class="ojek-input"
                      placeholder="Alamat tujuan..."
                      required></textarea>

            <div class="fare-preview" id="farePreview">
                <div class="fare-title">
                    <span>💰 Estimasi Tarif</span>
                    <small>Tarif final sebelum voucher</small>
                </div>

                <div class="fare-row">
                    <span>Jarak</span>
                    <b id="previewDistance">-</b>
                </div>

                <div class="fare-row">
                    <span>Tarif Ojek</span>
                    <b id="previewFare">Pilih tujuan dulu</b>
                </div>
            </div>

            <button type="submit" class="ojek-submit">
                Lanjutkan Pesanan
            </button>
        </form>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let defaultLat = -6.5345831;
    let defaultLng = 111.0390329;
    let mode = 'pickup';

    let baseFee = 5000;
    let perKmFee = 2500;
    let minimumFee = 8000;

    let map = L.map('map').setView([defaultLat, defaultLng], 14);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let pickupMarker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map).bindPopup('Titik Jemput');

    let destinationMarker = null;
    let routeControl = null;

    function formatRupiah(number) {
        return 'Rp ' + Math.round(number).toLocaleString('id-ID');
    }

    function calculateDistanceKm(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    function roundUp500(amount) {
        return Math.ceil(amount / 500) * 500;
    }

    function updateFarePreview() {
        let pickupLat = document.getElementById('pickup_latitude').value;
        let pickupLng = document.getElementById('pickup_longitude').value;
        let destLat = document.getElementById('destination_latitude').value;
        let destLng = document.getElementById('destination_longitude').value;

        let distanceText = document.getElementById('previewDistance');
        let fareText = document.getElementById('previewFare');

        if (!pickupLat || !pickupLng || !destLat || !destLng) {
            distanceText.innerText = '-';
            fareText.innerText = 'Pilih tujuan dulu';
            return;
        }

        let distanceKm = calculateDistanceKm(
            parseFloat(pickupLat),
            parseFloat(pickupLng),
            parseFloat(destLat),
            parseFloat(destLng)
        );

        let fare = baseFee + (distanceKm * perKmFee);
        fare = Math.max(minimumFee, fare);
        fare = roundUp500(fare);

        distanceText.innerText = distanceKm.toFixed(1) + ' km';
        fareText.innerText = formatRupiah(fare);
    }

    async function getAddress(lat, lng, targetId) {
        let input = document.getElementById(targetId);
        input.value = 'Mengambil alamat...';

        try {
            let response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
            );

            let data = await response.json();

            if (data && data.display_name) {
                input.value = data.display_name;
            } else {
                input.value = lat + ', ' + lng;
            }
        } catch (e) {
            input.value = lat + ', ' + lng;
        }
    }

    function drawRoute() {
        let pickupLat = document.getElementById('pickup_latitude').value;
        let pickupLng = document.getElementById('pickup_longitude').value;
        let destLat = document.getElementById('destination_latitude').value;
        let destLng = document.getElementById('destination_longitude').value;

        if (!pickupLat || !pickupLng || !destLat || !destLng) {
            updateFarePreview();
            return;
        }

        if (routeControl) {
            map.removeControl(routeControl);
        }

        routeControl = L.Routing.control({
            waypoints: [
                L.latLng(pickupLat, pickupLng),
                L.latLng(destLat, destLng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            show: false,
            lineOptions: {
                styles: [
                    {
                        color: '#2563eb',
                        opacity: 0.9,
                        weight: 6
                    }
                ]
            },
            createMarker: function () {
                return null;
            }
        }).addTo(map);

        updateFarePreview();
    }

    function setPickup(lat, lng) {
        document.getElementById('pickup_latitude').value = lat;
        document.getElementById('pickup_longitude').value = lng;

        pickupMarker.setLatLng([lat, lng]);
        getAddress(lat, lng, 'pickup_address');

        drawRoute();
    }

    function setDestination(lat, lng) {
        document.getElementById('destination_latitude').value = lat;
        document.getElementById('destination_longitude').value = lng;

        if (destinationMarker) {
            destinationMarker.setLatLng([lat, lng]);
        } else {
            destinationMarker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map).bindPopup('Titik Tujuan');

            destinationMarker.on('dragend', function () {
                let pos = destinationMarker.getLatLng();
                setDestination(pos.lat, pos.lng);
            });
        }

        getAddress(lat, lng, 'destination_address');
        drawRoute();
    }

    pickupMarker.on('dragend', function () {
        let pos = pickupMarker.getLatLng();
        setPickup(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        if (mode === 'pickup') {
            setPickup(e.latlng.lat, e.latlng.lng);
        } else {
            setDestination(e.latlng.lat, e.latlng.lng);
        }
    });

    document.getElementById('pickupBtn').addEventListener('click', function () {
        mode = 'pickup';
        this.classList.add('active');
        document.getElementById('destinationBtn').classList.remove('active');
    });

    document.getElementById('destinationBtn').addEventListener('click', function () {
        mode = 'destination';
        this.classList.add('active');
        document.getElementById('pickupBtn').classList.remove('active');
    });

    document.getElementById('ojekForm').addEventListener('submit', function (e) {
        let destLat = document.getElementById('destination_latitude').value;
        let destLng = document.getElementById('destination_longitude').value;

        if (!destLat || !destLng) {
            e.preventDefault();
            alert('Silakan pilih titik tujuan terlebih dahulu.');
        }
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            map.setView([lat, lng], 16);
            setPickup(lat, lng);

            setTimeout(function () {
                map.invalidateSize();
            }, 500);
        }, function () {
            setPickup(defaultLat, defaultLng);
        });
    } else {
        setPickup(defaultLat, defaultLng);
    }

    setTimeout(function () {
        map.invalidateSize();
    }, 500);

});
</script>

<style>
.ojek-wrapper{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.leaflet-routing-container{
    display:none;
}

.ojek-card{
    background:white;
    border-radius:28px;
    padding:18px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.ojek-title{
    margin:0;
    font-size:26px;
    color:var(--primary);
    font-weight:900;
}

.ojek-subtitle{
    margin:6px 0 16px;
    color:#6b7280;
}

.mode-box{
    display:flex;
    gap:10px;
    margin-bottom:14px;
}

.mode-btn{
    flex:1;
    border:none;
    padding:12px;
    border-radius:16px;
    background:rgba(15,23,42,.05);
    color:var(--primary);
    font-weight:900;
}

.mode-btn.active{
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
}

#map{
    width:100%;
    height:360px;
    border-radius:22px;
    margin-bottom:16px;
    overflow:hidden;
    background:#e5e7eb;
}

label{
    display:block;
    margin:12px 0 6px;
    color:var(--primary);
    font-weight:900;
    font-size:13px;
}

.ojek-input{
    width:100%;
    min-height:80px;
    border:none;
    background:rgba(15,23,42,.05);
    border-radius:18px;
    padding:14px;
    font-size:14px;
    outline:none;
    font-weight:700;
}

.fare-preview{
    margin-top:16px;
    background:#f8fafc;
    border:2px dashed rgba(15,23,42,.08);
    border-radius:22px;
    padding:16px;
}

.fare-title{
    display:flex;
    justify-content:space-between;
    gap:12px;
    margin-bottom:8px;
}

.fare-title span{
    color:var(--primary);
    font-weight:900;
}

.fare-title small{
    color:#6b7280;
    font-size:12px;
    font-weight:700;
}

.fare-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:10px 0;
    border-bottom:1px dashed rgba(15,23,42,.08);
}

.fare-row:last-child{
    border-bottom:none;
}

.fare-row span{
    color:#6b7280;
    font-weight:800;
}

.fare-row b{
    color:#111827;
    font-size:18px;
    font-weight:900;
}

.ojek-submit{
    margin-top:16px;
    width:100%;
    border:none;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:16px;
    border-radius:18px;
    font-size:15px;
    font-weight:900;
    cursor:pointer;
}

@media(max-width:640px){
    #map{
        height:330px;
    }

    .fare-title{
        flex-direction:column;
    }
}
</style>

@endsection