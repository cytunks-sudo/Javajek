@extends('layouts.driver-page')

@section('content')

<div class="food-card driver-hero">
    <div>
        <h2>
            Dashboard Driver
            <span id="driverNotifBadge" class="notif-badge">0</span>
        </h2>

        <p>Status: <b>{{ strtoupper($driver->status) }}</b></p>
        <p>Kendaraan: <b>{{ $driver->vehicle_type }}</b></p>
        <p>Plat: <b>{{ $driver->plate_number }}</b></p>
    </div>

    <div>
        @if($driver->status == 'offline')
            <a href="/driver/status/online" class="btn-mini green">
                GO ONLINE
            </a>
        @else
            <a href="/driver/status/offline" class="btn-mini red">
                GO OFFLINE
            </a>
        @endif
    </div>
</div>

<div class="food-card notif-box">
    <h3>🔔 Notifikasi Pesanan</h3>
    <p>Aktifkan agar HP berbunyi saat ada pesanan baru masuk.</p>

    <button id="enableNotifBtn" class="btn-mini green">
        🔔 Aktifkan Notifikasi HP
    </button>
</div>

<div class="food-card">
    <h3 class="order-title">Order Saya</h3>

    @forelse($orders as $order)

    @php
        $isFinished = in_array($order->status, ['cancelled', 'completed']);

        $deliveryFee = $order->delivery_fee ?? 0;

        $grandTotal = ($order->grand_total ?? 0) > 0
            ? $order->grand_total
            : ($order->total + $deliveryFee);
    @endphp

    <div class="order-card">
        <div class="order-head">
            <div>
                <b>Order #{{ $order->id }}</b>
                <p class="mini-text">
                    Status: {{ $order->status }}
                </p>
            </div>

            <span class="order-status {{ $order->status }}">
                {{ $order->status }}
            </span>
        </div>

        <div class="simple-total">
            Rp {{ number_format($grandTotal) }}
        </div>

        <div class="order-actions">
            <button type="button"
                    class="btn-mini blue"
                    onclick="openOrderDetail('orderDetail{{ $order->id }}')">
                Detail
            </button>

            @if(!$isFinished)

                @if($order->driver_status == 'pending')

                    <a href="/driver/order/{{ $order->id }}/accept" class="btn-mini green">
                        Terima
                    </a>

                    <a href="/driver/order/{{ $order->id }}/reject"
                       class="btn-mini red"
                       onclick="return confirm('Tolak pesanan ini?')">
                        Tolak
                    </a>

                @elseif($order->status == 'driver_to_merchant')

                    <a href="/driver/order/{{ $order->id }}/status/dalam_pengiriman"
                       class="btn-mini orange">
                        Pesanan Diambil
                    </a>

                @elseif($order->status == 'dalam_pengiriman')

                    <a href="/driver/order/{{ $order->id }}/status/completed"
                       class="btn-mini green">
                        Selesaikan
                    </a>

                @endif

            @endif
        </div>

        @if($order->status == 'cancelled')
            <div class="cancel-box">
                Pesanan dibatalkan.
            </div>
        @elseif($order->status == 'completed')
            <div class="success-box">
                Pesanan selesai.
            </div>
        @endif
    </div>

    <div id="orderDetail{{ $order->id }}" class="modal-detail">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Detail Order #{{ $order->id }}</h3>
                <button onclick="closeOrderDetail('orderDetail{{ $order->id }}')">×</button>
            </div>

            <div class="modal-body">
                <p><b>Status Order:</b> {{ $order->status }}</p>
                <p><b>Status Merchant:</b> {{ $order->merchant_status }}</p>
                <p><b>Status Driver:</b> {{ $order->driver_status }}</p>

                <hr>

                <p><b>Total Makanan:</b> Rp {{ number_format($order->total) }}</p>
                <p><b>Ongkir:</b> Rp {{ number_format($deliveryFee) }}</p>
                <p><b>Grand Total:</b> Rp {{ number_format($grandTotal) }}</p>

                <hr>

                <h4>Item Pesanan</h4>

                <div class="order-items">
                    @forelse($order->items as $item)
                        <div>• {{ $item->food->name ?? '-' }} x {{ $item->qty }}</div>
                    @empty
                        <div>Tidak ada item.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@empty

    <div class="empty-box">
        Belum ada order untuk driver ini.
    </div>

