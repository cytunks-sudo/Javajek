@extends('layouts.merchant-page')

@section('content')

<div class="food-card merchant-hero">
    <div>
        <h2>
            Dashboard Merchant
            <span id="merchantNotifBadge" class="notif-badge">0</span>
        </h2>
        <p>Fokus kelola pesanan masuk customer.</p>
    </div>

    <div class="merchant-menu-wrap">
        <button type="button" class="merchant-menu-btn" onclick="toggleMerchantMenu()">
            ☰
        </button>

        <div id="merchantDropdown" class="merchant-dropdown">
            <a href="/merchant/foods" class="merchant-menu-item">
                🍔 Daftar Menu
            </a>

            @forelse($restaurants as $restaurant)
                <a href="/merchant/restaurants/{{ $restaurant->id }}/edit" class="merchant-menu-item">
                    ⚙️ Setting Restoran
                </a>

                <a href="/merchant/restaurants/{{ $restaurant->id }}/toggle-open"
                   class="merchant-menu-item {{ $restaurant->manual_closed ? 'green-text' : 'red-text' }}">
                    {{ $restaurant->manual_closed ? '✅ Buka Manual' : '⛔ Tutup Manual' }}
                </a>
            @empty
                <div class="merchant-menu-empty">
                    Belum ada restoran.
                </div>
            @endforelse

            <form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit" class="merchant-menu-item logout-btn">
        🚪 Logout
    </button>
</form>
        </div>
    </div>
</div>

@php
    $mainRestaurant = $restaurants->first();

    $merchantOpenText = 'Belum ada restoran';
    $merchantOpenClass = 'close';

    if ($mainRestaurant) {
        $merchantOpenText = $mainRestaurant->manual_closed ? 'TOKO TUTUP' : 'TOKO BUKA';
        $merchantOpenClass = $mainRestaurant->manual_closed ? 'close' : 'open';
    }
@endphp

<div class="food-card notif-box">
    <div class="notif-head">
        <div>
            <h3>🔔 Notifikasi Pesanan</h3>
            <p>Aktifkan agar HP berbunyi saat ada pesanan baru masuk.</p>
        </div>

        <div class="merchant-open-badge {{ $merchantOpenClass }}">
            {{ $merchantOpenText }}
        </div>
    </div>

    <button id="enableNotifBtn" class="btn-mini green">
        🔔 Notifikasi Aktif
    </button>
</div>

<div class="food-card">
    <h3 class="order-title">Pesanan Masuk</h3>

    @forelse($orders as $order)

        @php
            $isFinished = in_array($order->status, ['cancelled', 'completed']);

            $deliveryFee = $order->delivery_fee ?? 0;

            $grandTotal = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : ($order->total + $deliveryFee);
        @endphp

        <div class="order-card simple-order">
            <div class="order-head">
                <div>
                    <b>Order #{{ $order->id }}</b>
                    <p class="mini-text">
                        Customer: {{ $order->user->name ?? '-' }}
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
                    <a href="/merchant/orders/{{ $order->id }}/reject"
                       class="btn-mini red"
                       onclick="return confirm('Tolak dan batalkan pesanan ini?')">
                        Tolak Pesanan
                    </a>
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
                    <p><b>Customer:</b> {{ $order->user->name ?? '-' }}</p>
                    <p><b>Status Order:</b> {{ $order->status }}</p>
                    <p><b>Status Driver:</b> {{ $order->driver_status }}</p>
                    <p><b>Status Merchant:</b> {{ $order->merchant_status }}</p>

                    <hr>

                    <p><b>Total Makanan:</b> Rp {{ number_format($order->total) }}</p>
                    <p><b>Ongkir:</b> Rp {{ number_format($deliveryFee) }}</p>
                    <p><b>Grand Total:</b> Rp {{ number_format($grandTotal) }}</p>

                    <hr>

                    <h4>Item Pesanan</h4>

                    <div class="order-items">
                        @forelse($order->items as $item)
                            <div>
                                • {{ $item->food->name ?? '-' }} x {{ $item->qty }}
                            </div>
                        @empty
                            <div>Tidak ada item.</div>
                        @endforelse
                    </div>

                    <br>

                    <button onclick="printOrder(
                        '{{ $order->id }}',
                        '{{ $order->restaurant->name ?? "Merchant" }}'
                    )"
                    class="btn-mini green">
                        🖨️ Cetak Nota
                    </button>

                    @if($order->status == 'cancelled')
                        <div class="cancel-box">
                            Pesanan ini sudah dibatalkan.
                        </div>
                    @elseif($order->status == 'completed')
                        <div class="success-box">
                            Pesanan ini sudah selesai.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @empty
        <div class="empty-box">
            Belum ada pesanan.
        </div>
    @endforelse
</div>

<style>
.merchant-hero{
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    flex-wrap:wrap;
    position:relative;
}

.merchant-hero h2{
    font-size:28px;
    font-weight:900;
    margin:0;
}

.merchant-hero p{
    margin-top:6px;
    opacity:.95;
}

.notif-badge{
    display:none;
    background:#dc2626;
    color:white;
    border-radius:999px;
    padding:4px 9px;
    font-size:13px;
    margin-left:8px;
    vertical-align:middle;
}
.notif-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
    flex-wrap:wrap;
}

