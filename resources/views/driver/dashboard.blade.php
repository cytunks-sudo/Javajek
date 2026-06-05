@extends('layouts.driver-page')

@section('content')

@php
    $saldo = $driver->saldo ?? $driver->balance ?? 0;
@endphp

<div class="driver-dashboard">

   @if(session('success'))
<div id="toastSuccess" class="driver-toast success">
    ✅ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="toastError" class="driver-toast error">
    ❌ {{ session('error') }}
</div>
@endif

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

            <button type="button" class="menu-close" onclick="toggleDriverMenu()">
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

        <a href="/driver/wallet-history" class="driver-menu-item">
    <div class="driver-menu-icon">💰</div>
    Riwayat Saldo
</a>
<a href="/driver/income" class="driver-menu-item">
    <div class="driver-menu-icon">💵</div>
    Pendapatan Driver
</a>
        <a href="/driver/settings" class="driver-menu-item">
            <div class="driver-menu-icon">⚙️</div>
            Setting Driver
        </a>

        <button type="button" id="enableNotifBtn" class="notif-btn">
            🔔 Aktifkan Notifikasi
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>

    </div>
    @if($walletWarning)

<div class="wallet-warning-card">

    <div class="wallet-warning-icon">
        ⚠️
    </div>

    <div>

        <div class="wallet-warning-title">
            Saldo Driver Bermasalah
        </div>

        <div class="wallet-warning-text">
            {{ $walletWarning }}
        </div>

    </div>

</div>

@endif
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

<div class="driver-income-grid">

    <div class="income-card">
        <small>💰 Pendapatan Hari Ini</small>
        <h3>Rp {{ number_format($driverIncomeToday ?? 0) }}</h3>
    </div>

    <div class="income-card">
        <small>📈 Pendapatan Bulan Ini</small>
        <h3>Rp {{ number_format($driverIncomeMonth ?? 0) }}</h3>
    </div>

    <div class="income-card">
        <small>📦 Order Selesai</small>
        <h3>{{ $driverCompletedOrders ?? 0 }}</h3>
    </div>

    <div class="income-card danger">
        <small>💸 Komisi Admin</small>
        <h3>Rp {{ number_format($driverCommissionTotal ?? 0) }}</h3>
    </div>

</div>

<div class="rating-map-grid">

    <div class="income-card rating-card">
        <small>⭐ Rating Driver</small>

        <h3>{{ number_format($driverAverageRating ?? 0, 1) }} / 5</h3>

        <p class="rating-mini">
            {{ $driverTotalReviews ?? 0 }} ulasan
        </p>

        <div class="mini-review-list">
            @forelse($driverLatestReviews as $review)
                <div class="mini-review-item">
                    <div class="mini-review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->driver_rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>

                    <div class="mini-review-text">
                        {{ $review->driver_review ?: 'Tidak ada ulasan.' }}
                    </div>
                </div>
            @empty
                <div class="mini-review-empty">
                    Belum ada ulasan.
                </div>
            @endforelse
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

