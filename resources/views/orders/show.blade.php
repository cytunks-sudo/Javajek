@extends('layouts.customer-page')

@section('content')

@php
    $isRide = in_array($order->order_type, ['ojek', 'car']);
    $isCar = $order->order_type == 'car';
    $isOjek = $order->order_type == 'ojek';
    $isFood = !$isRide;

    $appSetting = \App\Models\AppSetting::first();
    $primaryColor = $appSetting->primary_color ?? '#f97316';
    $secondaryColor = $appSetting->secondary_color ?? '#fb923c';

    if($isRide){
        $targetLat = $order->status == 'driver_to_destination'
            ? $order->destination_latitude
            : $order->pickup_latitude;

        $targetLng = $order->status == 'driver_to_destination'
            ? $order->destination_longitude
            : $order->pickup_longitude;

        $targetLabel = $order->status == 'driver_to_destination'
            ? 'Tujuan'
            : 'Titik Jemput';
    }else{
        $targetLat = $order->status == 'dalam_pengiriman'
            ? $order->latitude
            : ($order->restaurant->latitude ?? null);

        $targetLng = $order->status == 'dalam_pengiriman'
            ? $order->longitude
            : ($order->restaurant->longitude ?? null);

        $targetLabel = $order->status == 'dalam_pengiriman'
            ? 'Alamat Customer'
            : 'Merchant';
    }
@endphp

<div class="order-detail-page">

    <a href="/my-orders" class="back-btn">← Kembali</a>

    <div class="detail-card">
        <div class="detail-head">
            <div>
                <h2>
                    @if($isOjek)
                        🏍️ Detail J-Ride
                    @elseif($isCar)
                        🚗 Detail J-Car
                    @else
                        🍔 Detail J-Food
                    @endif
                </h2>

                <p>{{ $order->order_number ?? '#'.$order->id }}</p>
            </div>

            <span class="status-pill {{ $order->status }}">
                {{ strtoupper(str_replace('_',' ', $order->status)) }}
            </span>
        </div>

        <div class="info-box total-box">
            <b>Total</b>
            <p>Rp {{ number_format($order->total) }}</p>
        </div>

        @if($isRide)

            <div class="info-box">
                <b>📍 Titik Jemput</b>
                <p>{{ $order->pickup_address ?? '-' }}</p>
            </div>

            <div class="info-box">
                <b>🏁 Titik Tujuan</b>
                <p>{{ $order->destination_address ?? '-' }}</p>
            </div>

        @else

            <div class="info-box">
                <b>🏪 Merchant</b>
                <p>{{ $order->restaurant->name ?? '-' }}</p>
            </div>

            <div class="info-box">
                <b>📍 Alamat Pengiriman</b>
                <p>{{ $order->address ?? '-' }}</p>
            </div>

            @if($order->items && $order->items->count())
                <div class="items-box">
                    <h3>Item Pesanan</h3>

                    @foreach($order->items as $item)
                        <div class="item-row">
                            <span>{{ $item->food->name ?? '-' }} x {{ $item->qty }}</span>
                            <b>Rp {{ number_format($item->price * $item->qty) }}</b>
                        </div>
                    @endforeach
                </div>
            @endif

        @endif
    </div>

    <div class="detail-card">
        <div class="tracking-head">
            <div>
                <h3>📍 Tracking Driver</h3>
                <p id="trackingInfo">Menunggu lokasi driver...</p>
            </div>

            <div class="tracking-badge">
                {{ $targetLabel }}
            </div>
        </div>

        <div id="trackingMap"></div>
    </div>

    <div class="detail-card">
        <div class="chat-section-head">
            <div>
                <h3>💬 Chat Pesanan</h3>
                <p>Chat akan muncul setelah tombol dipilih.</p>
            </div>
        </div>

        <div class="chat-button-row">
            @if($isFood)
                <button type="button"
                        class="chat-open-btn merchant"
                        onclick="openChatModal('chatCustomerMerchant{{ $order->id }}','{{ $order->id }}customer_merchant')">
                    💬 Chat Merchant
                    <span id="badge-customer-merchant-{{ $order->id }}" class="chat-unread-badge" style="display:none;">0</span>
                </button>
            @endif

            @if($order->driver_id)
                <button type="button"
                        class="chat-open-btn driver"
                        onclick="openChatModal('chatCustomerDriver{{ $order->id }}','{{ $order->id }}customer_driver')">
                    💬 Chat Driver
                    <span id="badge-customer-driver-{{ $order->id }}" class="chat-unread-badge" style="display:none;">0</span>
                </button>
            @endif
        </div>
    </div>