.merchant-open-badge{
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.merchant-open-badge.open{
    background:#dcfce7;
    color:#166534;
}

.merchant-open-badge.close{
    background:#fee2e2;
    color:#991b1b;
}
.merchant-menu-wrap{
    position:relative;
}

.merchant-menu-btn{
    width:48px;
    height:48px;
    border:none;
    border-radius:16px;
    background:white;
    color:#f97316;
    font-size:24px;
    font-weight:900;
    cursor:pointer;
    box-shadow:0 10px 22px rgba(0,0,0,.12);
}

.merchant-dropdown{
    position:absolute;
    right:0;
    top:58px;
    width:240px;
    background:white;
    border-radius:22px;
    padding:10px;
    box-shadow:0 20px 45px rgba(0,0,0,.18);
    display:none;
    z-index:99999;
}

.merchant-menu-item{
    display:block;
    padding:13px 14px;
    border-radius:15px;
    color:#9a3412;
    text-decoration:none;
    font-weight:900;
    margin-bottom:5px;
}

.merchant-menu-item:hover{
    background:#fff7ed;
}

.green-text{color:#16a34a;}
.red-text{color:#dc2626;}

.merchant-menu-empty{
    padding:13px 14px;
    color:#991b1b;
    font-weight:800;
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

.btn-mini{
    border:none;
    background:#f97316;
    color:white;
    padding:9px 13px;
    border-radius:14px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
    display:inline-block;
}

.btn-mini.blue{background:#0ea5e9;}
.btn-mini.green{background:#16a34a;}
.btn-mini.red{background:#dc2626;}

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

.simple-order{
    display:flex;
    flex-direction:column;
    gap:12px;
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
}

.order-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.order-items{
    background:white;
    border-radius:16px;
    padding:12px;
    margin:12px 0;
}

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
.logout-btn{
    width:100%;
    border:none;
    text-align:left;
    background:none;
    cursor:pointer;
}
@media(max-width:640px){
    .btn-mini{
        flex:1;
        text-align:center;
    }

    .merchant-dropdown{
        right:0;
        width:230px;
    }
}
</style>

<audio id="orderSound" preload="auto">
    <source src="{{ asset('sounds/order.mp3') }}" type="audio/mpeg">
</audio>

<script>
let lastMerchantNotifCount = 0;
let merchantNotifReady = false;
let soundUnlocked = false;

function toggleMerchantMenu()
{
    const menu = document.getElementById('merchantDropdown');

    if (!menu) return;

    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

window.addEventListener('click', function(e){
    const menu = document.getElementById('merchantDropdown');
    const btn = document.querySelector('.merchant-menu-btn');

    if (menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) {
        menu.style.display = 'none';
    }

    document.querySelectorAll('.modal-detail').forEach(function(modal){
        if(e.target === modal){
            modal.style.display = 'none';
        }
    });
});

function openOrderDetail(id) {
    document.getElementById(id).style.display = 'block';
}

function closeOrderDetail(id) {
    document.getElementById(id).style.display = 'none';
}

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
        new Notification('JavaJek Merchant Aktif', {
            body: 'Notifikasi merchant sudah aktif.',
            icon: "{{ asset('images/logo.png') }}",
            vibrate: [300, 150, 300],
            requireInteraction: true
        });
    }
});

function showMerchantOrderNotif() {
    playOrderSound();

    if ('vibrate' in navigator) {
        navigator.vibrate([500, 200, 500]);
    }

    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('🍽️ Pesanan Baru Masuk', {
            body: 'Ada pesanan baru untuk merchant.',
            icon: "{{ asset('images/logo.png') }}",
            vibrate: [500, 200, 500],
            requireInteraction: true
        });
    }
}

function checkMerchantNotif() {
    fetch("{{ url('/merchant/notif-count') }}")
        .then(res => res.json())
        .then(data => {
            const count = parseInt(data.count ?? 0);

            if (merchantNotifReady && count > lastMerchantNotifCount) {
                showMerchantOrderNotif();
            }

            lastMerchantNotifCount = count;
            merchantNotifReady = true;

            const badge = document.getElementById('merchantNotifBadge');

            if (badge) {
                badge.innerText = count;
                badge.style.display = count > 0 ? 'inline-block' : 'none';
            }
        })
        .catch(err => {
            console.log('Merchant notif error:', err);
        });
}

function printOrder(orderId, merchantName)
{
    const content = document.querySelector('#orderDetail' + orderId + ' .modal-body').innerHTML;

    const printWindow = window.open('', '', 'width=800,height=900');

    printWindow.document.write(`
        <html>
        <head>
            <title>Nota Order #${orderId}</title>
            <style>
                body{
                    font-family:Arial,sans-serif;
                    padding:25px;
                    color:#111;
                }

                hr{
                    margin:18px 0;
                }

                .print-head{
                    text-align:center;
                    margin-bottom:22px;
                }

                .print-head h1{
                    margin:0;
                    color:#ea580c;
                    font-size:30px;
                }

                .merchant-name{
                    margin-top:6px;
                    font-size:18px;
                    font-weight:bold;
                }

                .print-head small{
                    color:#666;
                }

                .btn-mini,
                button{
                    display:none !important;
                }

                .cancel-box,
                .success-box{
                    margin-top:15px;
                    padding:10px;
                    border-radius:10px;
                }

                .cancel-box{
                    background:#fee2e2;
                }

                .success-box{
                    background:#dcfce7;
                }
            </style>
        </head>

        <body>
            <div class="print-head">
                <h1>JavaJek Food</h1>
                <div class="merchant-name">${merchantName}</div>
                <small>Nota Merchant</small>
            </div>

            ${content}
        </body>
        </html>
    `);

    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 500);
}

document.addEventListener('DOMContentLoaded', function () {
    updateNotifButton();
    checkMerchantNotif();
    setInterval(checkMerchantNotif, 5000);
});
</script>

@endsection