</div>

     <div class="food-card">
        <h3 class="order-title">Order Aktif</h3>

        @forelse($orders as $order)

            @php
                $isFinished = in_array($order->status, ['cancelled', 'completed']);
                $isRide = in_array($order->order_type, ['ojek', 'car']);
                $isCar = $order->order_type == 'car';
                $isOjek = $order->order_type == 'ojek';
                $isFood = !$isRide;

                $deliveryFee = $order->delivery_fee ?? 0;

                $grandTotal = ($order->grand_total ?? 0) > 0
                    ? $order->grand_total
                    : ($order->total ?? 0);
            @endphp

            @if(!$isFinished)

            <div class="order-card">

                <div class="order-head">
                    <div>
                        <b>
                            @if($isOjek)
                                🏍️ J-Ride {{ $order->order_number ?? '#'.$order->id }}
                            @elseif($isCar)
                                🚗 J-Car {{ $order->order_number ?? '#'.$order->id }}
                            @else
                                🍔 Food {{ $order->order_number ?? '#'.$order->id }}
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

                @if($isRide)

                    <div class="route-box">

                        <div class="address-info">
                            <small>📍 Titik Jemput</small>
                            <p>{{ $order->pickup_address ?? '-' }}</p>
                        </div>

                        <div class="address-info">
                            <small>🏁 Titik Tujuan</small>
                            <p>{{ $order->destination_address ?? '-' }}</p>
                        </div>

                        <div class="map-action-row">
                            @if($order->pickup_latitude && $order->pickup_longitude)
                                <a class="btn-mini blue"
                                   target="_blank"
                                   href="https://www.google.com/maps/dir/?api=1&destination={{ $order->pickup_latitude }},{{ $order->pickup_longitude }}">
                                    📍 Buka Titik Jemput
                                </a>
                            @endif

                            @if($order->destination_latitude && $order->destination_longitude)
                                <a class="btn-mini green"
                                   target="_blank"
                                   href="https://www.google.com/maps/dir/?api=1&destination={{ $order->destination_latitude }},{{ $order->destination_longitude }}">
                                    🏁 Buka Tujuan
                                </a>
                            @endif
                        </div>

                        @if($order->distance_km)
                            <p class="distance-text">
                                <b>Jarak:</b> {{ number_format($order->distance_km,1) }} km
                            </p>
                        @endif

                    </div>

                @else

                    <div class="route-box">

                        <div class="address-info">
                            <small>🏪 Merchant</small>
                            <p>{{ $order->restaurant->name ?? '-' }}</p>
                        </div>

                        <div class="address-info">
                            <small>📍 Alamat Customer</small>
                            <p>{{ $order->address ?? '-' }}</p>
                        </div>

                        <div class="map-action-row">
                            @if($order->restaurant && $order->restaurant->latitude && $order->restaurant->longitude)
                                <a class="btn-mini blue"
                                   target="_blank"
                                   href="https://www.google.com/maps/dir/?api=1&destination={{ $order->restaurant->latitude }},{{ $order->restaurant->longitude }}">
                                    🏪 Buka Merchant
                                </a>
                            @endif

                            @if($order->latitude && $order->longitude)
                                <a class="btn-mini green"
                                   target="_blank"
                                   href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}">
                                    📍 Buka Alamat Customer
                                </a>
                            @endif
                        </div>

                    </div>

                @endif

                <div class="order-actions">

                    @php
    if($isRide){
        $routeStartLat = $order->pickup_latitude;
        $routeStartLng = $order->pickup_longitude;
        $routeEndLat = $order->destination_latitude;
        $routeEndLng = $order->destination_longitude;
    }else{
        $routeStartLat = $order->restaurant->latitude ?? null;
        $routeStartLng = $order->restaurant->longitude ?? null;
        $routeEndLat = $order->latitude;
        $routeEndLng = $order->longitude;
    }

    $hasRouteMap = $routeStartLat && $routeStartLng && $routeEndLat && $routeEndLng;
@endphp