</div>

@if($isFood)
<div id="chatCustomerMerchant{{ $order->id }}" class="chat-modal">
    <div class="chat-modal-box">
        <div class="chat-modal-head">
            <h3>💬 Chat Merchant</h3>
            <button type="button" onclick="closeChatModal('chatCustomerMerchant{{ $order->id }}','{{ $order->id }}customer_merchant')">×</button>
        </div>

        @include('components.order-chat', [
            'order' => $order,
            'type' => 'customer_merchant'
        ])
    </div>
</div>
@endif

@if($order->driver_id)
<div id="chatCustomerDriver{{ $order->id }}" class="chat-modal">
    <div class="chat-modal-box">
        <div class="chat-modal-head">
            <h3>💬 Chat Driver</h3>
            <button type="button" onclick="closeChatModal('chatCustomerDriver{{ $order->id }}','{{ $order->id }}customer_driver')">×</button>
        </div>

        @include('components.order-chat', [
            'order' => $order,
            'type' => 'customer_driver'
        ])
    </div>
</div>
@endif

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<audio id="chatSound" preload="auto">
    <source src="{{ asset('sounds/order.mp3') }}" type="audio/mpeg">
</audio>

<style>
.order-detail-page{display:flex;flex-direction:column;gap:16px}.back-btn{display:inline-block;width:max-content;background:rgba(15,23,42,.05);color:var(--primary);padding:11px 15px;border-radius:15px;text-decoration:none;font-weight:900}.detail-card{background:white;border:1px solid rgba(15,23,42,.06);border-radius:28px;padding:20px;box-shadow:0 12px 30px rgba(15,23,42,.08)}.detail-head,.tracking-head,.chat-section-head{display:flex;justify-content:space-between;align-items:flex-start;gap:14px;margin-bottom:16px}.detail-head h2,.tracking-head h3,.chat-section-head h3{margin:0;color:var(--primary);font-size:26px;font-weight:900}.detail-head p,.tracking-head p,.chat-section-head p{margin:6px 0 0;color:#6b7280;font-size:13px;font-weight:700}.status-pill{display:inline-block;background:#fef3c7;color:#92400e;padding:9px 13px;border-radius:999px;font-size:12px;font-weight:900;white-space:nowrap}.status-pill.completed{background:#dcfce7;color:#166534}.status-pill.cancelled{background:#fee2e2;color:#991b1b}.status-pill.driver_to_pickup,.status-pill.driver_to_destination,.status-pill.driver_to_merchant,.status-pill.dalam_pengiriman{background:#dbeafe;color:#1d4ed8}.info-box{background:rgba(15,23,42,.04);padding:15px;border-radius:20px;margin-bottom:12px}.info-box b{color:var(--primary);font-size:13px;font-weight:900}.info-box p{margin:7px 0 0;color:#111827;font-weight:800;line-height:1.5}.total-box p{color:var(--primary);font-size:24px;font-weight:900}.items-box{background:rgba(15,23,42,.04);border-radius:20px;padding:15px}.items-box h3{margin:0 0 12px;color:var(--primary);font-size:18px;font-weight:900}.item-row{display:flex;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px dashed rgba(15,23,42,.10)}.item-row:last-child{border-bottom:none}.item-row span{color:#111827;font-weight:700}.item-row b{color:var(--primary);white-space:nowrap}.tracking-badge{background:linear-gradient(135deg,var(--primary),var(--secondary));color:white;padding:9px 13px;border-radius:999px;font-size:12px;font-weight:900;white-space:nowrap}#trackingMap{width:100%;height:360px;border-radius:24px;overflow:hidden;background:#e5e7eb}.leaflet-routing-container{display:none!important}.chat-button-row{display:flex;gap:12px;flex-wrap:wrap}.chat-open-btn{border:none;position:relative;cursor:pointer;padding:14px 18px;border-radius:18px;color:white;font-weight:900;box-shadow:0 10px 22px rgba(15,23,42,.14)}.chat-open-btn.merchant{background:#16a34a}.chat-open-btn.driver{background:#0ea5e9}.chat-unread-badge{position:absolute;top:-8px;right:-8px;min-width:22px;height:22px;border-radius:999px;background:#dc2626;color:white;align-items:center;justify-content:center;font-size:11px;font-weight:900;box-shadow:0 4px 10px rgba(0,0,0,.22);padding:0 6px}.chat-modal{display:none;position:fixed;inset:0;background:rgba(15,23,42,.60);z-index:99999;padding:22px;overflow-y:auto}.chat-modal-box{background:white;max-width:720px;margin:30px auto;border-radius:26px;padding:20px;box-shadow:0 24px 60px rgba(15,23,42,.30)}.chat-modal-head{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(15,23,42,.08);padding-bottom:12px;margin-bottom:14px}.chat-modal-head h3{margin:0;color:var(--primary);font-size:23px;font-weight:900}.chat-modal-head button{width:40px;height:40px;border:none;border-radius:50%;background:#fee2e2;color:#991b1b;font-size:26px;cursor:pointer}@media(max-width:640px){.detail-card{padding:16px;border-radius:24px}.detail-head,.tracking-head,.chat-section-head{flex-direction:column}.detail-head h2,.tracking-head h3,.chat-section-head h3{font-size:23px}#trackingMap{height:330px}.chat-open-btn{width:100%}.chat-modal{padding:12px}.chat-modal-box{margin:20px auto;padding:14px;border-radius:22px}}
</style>

<script>
let trackingMap = null;
let driverMarker = null;
let targetMarker = null;
let routeControl = null;
let trackingFirstLoad = true;
let userDraggingMap = false;
let targetLat = {{ $targetLat ?? 'null' }};
let targetLng = {{ $targetLng ?? 'null' }};
let targetLabel = "{{ $targetLabel }}";
let primaryColor = "{{ $primaryColor }}";
let vehicleIconText = "{{ $isCar ? '🚗' : '🛵' }}";
let lastTotalChatBadge = 0;
let chatBadgeReady = false;

function driverIcon(){return L.divIcon({className:'',html:`<div style="width:38px;height:38px;background:${primaryColor};border:4px solid white;border-radius:50%;box-shadow:0 6px 18px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;font-size:18px;">${vehicleIconText}</div>`,iconSize:[38,38],iconAnchor:[19,19],popupAnchor:[0,-18]});}
function targetIcon(){return L.divIcon({className:'',html:`<div style="width:36px;height:36px;background:#16a34a;border:4px solid white;border-radius:50%;box-shadow:0 6px 18px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;font-size:17px;">📍</div>`,iconSize:[36,36],iconAnchor:[18,18],popupAnchor:[0,-18]});}
function initTrackingMap(driverLat,driverLng){if(!trackingMap){trackingMap=L.map('trackingMap').setView([driverLat,driverLng],15);trackingMap.on('dragstart',()=>userDraggingMap=true);trackingMap.on('zoomstart',()=>userDraggingMap=true);L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(trackingMap);if(targetLat&&targetLng){targetMarker=L.marker([targetLat,targetLng],{icon:targetIcon()}).addTo(trackingMap).bindPopup(targetLabel);}setTimeout(()=>trackingMap.invalidateSize(),500);}if(driverMarker){driverMarker.setLatLng([driverLat,driverLng]);}else{driverMarker=L.marker([driverLat,driverLng],{icon:driverIcon()}).addTo(trackingMap).bindPopup('Lokasi Driver').openPopup();}drawRoute(driverLat,driverLng);if(trackingFirstLoad&&targetLat&&targetLng){trackingMap.fitBounds([[driverLat,driverLng],[targetLat,targetLng]]);trackingFirstLoad=false;}}
function drawRoute(driverLat,driverLng){if(!targetLat||!targetLng)return;if(routeControl){trackingMap.removeControl(routeControl);}routeControl=L.Routing.control({waypoints:[L.latLng(driverLat,driverLng),L.latLng(targetLat,targetLng)],routeWhileDragging:false,addWaypoints:false,draggableWaypoints:false,fitSelectedRoutes:!userDraggingMap,show:false,createMarker:function(){return null;},lineOptions:{styles:[{color:primaryColor,opacity:.9,weight:6}]}}).addTo(trackingMap);setTimeout(()=>trackingMap.invalidateSize(),500);}
function loadDriverLocation(){fetch('/order/{{ $order->id }}/driver-location').then(res=>res.json()).then(data=>{if(!data.success){document.getElementById('trackingInfo').innerText=data.message??'Driver belum tersedia.';return;}let lat=parseFloat(data.latitude);let lng=parseFloat(data.longitude);if(!lat||!lng){document.getElementById('trackingInfo').innerText='Lokasi driver belum aktif.';return;}initTrackingMap(lat,lng);document.getElementById('trackingInfo').innerText='Driver: '+data.driver_name+' | Status: '+data.status;}).catch(()=>{document.getElementById('trackingInfo').innerText='Gagal mengambil lokasi driver.';});}
function openChatModal(id, chatId)
{
    document.getElementById(id).style.display='block';

    if(
        window.initChat &&
        window.initChat[chatId]
    ){
        window.initChat[chatId]();
        delete window.initChat[chatId];
    }
}
function closeChatModal(id,chatId){const modal=document.getElementById(id);if(modal){modal.style.display='none';}if(window.stopChat&&window.stopChat[chatId]){window.stopChat[chatId]();}setTimeout(refreshAllChatBadges,500);}
function playChatSound(){const audio=document.getElementById('chatSound');if(audio){audio.currentTime=0;audio.play().catch(()=>{});}}
function updateChatBadge(orderId,type,badgeId){return fetch(`/orders/${orderId}/chat/${type}/badge`,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(res=>res.json()).then(data=>{const badge=document.getElementById(badgeId);if(!badge)return 0;const count=parseInt(data.count||0);if(count>0){badge.innerText=count;badge.style.display='flex';}else{badge.style.display='none';badge.innerText=0;}return count;}).catch(()=>0);}
function refreshAllChatBadges(){let promises=[];@if($isFood) promises.push(updateChatBadge({{ $order->id }},'customer_merchant','badge-customer-merchant-{{ $order->id }}')); @endif @if($order->driver_id) promises.push(updateChatBadge({{ $order->id }},'customer_driver','badge-customer-driver-{{ $order->id }}')); @endif Promise.all(promises).then(values=>{let total=values.reduce((a,b)=>a+parseInt(b||0),0);if(chatBadgeReady&&total>lastTotalChatBadge){playChatSound();if('Notification'in window&&Notification.permission==='granted'){new Notification('💬 Pesan Chat Baru',{body:'Ada pesan baru pada order {{ $order->order_number ?? '#'.$order->id }}',icon:"{{ asset('images/logo.png') }}"});}}lastTotalChatBadge=total;chatBadgeReady=true;});}

document.addEventListener('DOMContentLoaded',function(){loadDriverLocation();setInterval(loadDriverLocation,5000);refreshAllChatBadges();setInterval(refreshAllChatBadges,3000);if('Notification'in window&&Notification.permission==='default'){Notification.requestPermission().catch(()=>{});}});
</script>
@include('components.chat-sound-notification')
@endsection
