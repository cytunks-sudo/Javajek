@extends('layouts.customer-page')

@section('content')

<div class="orders-page">

    <h2 class="orders-title">
        Pesanan Saya
    </h2>

    <div class="orders-list">

        @forelse($orders as $order)

            <a href="{{ route('orders.show',$order->id) }}"
               class="order-card">

                <div class="order-top">

                    <div>
                        <h3>
                            Order #{{ $order->id }}
                        </h3>

                        <div class="order-badges">

                            @if($order->order_type == 'ojek')
                                <span class="badge-ojek">
                                    🏍️ OJEK
                                </span>
                            @else
                                <span class="badge-food">
                                    🍔 FOOD
                                </span>
                            @endif

                            <span class="badge-status">
                                {{ strtoupper(str_replace('_',' ',$order->status)) }}
                            </span>

                        </div>
                    </div>

                    <div class="order-price">
                        Rp {{ number_format($order->total) }}
                    </div>

                </div>

                <div class="order-body">

                    @if($order->order_type == 'ojek')

                        <div class="address-box">
                            <small>Jemput</small>
                            <p>
                                {{ $order->pickup_address ?? '-' }}
                            </p>
                        </div>

                        <div class="address-box">
                            <small>Tujuan</small>
                            <p>
                                {{ $order->destination_address ?? '-' }}
                            </p>
                        </div>

                    @else

                        <div class="address-box">
                            <small>Alamat Pengiriman</small>
                            <p>
                                {{ $order->address ?? '-' }}
                            </p>
                        </div>

                    @endif

                    <div class="order-footer">

                        @if($order->distance_km)
                            <span>
                                📍 {{ number_format($order->distance_km,1) }} km
                            </span>
                        @endif

                        <span>
                            🕒 {{ $order->created_at->diffForHumans() }}
                        </span>

                    </div>

                </div>

            </a>

        @empty

            <div class="empty-order">
                Belum ada pesanan.
            </div>

        @endforelse

    </div>

</div>

<style>
.orders-title{
    font-size:28px;
    font-weight:900;
    color:#9a3412;
    margin-bottom:18px;
}

.orders-list{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.order-card{
    background:white;
    border-radius:26px;
    padding:18px;
    text-decoration:none;
    border:1px solid #fed7aa;
    box-shadow:0 10px 28px rgba(15,23,42,.07);
    transition:.2s;
}

.order-card:hover{
    transform:translateY(-2px);
}

.order-top{
    display:flex;
    justify-content:space-between;
    gap:16px;
    margin-bottom:14px;
}

.order-top h3{
    margin:0 0 10px;
    color:#111827;
    font-size:22px;
    font-weight:900;
}

.order-badges{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

.badge-food,
.badge-ojek,
.badge-status{
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.badge-food{
    background:#ffedd5;
    color:#c2410c;
}

.badge-ojek{
    background:#dbeafe;
    color:#1d4ed8;
}

.badge-status{
    background:#dcfce7;
    color:#166534;
}

.order-price{
    color:#ea580c;
    font-size:22px;
    font-weight:900;
    white-space:nowrap;
}

.address-box{
    background:#fff7ed;
    padding:14px;
    border-radius:18px;
    margin-bottom:12px;
}

.address-box small{
    color:#6b7280;
    display:block;
    margin-bottom:6px;
}

.address-box p{
    margin:0;
    color:#111827;
    line-height:1.5;
    font-weight:700;
}

.order-footer{
    display:flex;
    justify-content:space-between;
    color:#6b7280;
    font-size:13px;
    margin-top:10px;
}

.empty-order{
    background:white;
    border-radius:24px;
    padding:26px;
    text-align:center;
    font-weight:900;
    color:#9a3412;
}
</style>

@endsection