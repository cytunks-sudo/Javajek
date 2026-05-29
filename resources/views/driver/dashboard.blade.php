@extends('layouts.driver-page')

@section('content')

@php
    $saldo = $driver->saldo ?? $driver->balance ?? 0;
@endphp

<div class="driver-topbar">
    <div>
        <h2>JavaJek Driver</h2>
        <p>Status: <b>{{ strtoupper($driver->status) }}</b></p>
    </div>

    <button type="button" class="hamburger-btn" onclick="toggleDriverMenu()">
        ☰
    </button>
</div>

<div id="driverSideMenu" class="driver-dropdown-menu">

    <div class="menu-header">
        <h3>☰ Menu Driver</h3>

        <button
            type="button"
            class="menu-close"
            onclick="toggleDriverMenu()">
            ×
        </button>
    </div>

    <a href="/driver" class="driver-menu-item">
        <div class="driver-menu-icon">🏠</div>
        Dashboard
    </a>

    <a href="/driver/history" class="driver-menu-item">
        <div class="driver-menu-icon">📜</div>
        Riwayat Order
    </a>

    <a href="/driver/settings" class="driver-menu-item">
    <div class="driver-menu-icon">⚙️</div>
    Setting Driver
</a>

    <button
        type="button"
        id="enableNotifBtn"
        class="notif-btn">
        🔔 Aktifkan Notifikasi
    </button>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="logout-btn">
            Logout
        </button>
    </form>

</div>

<div class="driver-summary-grid">
    <div class="summary-card">
        <span>Saldo Driver</span>
        <h3>Rp {{ number_format($saldo) }}</h3>
    </div>

    <div class="summary-card">
        <span>Status Driver</span>

        @if($driver->status == 'offline')
            <a href="/driver/status/online" class="status-btn online">GO ONLINE</a>
        @else
            <a href="/driver/status/offline" class="status-btn offline">GO OFFLINE</a>
        @endif
    </div>
</div>

<div class="food-card map-card">
    <div class="section-head">
        <div>
            <h3>📍 Posisi Driver Saat Ini</h3>
            <p>Lokasi otomatis diperbarui.</p>
        </div>

        <span id="driverNotifBadge" class="notif-badge">0</span>
    </div>

    <div id="driverMap"></div>
</div>