<button type="button"
        class="btn-mini blue"
        onclick="openOrderDetailWithRoute(
            'orderDetail{{ $order->id }}',
            'routeMap{{ $order->id }}',
            {{ $hasRouteMap ? $routeStartLat : 'null' }},
            {{ $hasRouteMap ? $routeStartLng : 'null' }},
            {{ $hasRouteMap ? $routeEndLat : 'null' }},
            {{ $hasRouteMap ? $routeEndLng : 'null' }}
        )">
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

                    @elseif($isRide && $order->status == 'driver_to_pickup')

                        <a href="/driver/order/{{ $order->id }}/status/driver_to_destination"
                           class="btn-mini orange">
                            Sudah Jemput - Antar
                        </a>

                    @elseif($isRide && $order->status == 'driver_to_destination')

                        <a href="/driver/order/{{ $order->id }}/status/completed"
                           class="btn-mini green">
                            Selesai
                        </a>

                    @elseif($isFood && $order->status == 'driver_to_merchant')

                        <a href="/driver/order/{{ $order->id }}/status/dalam_pengiriman"
                           class="btn-mini orange">
                            Pesanan Diambil
                        </a>

                    @elseif($isFood && $order->status == 'dalam_pengiriman')

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
                        <h3>Detail Order</h3>
                        <button type="button" onclick="closeOrderDetail('orderDetail{{ $order->id }}')">×</button>
                    </div>

                    <div class="modal-body">
                        <p>
                            <b>No Order:</b>
                            {{ $order->order_number ?? '#'.$order->id }}
                        </p>

                        <p>
                            <b>Jenis:</b>
                            @if($isOjek)
                                J-Ride
                            @elseif($isCar)
                                J-Car
                            @else
                                Food
                            @endif
                        </p>

                        <p><b>Status:</b> {{ strtoupper(str_replace('_',' ', $order->status)) }}</p>
                        <p><b>Driver:</b> {{ strtoupper($order->driver_status) }}</p>

                        <hr>

                        @if($isRide)

                            <div class="address-info">
                                <small>📍 Jemput</small>
                                <p>{{ $order->pickup_address ?? '-' }}</p>
                            </div>

                            <div class="address-info">
                                <small>🏁 Tujuan</small>
                                <p>{{ $order->destination_address ?? '-' }}</p>
                            </div>

                            <p><b>Jarak:</b> {{ number_format($order->distance_km ?? 0,1) }} km</p>
                            <p><b>Tarif:</b> Rp {{ number_format($order->total) }}</p>

                        @else

                            <div class="address-info">
                                <small>🏪 Merchant</small>
                                <p>{{ $order->restaurant->name ?? '-' }}</p>
                            </div>

                            <div class="address-info">
                                <small>📍 Alamat Antar</small>
                                <p>{{ $order->address ?? '-' }}</p>
                            </div>

                            <p><b>Total:</b> Rp {{ number_format($grandTotal) }}</p>
                            @php
    $foodOriginalTotal = 0;
    $foodSellingTotal = 0;

    foreach($order->items as $item){
        $qty = $item->qty ?? 1;
        $originalPrice = $item->food->price ?? $item->price;
        $sellingPrice = $item->price ?? $originalPrice;

        $foodOriginalTotal += $originalPrice * $qty;
        $foodSellingTotal += $sellingPrice * $qty;
    }

    $foodMarkupAmount = max(0, $foodSellingTotal - $foodOriginalTotal);

    $appSetting = \App\Models\AppSetting::first();
    $deliveryCommissionPercent = $appSetting->food_driver_commission_percent ?? 0;

    $deliveryCommissionAmount = round(($order->delivery_fee ?? 0) * ($deliveryCommissionPercent / 100));

    $driverCutTotal = $foodMarkupAmount + $deliveryCommissionAmount;
@endphp

<div class="driver-commission-box">
    <h4>💰 Rincian Komisi Admin</h4>

    <div class="commission-row">
        <span>Total customer bayar</span>
        <b>Rp {{ number_format($grandTotal) }}</b>
    </div>

    <div class="commission-row">
        <span>Harga asli merchant</span>
        <b>Rp {{ number_format($foodOriginalTotal) }}</b>
    </div>

    <div class="commission-row">
        <span>Markup food</span>
        <b>Rp {{ number_format($foodMarkupAmount) }}</b>
    </div>

    <div class="commission-row">
        <span>Ongkir</span>
        <b>Rp {{ number_format($order->delivery_fee ?? 0) }}</b>
    </div>

    <div class="commission-row">
        <span>Komisi ongkir {{ $deliveryCommissionPercent }}%</span>
        <b>Rp {{ number_format($deliveryCommissionAmount) }}</b>
    </div>

    <div class="commission-row total">
        <span>Total potong saldo driver</span>
        <b>Rp {{ number_format($driverCutTotal) }}</b>
    </div>
</div>

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

                        @if($hasRouteMap)
      <div class="modal-route-box">
        <div class="modal-route-title">
            🗺️ Peta Rute
        </div>

        <div class="route-legend">
            <span><i class="dot-start"></i> Titik Asal</span>
            <span><i class="dot-end"></i> Titik Tujuan</span>
        </div>

        <div id="routeMap{{ $order->id }}" class="order-route-map"></div>
    </div>
@endif

<hr style="margin:20px 0;">

<div class="chat-button-row">
    <button type="button"
            class="btn-mini blue chat-action-btn"
           onclick="openChatModal(
'chatCustomerDriver{{ $order->id }}',
'{{ $order->id }}customer_driver'
)">
        💬 Chat Customer
        <span id="badge-driver-customer-{{ $order->id }}" class="chat-unread-badge" style="display:none;">0</span>
    </button>

    @if(!$isRide)
        <button type="button"
                class="btn-mini green chat-action-btn"
                onclick="openChatModal(
