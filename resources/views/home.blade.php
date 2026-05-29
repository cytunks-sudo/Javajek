@extends('layouts.customer')

@section('content')

<div class="food-card merchant-near-card">
    <div class="home-section-head">
        <h2 class="section-title">Merchant Terdekat</h2>
        <p>Pilih toko terdekat dari lokasi Anda.</p>
    </div>

    <div class="product-grid">
        @forelse($merchants as $merchant)
            <a href="{{ route('merchant.foods', $merchant->id) }}" class="product-card merchant-card-link">
                @if($merchant->photo)
                    <img src="{{ asset('storage/'.$merchant->photo) }}" class="product-img">
                @else
                    <div class="product-img product-empty">🏪</div>
                @endif

                <div class="product-body">
                    <h3>{{ $merchant->name }}</h3>

                    <p class="product-merchant">
                        📍 {{ $merchant->distance_km }} km dari lokasi Anda
                    </p>

                    <div class="product-bottom">
                        <div class="product-price">Lihat Menu</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-product">Belum ada merchant.</div>
        @endforelse
    </div>
</div>


<div class="driver-map-card">
    <div class="driver-map-head">
        <div>
            <h3>🚗 Driver Aktif Di Sekitar Anda</h3>
            <p id="driverMapInfo">Mencari driver aktif...</p>
        </div>

        <div class="driver-count">
            <span id="driverCount">0</span>
            <small>Driver</small>
        </div>
    </div>

    <div id="customerDriverMap"></div>
</div>


<div class="food-card">
    <div class="home-section-head">
        <h2 class="section-title">Produk Terbaru</h2>
        <p>Menu terbaru dari semua merchant.</p>
    </div>

    <div class="product-grid">
        @forelse($foods as $food)
            <div class="product-card">
                @if($food->photo)
                    <img src="{{ asset('storage/'.$food->photo) }}"
                         class="product-img"
                         alt="{{ $food->name }}">
                @else
                    <div class="product-img product-empty">🍔</div>
                @endif

                <div class="product-body">
                    <h3>{{ $food->name }}</h3>

                    <p class="product-merchant">
                        {{ $food->restaurant->name ?? 'JavaJek Merchant' }}
                    </p>

                    @if($food->description)
                        <p class="product-desc">{{ $food->description }}</p>
                    @endif

                    <div class="product-bottom">
                        <div class="product-price">
                            Rp {{ number_format($food->price) }}
                        </div>

                        <a href="/cart/add/{{ $food->id }}" class="product-cart-btn">
                            + Keranjang
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-product">Belum ada menu makanan.</div>
        @endforelse
    </div>
</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.home-section-head{
    margin-bottom:12px;
}

.section-title{
    font-size:20px;
    font-weight:900;
    color:#9a3412;
    margin:0;
}

.home-section-head p{
    margin:4px 0 0;
    color:#6b7280;
    font-size:12px;
}

.product-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
}

.product-card{
    background:#ffffff;
    border:1px solid #fed7aa;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 8px 18px rgba(15,23,42,.08);
    display:flex;
    flex-direction:column;
    transition:.2s;
}

.product-card:hover{
    transform:translateY(-2px);
}

.product-img{
    width:100%;
    height:92px;
    object-fit:cover;
    display:block;
    background:#ffedd5;
}

.product-empty{
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:36px;
}

.product-body{
    padding:9px;
    display:flex;
    flex-direction:column;
    flex:1;
}

.product-body h3{
    font-size:13px;
    font-weight:900;
    color:#9a3412;
    margin:0 0 4px;
    line-height:1.2;
}

.product-merchant{
    font-size:11px;
    color:#6b7280;
    margin:0 0 6px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.merchant-card-link{
    text-decoration:none;
    color:inherit;
}

.product-desc{
    font-size:11px;
    color:#4b5563;
    line-height:1.3;
    margin:0 0 8px;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}

.product-bottom{
    margin-top:auto;
}

.product-price{
    color:#ea580c;
    font-size:13px;
    font-weight:900;
    margin-bottom:8px;
}

.product-cart-btn{
    display:block;
    width:100%;
    text-align:center;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:8px;
    border-radius:12px;
    font-size:11px;
    font-weight:900;
    text-decoration:none;
    box-shadow:0 6px 14px rgba(249,115,22,.22);
}

.empty-product{
    grid-column:1 / -1;
    background:#fff7ed;
    color:#9a3412;
    padding:14px;
    border-radius:16px;
    font-weight:900;
}

.merchant-near-card{
    margin-bottom:14px;
}

.driver-map-card{
    background:white;
    border:1px solid #fed7aa;
    border-radius:22px;
    padding:12px;
    margin-bottom:14px;
    box-shadow:0 8px 18px rgba(15,23,42,.08);
}

.driver-map-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
}

.driver-map-head h3{
    margin:0;
    color:#9a3412;
    font-size:17px;
    font-weight:900;
}

.driver-map-head p{
    margin:4px 0 0;
    color:#6b7280;
    font-size:12px;
}

.driver-count{
    min-width:56px;
    height:56px;
    border-radius:18px;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    box-shadow:0 8px 18px rgba(249,115,22,.22);
}

.driver-count span{
    font-size:20px;
    font-weight:900;
    line-height:1;
}

.driver-count small{
    font-size:10px;
    font-weight:800;
}

#customerDriverMap{
    width:100%;
    height:230px;
    border-radius:18px;
    overflow:hidden;
    background:#e5e7eb;
    z-index:1;
}