@endforelse
</div>

<style>
.driver-hero{
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    flex-wrap:wrap;
}

.driver-hero h2{
    font-size:28px;
    font-weight:900;
    margin:0 0 8px;
}

.notif-badge{
    display:none;
    background:#dc2626;
    color:white;
    border-radius:999px;
    padding:4px 9px;
    font-size:13px;
    margin-left:8px;
}

.notif-box{
    border:1px solid #bbf7d0;
    background:linear-gradient(135deg,#f0fdf4,#ffffff);
}

.notif-box h3{
    font-size:19px;
    font-weight:900;
    color:#166534;
    margin-bottom:8px;
}

.notif-box p{
    color:#166534;
    margin-bottom:12px;
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
    height:max-content;
}

.order-status.cancelled{
    background:#fee2e2;
    color:#991b1b;
}

.order-status.completed{
    background:#dcfce7;
    color:#166534;
}

.simple-total{
    color:#ea580c;
    font-size:18px;
    font-weight:900;
    margin:12px 0;
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

.cancel-box{
    margin-top:14px;
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:14px;
    font-weight:900;
}

.success-box{
    margin-top:14px;
    background:#dcfce7;
    color:#166534;
    padding:12px;
    border-radius:14px;
    font-weight:900;
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
    cursor:pointer;
}

@media(max-width:640px){
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

function openOrderDetail(id) {
    document.getElementById(id).style.display = 'block';
}

function closeOrderDetail(id) {
    document.getElementById(id).style.display = 'none';
}

window.addEventListener('click', function(e){
    document.querySelectorAll('.modal-detail').forEach(function(modal){
        if(e.target === modal){
            modal.style.display = 'none';
        }
    });
});

function updateNotifButton() {
    const btn = document.getElementById('enableNotifBtn');

    if (!btn) return;

    if (soundUnlocked || ('Notification' in window && Notification.permission === 'granted')) {
        btn.innerText = '🔔 Notifikasi Aktif';
        btn.disabled = true;
        btn.style.opacity = '0.75';
    }
}

function playOrderSound() {
    const audio = document.getElementById('orderSound');

    if (!audio) return;

    audio.currentTime = 0;

    audio.play().catch(function(err) {
        console.log('Audio gagal:', err);
    });
}

document.getElementById('enableNotifBtn')?.addEventListener('click', async function () {
    const audio = document.getElementById('orderSound');

    if (audio) {
        try {
            audio.volume = 1;
            await audio.play();
            audio.pause();
            audio.currentTime = 0;
            soundUnlocked = true;
        } catch (err) {
            console.log('Audio unlock gagal:', err);
        }
    }

    if ('Notification' in window) {
        await Notification.requestPermission();
    }

    updateNotifButton();
    playOrderSound();

    if ('vibrate' in navigator) {
        navigator.vibrate([300, 150, 300]);
    }

    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('JavaJek Driver Aktif', {
            body: 'Notifikasi driver sudah aktif.',
            icon: "{{ asset('images/logo.png') }}",
            vibrate: [300, 150, 300],
            requireInteraction: true
        });
    }
});

function showDriverOrderNotif() {
    playOrderSound();

    if ('vibrate' in navigator) {
        navigator.vibrate([500, 200, 500]);
    }

    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('🚗 Order Driver Baru', {
            body: 'Ada order baru untuk driver.',
            icon: "{{ asset('images/logo.png') }}",
            vibrate: [500, 200, 500],
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
        })
        .catch(err => {
            console.log('Driver notif error:', err);
        });
}

function updateDriverGPS()
{
    if (!navigator.geolocation) {
        console.log('GPS tidak didukung browser');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            fetch('/driver/location/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log('Lokasi driver update:', data);
            });
        },
        function(error) {
            console.log('GPS error:', error);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

document.addEventListener('DOMContentLoaded', function () {
    updateNotifButton();

    checkDriverNotif();
    setInterval(checkDriverNotif, 5000);

    updateDriverGPS();
    setInterval(updateDriverGPS, 10000);
});
</script>

@endsection