'chatMerchantDriver{{ $order->id }}',
'{{ $order->id }}merchant_driver'
)">
            💬 Chat Merchant
            <span id="badge-driver-merchant-{{ $order->id }}" class="chat-unread-badge" style="display:none;">0</span>
        </button>
    @endif
</div>

                    </div>
                </div>
            </div>

            <div id="chatCustomerDriver{{ $order->id }}" class="modal-detail chat-modal">
                <div class="modal-box">
                    <div class="modal-head">
                        <h3>💬 Chat Customer</h3>
                        <button type="button"
                                onclick="closeChatModal('chatCustomerDriver{{ $order->id }}','{{ $order->id }}customer_driver')">
                            ×
                        </button>
                    </div>

                    @include('components.order-chat', [
                        'order' => $order,
                        'type' => 'customer_driver'
                    ])
                </div>
            </div>

            @if(!$isRide)
                <div id="chatMerchantDriver{{ $order->id }}" class="modal-detail chat-modal">
                    <div class="modal-box">
                        <div class="modal-head">
                            <h3>💬 Chat Merchant</h3>
                            <button type="button"
                                    onclick="closeChatModal('chatMerchantDriver{{ $order->id }}','{{ $order->id }}merchant_driver')">
                                ×
                            </button>
                        </div>

                        @include('components.order-chat', [
                            'order' => $order,
                            'type' => 'merchant_driver'
                        ])
                    </div>
                </div>
            @endif

            @endif

        @empty
            <div class="empty-box">
                Belum ada order aktif.
            </div>
        @endforelse

    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<audio id="orderSound" preload="auto">
    <source src="{{ asset('sounds/order.mp3') }}" type="audio/mpeg">
</audio>

<style>
.driver-dashboard{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.driver-topbar{
    position:relative;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    border-radius:28px;
    padding:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 12px 28px rgba(15,23,42,.16);
}

.driver-topbar h2{
    margin:0 0 4px;
    font-size:26px;
    font-weight:900;
}

.rating-mini{
    margin:6px 0 0;
    color:#6b7280;
    font-size:13px;
    font-weight:800;
}

.driver-review-card{
    padding:16px;
}

.review-item{
    background:white;
    border:1px solid rgba(15,23,42,.06);
    border-radius:18px;
    padding:14px;
    margin-bottom:10px;
    box-shadow:0 8px 20px rgba(15,23,42,.05);
}

.review-top{
    display:flex;
    justify-content:space-between;
    gap:10px;
    margin-bottom:8px;
}

.review-top b{
    color:var(--primary);
    font-size:15px;
}

.review-top span{
    color:#6b7280;
    font-size:12px;
    font-weight:800;
}

.review-item p{
    margin:0 0 8px;
    color:#111827;
    font-weight:700;
    line-height:1.5;
}

.review-item small{
    color:#6b7280;
    font-weight:800;
}
.driver-topbar p{
    margin:0;
}

.hamburger-btn{
    width:48px;
    height:48px;
    border:none;
    border-radius:16px;
    background:white;
    color:var(--primary);
    font-size:26px;
    font-weight:900;
    cursor:pointer;
    position:relative;
    z-index:10;
}

.driver-dropdown-menu{
    display:none;
    position:absolute;
    top:86px;
    right:18px;
    width:280px;
    background:white;
    z-index:9999;
    padding:16px;
    border-radius:24px;
    box-shadow:0 18px 40px rgba(15,23,42,.22);
    flex-direction:column;
    gap:10px;
}

.driver-dropdown-menu.show{
    display:flex;
}

.menu-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:8px;
}

.menu-header h3{
    margin:0;
    font-size:22px;
    font-weight:900;
    color:var(--primary);
}

.driver-income-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.income-card{
    background:white;
    border-radius:22px;
    padding:16px;
    box-shadow:0 10px 24px rgba(15,23,42,.07);
}

.income-card small{
    display:block;
    color:#6b7280;
    font-size:13px;
    font-weight:800;
    margin-bottom:8px;
}

.income-card h3{
    margin:0;
    color:var(--primary);
    font-size:24px;
    font-weight:900;
}

.income-card.danger h3{
    color:#dc2626;
}