<div class="food-card">
    <h3 class="order-title">Order Aktif</h3>

    @forelse($orders as $order)

        @php
            $isFinished = in_array($order->status, ['cancelled', 'completed']);
            $isOjek = $order->order_type == 'ojek';

            $deliveryFee = $order->delivery_fee ?? 0;

            $grandTotal = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : ($order->total + $deliveryFee);
        @endphp

        @if(!$isFinished)

        <div class="order-card">

            <div class="order-head">
                <div>
                    <b>
                        @if($isOjek)
                            🏍️ Ojek #{{ $order->id }}
                        @else
                            🍔 Food #{{ $order->id }}
                        @endif
                    </b>

                    <p class="mini-text">
                        {{ strtoupper(str_replace('_',' ', $order->status)) }}
                    </p>
                </div>

                <span class="order-status {{ $order->status }}">
                    {{ strtoupper(str_replace('_',' ', $order->status)) }}
                </span>
            </div>

            <div class="simple-total">
                Rp {{ number_format($grandTotal) }}
            </div>

            @if($isOjek)
                <div class="route-box">
                    <p><b>📍 Jemput:</b> {{ $order->pickup_address ?? '-' }}</p>
                    <p><b>🏁 Tujuan:</b> {{ $order->destination_address ?? '-' }}</p>

                    @if($order->distance_km)
                        <p><b>Jarak:</b> {{ number_format($order->distance_km,1) }} km</p>
                    @endif
                </div>
            @else
                <div class="route-box">
                    <p><b>🏪 Merchant:</b> {{ $order->restaurant->name ?? '-' }}</p>
                    <p><b>📍 Antar:</b> {{ $order->address ?? '-' }}</p>
                </div>
            @endif

            <div class="order-actions">

                <button type="button"
                        class="btn-mini blue"
                        onclick="openOrderDetail('orderDetail{{ $order->id }}')">
                    Detail
                </button>

                @if($order->driver_status == 'pending' || $order->status == 'searching_driver')

                    <a href="/driver/order/{{ $order->id }}/accept" class="btn-mini green">
                        Terima
                    </a>

                    <a href="/driver/order/{{ $order->id }}/reject"
                       class="btn-mini red"
                       onclick="return confirm('Tolak order ini?')">
                        Tolak
                    </a>

                @elseif($isOjek && $order->status == 'driver_to_pickup')

                    <a href="/driver/order/{{ $order->id }}/status/driver_to_destination"
                       class="btn-mini orange">
                        Sudah Jemput - Antar
                    </a>

                @elseif($isOjek && $order->status == 'driver_to_destination')

                    <a href="/driver/order/{{ $order->id }}/status/completed"
                       class="btn-mini green">
                        Selesai
                    </a>

                @elseif(!$isOjek && $order->status == 'driver_to_merchant')

                    <a href="/driver/order/{{ $order->id }}/status/dalam_pengiriman"
                       class="btn-mini orange">
                        Pesanan Diambil
                    </a>

                @elseif(!$isOjek && $order->status == 'dalam_pengiriman')

                    <a href="/driver/order/{{ $order->id }}/status/completed"
                       class="btn-mini green">
                        Selesaikan
                    </a>

                @endif

            </div>
        </div>

        <div id="orderDetail{{ $order->id }}" class="modal-detail">
            <div class="modal-box">
                <div class="modal-head">
                    <h3>Detail Order #{{ $order->id }}</h3>
                    <button type="button" onclick="closeOrderDetail('orderDetail{{ $order->id }}')">×</button>
                </div>

                <div class="modal-body">
                    <p><b>Jenis:</b> {{ $isOjek ? 'Ojek' : 'Food' }}</p>
                    <p><b>Status:</b> {{ $order->status }}</p>
                    <p><b>Driver:</b> {{ $order->driver_status }}</p>

                    <hr>

                    @if($isOjek)
                        <p><b>Jemput:</b> {{ $order->pickup_address ?? '-' }}</p>
                        <p><b>Tujuan:</b> {{ $order->destination_address ?? '-' }}</p>
                        <p><b>Jarak:</b> {{ number_format($order->distance_km ?? 0,1) }} km</p>
                        <p><b>Tarif:</b> Rp {{ number_format($order->total) }}</p>
                    @else
                        <p><b>Merchant:</b> {{ $order->restaurant->name ?? '-' }}</p>
                        <p><b>Alamat Antar:</b> {{ $order->address ?? '-' }}</p>
                        <p><b>Total:</b> Rp {{ number_format($grandTotal) }}</p>

                        <hr>

                        <h4>Item Pesanan</h4>

                        <div class="order-items">
                            @forelse($order->items as $item)
                                <div>• {{ $item->food->name ?? '-' }} x {{ $item->qty }}</div>
                            @empty
                                <div>Tidak ada item.</div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @endif

    @empty
        <div class="empty-box">
            Belum ada order aktif.
        </div>
    @endforelse
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.driver-topbar{
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
    border-radius:28px;
    padding:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:16px;
    box-shadow:0 12px 28px rgba(249,115,22,.22);
}

.driver-topbar h2{
    margin:0 0 4px;
    font-size:26px;
    font-weight:900;
}

.driver-topbar p{
    margin:0;
}
.driver-dropdown-menu{
    display:none;
    position:absolute;
    top:70px;
    right:15px;

    width:280px;

    background:rgba(255,255,255,.96);
    backdrop-filter:blur(18px);

    border-radius:28px;

    padding:18px;

    box-shadow:
        0 20px 45px rgba(15,23,42,.18),
        0 4px 10px rgba(15,23,42,.08);

    border:1px solid rgba(255,255,255,.7);

    z-index:9999;
}

.driver-dropdown-menu.show{
    display:block;
    animation:menuShow .25s ease;
}

@keyframes menuShow{
    from{
        opacity:0;
        transform:translateY(-10px) scale(.95);
    }
    to{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}

.menu-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:16px;
}

.menu-header h3{
    margin:0;
    font-size:22px;
    font-weight:900;
    color:#9a3412;
}

.menu-close{
    width:38px;
    height:38px;
    border:none;
    border-radius:50%;
    background:#fff1f2;
    color:#dc2626;
    font-size:22px;
    cursor:pointer;
}

.driver-menu-item{
    display:flex;
    align-items:center;
    gap:12px;

    padding:14px 16px;

    border-radius:18px;

    text-decoration:none;

    color:#374151;

    font-weight:800;

    transition:.2s;
}

.driver-menu-item:hover{
    background:#fff7ed;
    color:#ea580c;
    transform:translateX(4px);
}

.driver-menu-icon{
    width:42px;
    height:42px;

    border-radius:14px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:#fff7ed;

    font-size:20px;
}

.notif-btn{
    width:100%;
    border:none;
    padding:14px 16px;
    border-radius:18px;
    background:#dcfce7;
    color:#166534;
    font-weight:900;
    cursor:pointer;
    margin-top:8px;
}

.logout-btn{
    width:100%;
    border:none;
    padding:14px;
    border-radius:18px;
    background:#fee2e2;
    color:#b91c1c;
    font-weight:900;
    cursor:pointer;
    margin-top:10px;
}