@media(min-width:768px){
    .product-grid{
        grid-template-columns:repeat(4,1fr);
        gap:14px;
    }

    .product-img{
        height:135px;
    }

    #customerDriverMap{
        height:290px;
    }

    .section-title{
        font-size:22px;
    }

    .driver-map-head h3{
        font-size:20px;
    }
}
</style>


<script>
let customerMap = null;
let customerMarker = null;
let customerRadius = null;
let customerDriverMarkers = [];
let customerLat = null;
let customerLng = null;
let firstCustomerMapLoad = true;

function initCustomerDriverMap(lat, lng){
    customerLat = parseFloat(lat);
    customerLng = parseFloat(lng);

    if(!customerMap){
        customerMap = L.map('customerDriverMap').setView([customerLat, customerLng], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(customerMap);

        setTimeout(function(){
            customerMap.invalidateSize();
        }, 700);
    }

    if(firstCustomerMapLoad){
        customerMap.setView([customerLat, customerLng], 15);
        firstCustomerMapLoad = false;
    }

    let userIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -26]
    });

    if(customerMarker){
        customerMarker.setLatLng([customerLat, customerLng]);
    }else{
        customerMarker = L.marker([customerLat, customerLng], {
            icon: userIcon
        })
        .addTo(customerMap)
        .bindPopup(`
            <div style="text-align:center;font-weight:900;color:#2563eb;">
                📍 Lokasi Anda
            </div>
        `);
    }

    if(customerRadius){
        customerRadius.setLatLng([customerLat, customerLng]);
    }else{
        customerRadius = L.circle([customerLat, customerLng], {
            radius: 100,
            color: '#2563eb',
            fillColor: '#3b82f6',
            fillOpacity: 0.12,
            weight: 2
        }).addTo(customerMap);
    }

    loadActiveDrivers();
}

function createDriverIcon(vehicleType){
    let icon = vehicleType === 'mobil' ? '🚗' : '🛵';

    return L.divIcon({
        className: '',
        html: `
            <div style="
                width:36px;
                height:36px;
                background:#22c55e;
                border:4px solid white;
                border-radius:50%;
                box-shadow:0 6px 18px rgba(0,0,0,.35);
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:17px;
            ">
                ${icon}
            </div>
        `,
        iconSize:[36,36],
        iconAnchor:[18,18],
        popupAnchor:[0,-18],
    });
}

function calculateDistanceKm(lat1, lng1, lat2, lng2){
    const R = 6371;

    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;

    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) *
        Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
}

function loadActiveDrivers(){
    if(!customerMap || customerLat === null || customerLng === null){
        return;
    }

    fetch('/active-drivers')
        .then(res => res.json())
        .then(data => {

            customerDriverMarkers.forEach(function(marker){
                customerMap.removeLayer(marker);
            });

            customerDriverMarkers = [];

            let count = 0;

            data.drivers.forEach(function(driver){

                let dLat = parseFloat(driver.latitude);
                let dLng = parseFloat(driver.longitude);

                if(!dLat || !dLng){
                    return;
                }

                let type = (driver.vehicle_type ?? 'motor').toLowerCase();

                if(type === 'mobil'){
                    dLng = dLng + 0.00018;
                }

                if(type === 'motor'){
                    dLng = dLng - 0.00018;
                }

                if(
                    Math.abs(dLat - customerLat) < 0.00001 &&
                    Math.abs(dLng - customerLng) < 0.00001
                ){
                    dLat = dLat + 0.00015;
                    dLng = dLng + 0.00015;
                }

                let distanceKm = driver.distance ?? calculateDistanceKm(
                    customerLat,
                    customerLng,
                    dLat,
                    dLng
                ).toFixed(1);

                let marker = L.marker([dLat, dLng], {
                    icon: createDriverIcon(type)
                })
                .addTo(customerMap)
                .bindPopup(`
                    <div style="min-width:180px;font-family:Segoe UI;">
                        <div style="font-weight:900;color:#ea580c;margin-bottom:6px;">
                            ${type === 'mobil' ? '🚗 Mobil Aktif' : '🛵 Motor Aktif'}
                        </div>

                        <div>
                            Status:
                            <b style="color:#16a34a;">Online</b>
                        </div>

                        <div>
                            Kendaraan:
                            <b>${type}</b>
                        </div>

                        <div>
                            Plat:
                            <b>${driver.plate_number ?? '-'}</b>
                        </div>

                        <div>
                            Jarak:
                            <b>${distanceKm} km</b>
                        </div>
                    </div>
                `);

                customerDriverMarkers.push(marker);
                count++;
            });

            document.getElementById('driverCount').innerText = count;

            document.getElementById('driverMapInfo').innerText =
                count > 0
                    ? count + ' driver aktif tersedia di sekitar Anda.'
                    : 'Belum ada driver aktif.';
        })
        .catch(function(error){
            console.log('active drivers error:', error);

            document.getElementById('driverMapInfo').innerText =
                'Gagal memuat data driver.';
        });
}

function startCustomerMap(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(
            function(pos){
                initCustomerDriverMap(
                    pos.coords.latitude,
                    pos.coords.longitude
                );
            },
            function(){
                initCustomerDriverMap(-6.5345831, 111.0390329);

                document.getElementById('driverMapInfo').innerText =
                    'GPS tidak aktif. Menampilkan lokasi default.';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }else{
        initCustomerDriverMap(-6.5345831, 111.0390329);
    }
}

document.addEventListener('DOMContentLoaded', function(){
    startCustomerMap();

    setInterval(function(){
        loadActiveDrivers();
    }, 10000);
});
</script>

@endsection