@media(max-width:640px){
    .driver-income-grid{
        grid-template-columns:1fr;
    }
}

.modal-route-box{
    margin:14px 0;
    background:rgba(15,23,42,.04);
    border-radius:18px;
    padding:12px;
}

.modal-route-title{
    color:var(--primary);
    font-weight:900;
    margin-bottom:8px;
}

.route-legend{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    font-size:12px;
    color:#6b7280;
    font-weight:800;
    margin-bottom:10px;
}

.route-legend span{
    display:flex;
    align-items:center;
    gap:6px;
}

.dot-start,
.dot-end{
    width:12px;
    height:12px;
    border-radius:50%;
    display:inline-block;
}

.leaflet-routing-container{
    display:none !important;
}
.modal-detail{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.55);
    z-index:99999;
    padding:24px;
    overflow-y:auto;
}

.modal-box{
    position:relative;
    z-index:100000;
}

.modal-head button{
    position:relative;
    z-index:100001;
    cursor:pointer;
}

.dot-start{
    background:#16a34a;
}

.dot-end{
    background:#dc2626;
}
.rating-map-grid{
    display:grid;
    grid-template-columns:1fr 2fr;
    gap:14px;
    align-items:stretch;
}

.rating-card{
    min-height:100%;
}

.rating-map-grid .map-card{
    margin:0;
}

.rating-map-grid #driverMap{
    height:260px;
}

@media(max-width:640px){
    .rating-map-grid{
        grid-template-columns:1fr;
    }
}

.driver-toast{
    position:fixed;
    top:90px;
    right:20px;
    z-index:99999;

    min-width:280px;
    max-width:380px;

    padding:14px 18px;
    border-radius:16px;

    color:#fff;
    font-weight:800;

    box-shadow:0 15px 40px rgba(0,0,0,.15);

    animation:toastSlide .35s ease;
}

.driver-toast.success{
    background:#16a34a;
}

.driver-toast.error{
    background:#dc2626;
}

@keyframes toastSlide{
    from{
        transform:translateX(100%);
        opacity:0;
    }
    to{
        transform:translateX(0);
        opacity:1;
    }
}
.order-route-map{
    width:100%;
    height:230px;
    border-radius:16px;
    overflow:hidden;
    background:#e5e7eb;
}

.menu-close{
    width:38px;
    height:38px;
    border:none;
    border-radius:50%;
    background:#fee2e2;
    color:#991b1b;
    font-size:22px;
    cursor:pointer;
}

.driver-menu-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:13px 14px;
    border-radius:18px;
    text-decoration:none;
    color:#374151;
    font-weight:900;
}
.driver-commission-box{
    background:#fff7ed;
    border-left:6px solid var(--primary);
    border-radius:18px;
    padding:14px;
    margin:14px 0;
}

.driver-commission-box h4{
    margin:0 0 10px;
    color:var(--primary);
    font-weight:900;
}

.commission-row{
    display:flex;
    justify-content:space-between;
    gap:12px;
    background:white;
    border-radius:12px;
    padding:10px 12px;
    margin-bottom:8px;
}

.commission-row span{
    color:#6b7280;
    font-weight:800;
    font-size:12px;
}

.commission-row b{
    color:#111827;
    font-weight:900;
}

.commission-row.total{
    background:#fee2e2;
}

.commission-row.total b{
    color:#dc2626;
}
.driver-menu-item:hover{
    background:rgba(15,23,42,.05);
    color:var(--primary);
}

.driver-menu-icon{
    width:40px;
    height:40px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(15,23,42,.05);
    font-size:20px;
}

.notif-btn{
    width:100%;
    border:none;
    padding:14px;
    border-radius:18px;
    background:#dcfce7;
    color:#166534;
    font-weight:900;
    cursor:pointer;
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
}

.driver-summary-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.summary-card{
    background:white;
    border:1px solid rgba(15,23,42,.06);
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
    color:var(--primary);
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
    color:var(--primary);
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
    color:var(--primary);
    margin-bottom:16px;
}

.order-card{
    border:1px solid rgba(15,23,42,.06);
    border-radius:24px;
    padding:16px;
    margin-bottom:16px;
    background:white;
    box-shadow:0 10px 24px rgba(15,23,42,.06);
}

.order-head{
    display:flex;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
}

