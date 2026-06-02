@extends('layouts.customer-page')

@section('content')

<div class="orders-page">

    <div class="orders-head">
        <div>
            <h2>📦 Pesanan Saya</h2>
            <p>Pantau pesanan aktif dan tracking driver.</p>
        </div>

        <a href="{{ route('orders.history') }}" class="history-btn">
            📜 Riwayat
        </a>
    </div>

    <div class="orders-list">

        @forelse($orders as $order)

            <a href="{{ route('orders.show',$order->id) }}" class="order-card">

                <div class="order-top">
                    <div>
                        <h3>
                            {{ $order->order_number ?? 'ORD-'.$order->id }}
                        </h3>

                        <div class="order-badges">
                            @if($order->order_type == 'ojek')
                                <span class="type-badge">🏍️ J-Ride</span>
                            @elseif($order->order_type == 'car')
                                <span class="type-badge">🚗 J-Car</span>
                            @else
                                <span class="type-badge">🍔 J-Food</span>
                            @endif

                            <span class="status-badge {{ $order->status }}">
                                {{ strtoupper(str_replace('_',' ',$order->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="order-price">
                        Rp {{ number_format($order->total) }}
                    </div>
                </div>

                <div class="order-body">

                    @if($order->order_type == 'ojek' || $order->order_type == 'car')

                        <div class="address-box">
                            <small>📍 Jemput</small>
                            <p>{{ $order->pickup_address ?? '-' }}</p>
                        </div>

                        <div class="address-box">
                            <small>🏁 Tujuan</small>
                            <p>{{ $order->destination_address ?? '-' }}</p>
                        </div>

                    @else

                        <div class="address-box">
                            <small>📍 Alamat Pengiriman</small>
                            <p>{{ $order->address ?? '-' }}</p>
                        </div>

                    @endif

                    <div class="order-footer">
                        @if($order->distance_km)
                            <span>📍 {{ number_format($order->distance_km,1) }} km</span>
                        @endif

                        <span>🕒 {{ $order->created_at->diffForHumans() }}</span>
                    </div>

                </div>

            </a>

        @empty

            <div class="empty-order">
                <div class="empty-icon">📦</div>
                <h3>Belum ada pesanan aktif</h3>
                <p>Pesanan yang sedang berjalan akan tampil di sini.</p>
                <a href="/" class="order-now-btn">Buat Pesanan</a>
            </div>

        @endforelse

    </div>

</div>

<style>
.orders-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.orders-head{
    background:white;
    border-radius:28px;
    padding:20px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
}

.orders-head h2{
    margin:0;
    color:var(--primary);
    font-size:28px;
    font-weight:900;
}

.orders-head p{
    margin:7px 0 0;
    color:#6b7280;
    font-size:14px;
}

.history-btn{
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
    white-space:nowrap;
    box-shadow:0 10px 22px rgba(15,23,42,.14);
}

.orders-list{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.order-card{
    display:block;
    background:white;
    border-radius:28px;
    padding:18px;
    text-decoration:none;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
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
    font-size:21px;
    font-weight:900;
}

.order-badges{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

.type-badge,
.status-badge{
    display:inline-block;
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.type-badge{
    background:rgba(15,23,42,.05);
    color:var(--primary);
}

.status-badge{
    background:#fef3c7;
    color:#92400e;
}

.status-badge.completed{
    background:#dcfce7;
    color:#166534;
}

.status-badge.cancelled{
    background:#fee2e2;
    color:#991b1b;
}

.status-badge.searching_driver,
.status-badge.waiting_response{
    background:#fef3c7;
    color:#92400e;
}

.status-badge.driver_to_merchant,
.status-badge.dalam_pengiriman,
.status-badge.driver_to_pickup,
.status-badge.driver_to_destination{
    background:#dbeafe;
    color:#1d4ed8;
}

.order-price{
    color:var(--primary);
    font-size:22px;
    font-weight:900;
    white-space:nowrap;
}

.address-box{
    background:rgba(15,23,42,.04);
    padding:14px;
    border-radius:18px;
    margin-bottom:12px;
}

.address-box small{
    color:var(--primary);
    display:block;
    margin-bottom:6px;
    font-size:12px;
    font-weight:900;
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
    gap:10px;
    color:#6b7280;
    font-size:13px;
    margin-top:10px;
}

.empty-order{
    background:white;
    border-radius:28px;
    padding:30px 20px;
    text-align:center;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.empty-icon{
    font-size:46px;
    margin-bottom:10px;
}

.empty-order h3{
    margin:0;
    color:var(--primary);
    font-size:22px;
    font-weight:900;
}

.empty-order p{
    margin:8px 0 18px;
    color:#6b7280;
}

.order-now-btn{
    display:inline-block;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:13px 18px;
    border-radius:16px;
    text-decoration:none;
    font-weight:900;
}

@media(max-width:640px){
    .orders-head{
        flex-direction:column;
        align-items:flex-start;
        border-radius:24px;
        padding:16px;
    }

    .history-btn{
        width:100%;
        text-align:center;
    }

    .order-card{
        border-radius:24px;
        padding:16px;
    }

    .order-top{
        flex-direction:column;
    }

    .order-price{
        font-size:20px;
    }

    .order-footer{
        flex-direction:column;
    }
}
</style>

@endsection