.hamburger-btn{
    width:48px;
    height:48px;
    border:none;
    border-radius:16px;
    background:white;
    color:#f97316;
    font-size:26px;
    font-weight:900;
    cursor:pointer;
    position:relative;
    z-index:10;
}
.driver-topbar{
    position:relative;
}

.driver-dropdown-menu{
    display:none;
    position:absolute;
    top:76px;
    right:18px;
    width:250px;
    background:white;
    z-index:9999;
    padding:14px;
    border-radius:22px;
    box-shadow:0 18px 40px rgba(15,23,42,.22);
    flex-direction:column;
    gap:10px;
}

.driver-dropdown-menu.show{
    display:flex;
}
.driver-summary-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
    margin-bottom:16px;
}

.summary-card{
    background:white;
    border:1px solid #fed7aa;
    border-radius:24px;
    padding:16px;
    box-shadow:0 10px 24px rgba(15,23,42,.07);
}

.summary-card span{
    color:#6b7280;
    font-size:13px;
    font-weight:800;
}

.summary-card h3{
    margin:8px 0 0;
    font-size:26px;
    color:#ea580c;
    font-weight:900;
}

.status-btn{
    display:block;
    margin-top:10px;
    text-align:center;
    padding:14px;
    border-radius:16px;
    text-decoration:none;
    color:white;
    font-weight:900;
}

.status-btn.online{background:#16a34a;}
.status-btn.offline{background:#dc2626;}

.map-card{
    padding:16px;
}

.section-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
    margin-bottom:12px;
}

.section-head h3{
    margin:0;
    font-size:20px;
    color:#9a3412;
    font-weight:900;
}

.section-head p{
    margin:4px 0 0;
    color:#6b7280;
    font-size:13px;
}

#driverMap{
    width:100%;
    height:260px;
    border-radius:22px;
    overflow:hidden;
    background:#e5e7eb;
}

.notif-badge{
    display:none;
    background:#dc2626;
    color:white;
    border-radius:999px;
    padding:5px 10px;
    font-size:13px;
    font-weight:900;
}

.order-title{
    font-size:22px;
    font-weight:900;
    color:#9a3412;
    margin-bottom:16px;
}