.order-head b{
    font-size:18px;
    color:var(--primary);
}

.mini-text{
    margin:4px 0 0;
    color:#6b7280;
    font-size:13px;
}

.order-status{
    background:#fef3c7;
    color:#92400e;
    padding:7px 12px;
    border-radius:999px;
    font-weight:900;
    font-size:12px;
}

.order-status.driver_to_pickup,
.order-status.driver_to_destination,
.order-status.driver_to_merchant,
.order-status.dalam_pengiriman{
    background:#dbeafe;
    color:#1d4ed8;
}

.simple-total{
    color:var(--primary);
    font-size:20px;
    font-weight:900;
    margin:12px 0;
}

.route-box{
    background:rgba(15,23,42,.04);
    border-radius:18px;
    padding:14px;
    margin-bottom:14px;
}

.address-info{
    background:white;
    border-radius:16px;
    padding:12px;
    margin-bottom:10px;
}

.address-info small{
    display:block;
    color:var(--primary);
    font-size:12px;
    font-weight:900;
    margin-bottom:5px;
}

.address-info p{
    margin:0;
    color:#374151;
    line-height:1.45;
    font-weight:700;
}

.distance-text{
    margin:8px 0 0;
    color:#374151;
}

.map-action-row{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.order-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn-mini{
    border:none;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
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
.btn-mini.orange{background:linear-gradient(135deg,var(--primary),var(--secondary));}

.chat-button-row{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:12px;
}

.chat-modal .modal-box{
    max-width:620px;
}

.empty-box{
    background:rgba(15,23,42,.04);
    border-radius:16px;
    padding:16px;
    color:var(--primary);
    font-weight:900;
    text-align:center;
}

.order-items{
    background:rgba(15,23,42,.04);
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
.wallet-warning-card{
    display:flex;
    gap:14px;
    align-items:flex-start;

    background:#fff7ed;
    border-left:6px solid #f97316;

    padding:18px;
    margin-bottom:18px;

    border-radius:18px;
}

.wallet-warning-icon{
    font-size:30px;
    line-height:1;
}

.wallet-warning-title{
    font-size:16px;
    font-weight:900;
    color:#c2410c;
    margin-bottom:4px;
}

.wallet-warning-text{
    color:#7c2d12;
    font-weight:700;
    line-height:1.5;
}
.modal-box{
    background:white;
    max-width:620px;
    margin:40px auto;
    border-radius:24px;
    padding:20px;
}

.modal-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding-bottom:12px;
    margin-bottom:14px;
}

.modal-head h3{
    margin:0;
    font-size:22px;
    font-weight:900;
    color:var(--primary);
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



.chat-button-row{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:12px;
}

.chat-action-btn{
    position:relative;
}

.chat-unread-badge{
    position:absolute;
    top:-8px;
    right:-8px;
    min-width:22px;
    height:22px;
    border-radius:999px;
    background:#dc2626;
    color:white;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:900;
    box-shadow:0 4px 10px rgba(0,0,0,.22);
    padding:0 6px;
}

.chat-modal .modal-box{
    max-width:680px;
}

@media(max-width:640px){
    .driver-summary-grid{
        grid-template-columns:1fr;
    }

    .driver-dropdown-menu{
        left:14px;
        right:14px;
        width:auto;
    }

    .btn-mini{
        flex:1;
        text-align:center;
    }

    .map-action-row{
        flex-direction:column;
    }
}
</style>

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

let orderRouteMaps = {};

function openOrderDetail(id) {
    document.getElementById(id).style.display = 'block';
}
function closeOrderDetail(id) {
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'none';
    }
}

function openOrderDetailWithRoute(id, mapId, startLat, startLng, endLat, endLng) {
    document.getElementById(id).style.display = 'block';

    if(startLat && startLng && endLat && endLng){
        setTimeout(function(){
            initOrderRouteMap(mapId, startLat, startLng, endLat, endLng);
        }, 250);
    }
}

function initOrderRouteMap(mapId, startLat, startLng, endLat, endLng) {
    if(orderRouteMaps[mapId]){
        orderRouteMaps[mapId].remove();
    }

    let map = L.map(mapId).setView([startLat, startLng], 14);
    orderRouteMaps[mapId] = map;

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let startIcon = L.divIcon({
        className:'',
        html:`<div style="
            width:34px;
            height:34px;
            background:#16a34a;
            border:4px solid white;
            border-radius:50%;
            box-shadow:0 6px 16px rgba(0,0,0,.3);
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-size:16px;
        ">📍</div>`,
        iconSize:[34,34],
        iconAnchor:[17,17],
    });

    let endIcon = L.divIcon({
        className:'',
        html:`<div style="
            width:34px;
            height:34px;
            background:#dc2626;
            border:4px solid white;
            border-radius:50%;
            box-shadow:0 6px 16px rgba(0,0,0,.3);
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-size:16px;
        ">🏁</div>`,
        iconSize:[34,34],
        iconAnchor:[17,17],
    });

    let themeColor = getComputedStyle(document.documentElement)
        .getPropertyValue('--primary')
        .trim();

    L.Routing.control({
        waypoints: [
            L.latLng(startLat, startLng),
            L.latLng(endLat, endLng)
        ],
        routeWhileDragging: false,
        addWaypoints: false,
        draggableWaypoints: false,
        fitSelectedRoutes: true,
        show: false,
        createMarker: function(i, waypoint) {
            if(i === 0){
                return L.marker(waypoint.latLng, {
                    icon:startIcon
                }).bindPopup('Titik Asal');
            }

            return L.marker(waypoint.latLng, {
                icon:endIcon
            }).bindPopup('Titik Tujuan');
        },
        lineOptions: {
            styles: [
                {
                    color: themeColor || '#f97316',
                    opacity: 0.9,
                    weight: 6
                }
            ]
        }
    }).addTo(map);

    setTimeout(function(){
        map.invalidateSize();
    }, 500);
}

function createVehicleIcon(vehicleType, isMe = false){
    let icon = vehicleType === 'mobil' ? '🚗' : '🛵';

    let themeColor = getComputedStyle(document.documentElement)
        .getPropertyValue('--primary')
        .trim();

    let color = isMe ? themeColor : '#22c55e';

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
            icon: createVehicleIcon('{{ $driver->vehicle_type ?? "motor" }}', true)
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

setTimeout(function(){

    let success = document.getElementById('toastSuccess');
    let error = document.getElementById('toastError');

    if(success){
        success.remove();
    }

    if(error){
        error.remove();
    }

},3000);


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
    }, function(error){
        console.log('GPS error:', error);
    }, {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 0
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

function openChatModal(id, chatId)
{
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'block';
    }

    if(
        window.initChat &&
        window.initChat[chatId]
    ){
        window.initChat[chatId]();
    }
}

function closeChatModal(id, chatId) {
    const modal = document.getElementById(id);
    if(modal){
        modal.style.display = 'none';
    }
    if(window.stopChat && window.stopChat[chatId]){
        window.stopChat[chatId]();
    }
    setTimeout(refreshAllChatBadges, 500);
}

function updateChatBadge(orderId, type, badgeId){
    fetch(`/orders/${orderId}/chat/${type}/badge`, {
        headers:{'X-Requested-With':'XMLHttpRequest'}
    })
    .then(res => res.json())
    .then(data => {
        const badge = document.getElementById(badgeId);
        if(!badge) return;

        const count = parseInt(data.count || 0);
        if(count > 0){
            badge.innerText = count;
            badge.style.display = 'flex';
        }else{
            badge.style.display = 'none';
            badge.innerText = 0;
        }
    })
    .catch(function(){});
}

function refreshAllChatBadges(){
    @foreach($orders as $order)
        updateChatBadge({{ $order->id }}, 'customer_driver', 'badge-driver-customer-{{ $order->id }}');
        @if(!in_array($order->order_type, ['ojek', 'car']))
            updateChatBadge({{ $order->id }}, 'merchant_driver', 'badge-driver-merchant-{{ $order->id }}');
        @endif
    @endforeach
}

document.addEventListener('DOMContentLoaded', function () {
    updateNotifButton();

    updateDriverGPS();
    setInterval(updateDriverGPS, 5000);

    checkDriverNotif();
    setInterval(checkDriverNotif, 5000);

    refreshAllChatBadges();
    setInterval(refreshAllChatBadges, 3000);
});
</script>
@include('components.chat-sound-notification')

@endsection