.order-card{
    border:1px solid #fed7aa;
    border-radius:22px;
    padding:16px;
    margin-bottom:16px;
    background:linear-gradient(135deg,#fff,#fff7ed);
}

.order-head{
    display:flex;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
}

.order-head b{
    font-size:18px;
    color:#9a3412;
}

.mini-text{
    margin:4px 0 0;
    color:#6b7280;
    font-size:13px;
}

.order-status{
    background:#ffedd5;
    color:#9a3412;
    padding:6px 11px;
    border-radius:999px;
    font-weight:900;
    font-size:12px;
}

.order-status.driver_to_pickup,
.order-status.driver_to_destination{
    background:#dbeafe;
    color:#1d4ed8;
}

.simple-total{
    color:#ea580c;
    font-size:18px;
    font-weight:900;
    margin:12px 0;
}

.route-box{
    background:white;
    border-radius:16px;
    padding:12px;
    margin-bottom:14px;
    border:1px dashed #fed7aa;
}

.route-box p{
    margin:0 0 8px;
    color:#374151;
    line-height:1.45;
}

.order-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn-mini{
    border:none;
    background:#f97316;
    color:white;
    padding:10px 14px;
    border-radius:14px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
    display:inline-block;
}

.btn-mini.blue{background:#0ea5e9;}
.btn-mini.green{background:#16a34a;}
.btn-mini.red{background:#dc2626;}
.btn-mini.orange{background:#f97316;}

.empty-box{
    background:#fff7ed;
    border-radius:16px;
    padding:14px;
    color:#9a3412;
    font-weight:800;
}

.order-items{
    background:white;
    border-radius:16px;
    padding:12px;
    margin:12px 0;
}

.modal-detail{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.55);
    z-index:9999;
    padding:24px;
    overflow-y:auto;
}

.modal-box{
    background:white;
    max-width:620px;
    margin:auto;
    border-radius:24px;
    padding:20px;
}

.modal-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #fed7aa;
    padding-bottom:12px;
    margin-bottom:14px;
}

.modal-head h3{
    font-size:22px;
    font-weight:900;
    color:#9a3412;
}

.modal-head button{
    width:38px;
    height:38px;
    border:none;
    border-radius:50%;
    background:#fee2e2;
    color:#991b1b;
    font-size:24px;
}

@media(max-width:640px){
    .driver-summary-grid{
        grid-template-columns:1fr;
    }

    .btn-mini{
        flex:1;
        text-align:center;
    }
}
</style>

<audio id="orderSound" preload="auto">
    <source src="{{ asset('sounds/order.mp3') }}" type="audio/mpeg">
</audio>

<script>
let lastDriverNotifCount = 0;
let driverNotifReady = false;
let soundUnlocked = false;
let firstMapLoad = true;
let driverMap = null;
let driverMarker = null;
let otherDriverMarkers = [];

function toggleDriverMenu(){
    document.getElementById('driverSideMenu').classList.toggle('show');
}

function openOrderDetail(id) {
    document.getElementById(id).style.display = 'block';
}

function closeOrderDetail(id) {
    document.getElementById(id).style.display = 'none';
}

function createVehicleIcon(vehicleType, isMe = false){
    let icon = vehicleType === 'mobil' ? '🚗' : '🛵';
    let color = isMe ? '#f97316' : '#22c55e';

    return L.divIcon({
        className: '',
        html: `
            <div style="
                width:36px;
                height:36px;
                background:${color};
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

function initDriverMap(lat, lng){
    if(!driverMap){
        driverMap = L.map('driverMap').setView([lat, lng], 16);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(driverMap);

        setTimeout(function(){
            driverMap.invalidateSize();
        }, 700);
    }

    if(driverMarker){
        driverMarker.setLatLng([lat, lng]);
    }else{
        driverMarker = L.marker([lat, lng], {
            icon: createVehicleIcon('motor', true)
        })
        .addTo(driverMap)
        .bindPopup('📍 Posisi Anda Saat Ini')
        .openPopup();
    }

    if (firstMapLoad) {
    driverMap.setView([lat, lng], 16);
    firstMapLoad = false;
}
    loadActiveDrivers(lat, lng);
}

function loadActiveDrivers(myLat, myLng){
    fetch('/driver/active-locations')
        .then(res => res.json())
        .then(data => {

            otherDriverMarkers.forEach(function(marker){
                driverMap.removeLayer(marker);
            });

            otherDriverMarkers = [];

            data.drivers.forEach(function(driver){

    if (Number(driver.driver_id) === {{ $driver->id }}) {
        return;
    }

    let lat = parseFloat(driver.latitude);
    let lng = parseFloat(driver.longitude);

    if(!lat || !lng){
        return;
    }

    let marker = L.marker([lat, lng], {
        icon: createVehicleIcon(driver.vehicle_type)
    })
    .addTo(driverMap)
    .bindPopup(`
        <b>${driver.name}</b><br>
        ${driver.vehicle_type ?? '-'}<br>
        ${driver.plate_number ?? '-'}
    `);

    otherDriverMarkers.push(marker);
});
        })
        .catch(function(error){
            console.log('Driver aktif error:', error);
        });
}

function updateDriverGPS(){
    if (!navigator.geolocation) return;

    navigator.geolocation.getCurrentPosition(function(position) {
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        initDriverMap(lat, lng);

        fetch('/driver/location/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitude: lat,
                longitude: lng
            })
        });
    });
}

function updateNotifButton() {
    const btn = document.getElementById('enableNotifBtn');
    if (!btn) return;

    btn.innerText = ('Notification' in window && Notification.permission === 'granted')
        ? '🔔 Notifikasi Aktif / Tes Bunyi'
        : '🔔 Aktifkan Notifikasi';

    btn.disabled = false;
}

function playOrderSound() {
    const audio = document.getElementById('orderSound');
    if (!audio) return;

    audio.currentTime = 0;
    audio.play().catch(function(err) {
        console.log('Audio gagal:', err);
    });
}

document.addEventListener('click', async function(e){
    if(e.target && e.target.id === 'enableNotifBtn'){
        const audio = document.getElementById('orderSound');

        if (audio) {
            try {
                audio.volume = 1;
                await audio.play();
                audio.pause();
                audio.currentTime = 0;
                soundUnlocked = true;
            } catch (err) {}
        }

        if ('Notification' in window) {
            await Notification.requestPermission();
        }

        updateNotifButton();
        playOrderSound();
    }
});

function showDriverOrderNotif() {
    playOrderSound();

    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('🚗 Order Driver Baru', {
            body: 'Ada order baru untuk driver.',
            icon: "{{ asset('images/logo.png') }}",
            requireInteraction: true
        });
    }
}

function checkDriverNotif() {
    fetch("{{ url('/driver/notif-count') }}")
        .then(res => res.json())
        .then(data => {
            const count = parseInt(data.count ?? 0);

            if (driverNotifReady && count > lastDriverNotifCount) {
                showDriverOrderNotif();
            }

            lastDriverNotifCount = count;
            driverNotifReady = true;

            const badge = document.getElementById('driverNotifBadge');

            if (badge) {
                badge.innerText = count;
                badge.style.display = count > 0 ? 'inline-block' : 'none';
            }
        });
}

document.addEventListener('DOMContentLoaded', function () {
    updateNotifButton();

    updateDriverGPS();
    setInterval(updateDriverGPS, 10000);

    checkDriverNotif();
    setInterval(checkDriverNotif, 5000);
});
</script>
@endsection