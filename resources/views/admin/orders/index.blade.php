@extends('layouts.admin')

@section('content')

<div class="admin-order-page">

    <div class="page-head">
        <div>
            <h2>📦 Order Masuk</h2>
            <p>Kelola order customer, driver, ongkir, dan status pesanan.</p>
        </div>

        <div class="count-badge">
            {{ $orders->count() }} Order
        </div>
       
    </div>

    <form method="GET" action="/admin/orders" class="filter-card">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari customer, restoran, driver, atau nomor order..."
               class="filter-input">

        <select name="status" class="filter-select">
            <option value="">Semua Status</option>
            <option value="searching_driver" {{ request('status') == 'searching_driver' ? 'selected' : '' }}>Searching Driver</option>
            <option value="waiting_response" {{ request('status') == 'waiting_response' ? 'selected' : '' }}>Waiting Response</option>
            <option value="driver_to_merchant" {{ request('status') == 'driver_to_merchant' ? 'selected' : '' }}>Driver Ke Merchant</option>
            <option value="dalam_pengiriman" {{ request('status') == 'dalam_pengiriman' ? 'selected' : '' }}>Dalam Pengiriman</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        
        <button class="filter-btn">
            🔍 Cari
        </button>

        @if(request('search') || request('status'))
            <a href="/admin/orders" class="reset-btn">
                Reset
            </a>
        @endif
    </form>

    @forelse($orders as $order)

        @php
            $drivers = \App\Models\Driver::with('user')
                ->where('status', 'online')
                ->get();

            $isFinished = in_array($order->status, ['cancelled', 'completed']);

            $orderType = $order->order_type ?? 'food';

            $orderIcon = match($orderType) {
                'ojek' => '🏍️',
                'car' => '🚗',
                default => '🍔',
            };
        @endphp

        <div class="order-card">

            <div class="order-top">
                <div class="order-title">
                    <div class="order-icon">{{ $orderIcon }}</div>

                    <div>
                        <h3>
                            {{ ucfirst($orderType) }} #{{ $order->order_number ?? $order->id }}
                        </h3>

                        <p>
                            Customer:
                            <b>{{ $order->user->name ?? '-' }}</b>
                        </p>
                    </div>
                </div>

                <span class="status-badge status-{{ $order->status }}">
                    {{ strtoupper(str_replace('_',' ', $order->status)) }}
                </span>
            </div>

            <div class="order-info-grid">
                <div>
                    <span>Restoran / Layanan</span>
                    <b>{{ $order->restaurant->name ?? ucfirst($orderType) }}</b>
                </div>

                <div>
                    <span>Driver</span>
                    <b>{{ $order->driver->user->name ?? 'Belum ada' }}</b>
                </div>

                <div>
                    <span>Total</span>
                    <b>Rp {{ number_format($order->grand_total ?? $order->total) }}</b>
                </div>

                <div>
                    <span>Status Driver</span>
                    <b>{{ strtoupper($order->driver_status ?? '-') }}</b>
                </div>
            </div>

            <div class="order-actions">

                <button type="button"
                        class="btn-action blue"
                        onclick="openOrderDetail('orderDetail{{ $order->id }}')">
                    Detail
                </button>

                @if(!$isFinished)

                    @php
    $drivers = \App\Http\Controllers\AdminOrderController::availableDriversForOrder($order);
@endphp

<form method="POST"
      action="/admin/orders/{{ $order->id }}/assign-driver"
      class="assign-form">
    @csrf

    <select name="driver_id" class="driver-select">
        @forelse($drivers as $driver)
            <option value="{{ $driver->id }}">
                {{ $driver->user->name ?? '-' }}
                - {{ $driver->vehicle_type }}
                - {{ number_format($driver->distance_km, 1) }} km
            </option>
        @empty
            <option value="">
                Tidak ada driver dalam radius
            </option>
        @endforelse
    </select>

    <button class="btn-action">
        Kirim ke Driver
    </button>
</form>

                    

                    <a href="/admin/orders/{{ $order->id }}/status/cancelled"
                       class="btn-action red"
                       onclick="return confirm('Yakin ingin membatalkan order ini?')">
                        Batalkan
                    </a>

                @else

                    <div class="finished-info">
                        Order {{ strtoupper($order->status) }}
                    </div>

                @endif

            </div>

        </div>

        <div id="orderDetail{{ $order->id }}" class="modal-detail">
            <div class="modal-box">

                <div class="modal-head">
                    <div>
                        <h3>Detail Order #{{ $order->order_number ?? $order->id }}</h3>
                        <p>{{ ucfirst($orderType) }}</p>
                    </div>

                    <button type="button"
                            onclick="closeOrderDetail('orderDetail{{ $order->id }}')">
                        ×
                    </button>
                </div>

                <div class="modal-body">

                    <div class="detail-grid">
                        <div>
                            <small>Customer</small>
                            <b>{{ $order->user->name ?? '-' }}</b>
                        </div>

                        <div>
                            <small>Restaurant / Layanan</small>
                            <b>{{ $order->restaurant->name ?? ucfirst($orderType) }}</b>
                        </div>

                        <div>
                            <small>Driver</small>
                            <b>{{ $order->driver->user->name ?? '-' }}</b>
                        </div>

                        <div>
                            <small>Status Order</small>
                            <b>{{ strtoupper($order->status) }}</b>
                        </div>

                        <div>
                            <small>Status Merchant</small>
                            <b>{{ strtoupper($order->merchant_status ?? '-') }}</b>
                        </div>

                        <div>
                            <small>Status Driver</small>
                            <b>{{ strtoupper($order->driver_status ?? '-') }}</b>
                        </div>
                    </div>

                    <hr>

                    <div class="price-box">
                        <div>
                            <span>Total Produk / Tarif</span>
                            <b>Rp {{ number_format($order->total) }}</b>
                        </div>

                        <div>
                            <span>Ongkir</span>
                            <b>Rp {{ number_format($order->delivery_fee ?? 0) }}</b>
                        </div>

                        <div class="grand-total">
                            <span>Grand Total</span>
                            <b>Rp {{ number_format($order->grand_total ?? $order->total) }}</b>
                        </div>
                    </div>

                    <hr>

                    <h4>Item Pesanan</h4>

                    <div class="item-list">
                        @forelse($order->items as $item)
                            <div class="item-row">
                                <span>{{ $item->food->name ?? '-' }}</span>
                                <b>x {{ $item->qty }}</b>
                            </div>
                        @empty
                            <div class="empty-box">
                                Tidak ada item.
                            </div>
                        @endforelse
                    </div>

                    @if($order->status == 'cancelled')
                        <div class="cancel-box">
                            Order ini sudah dibatalkan.
                        </div>
                    @endif

                </div>

            </div>
        </div>

    @empty

        <div class="empty-box">
            Belum ada order masuk.
        </div>

    @endforelse

</div>

<style>
.admin-order-page{
    width:100%;
}

.page-head{
    background:white;
    border-radius:26px;
    padding:22px;
    margin-bottom:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.page-head h2{
    margin:0;
    color:var(--primary-color);
    font-size:28px;
    font-weight:900;
}

.page-head p{
    margin:6px 0 0;
    color:#6b7280;
}

.count-badge{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
    white-space:nowrap;
}

.filter-card{
    background:white;
    border-radius:22px;
    padding:14px;
    margin-bottom:18px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    box-shadow:0 10px 24px rgba(15,23,42,.06);
}

.filter-input,
.filter-select,
.driver-select{
    border:none;
    outline:none;
    background:rgba(15,23,42,.05);
    border-radius:16px;
    padding:13px 14px;
    font-weight:700;
}

.filter-input{
    flex:1;
    min-width:260px;
}

.filter-select{
    min-width:210px;
}

.filter-btn,
.reset-btn{
    border:none;
    text-decoration:none;
    padding:13px 18px;
    border-radius:16px;
    font-weight:900;
    cursor:pointer;
}

.filter-btn{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
}

.reset-btn{
    background:#fee2e2;
    color:#991b1b;
}

.order-card{
    background:white;
    border-radius:26px;
    padding:20px;
    margin-bottom:16px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.order-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:16px;
    border-bottom:1px solid rgba(15,23,42,.07);
    padding-bottom:16px;
    margin-bottom:16px;
}

.order-title{
    display:flex;
    gap:14px;
    align-items:center;
}

.order-icon{
    width:52px;
    height:52px;
    border-radius:16px;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    flex-shrink:0;
}

.order-title h3{
    margin:0;
    color:#111827;
    font-size:20px;
    font-weight:900;
}

.order-title p{
    margin:4px 0 0;
    color:#6b7280;
}

.order-info-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
    margin-bottom:16px;
}

.order-info-grid div{
    background:rgba(15,23,42,.04);
    border-radius:16px;
    padding:13px;
}

.order-info-grid span,
.detail-grid small{
    display:block;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
    margin-bottom:5px;
}

.order-info-grid b,
.detail-grid b{
    color:#111827;
    font-weight:900;
}

.status-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
    background:#fef3c7;
    color:#92400e;
}

.status-cancelled{
    background:#fee2e2;
    color:#991b1b;
}

.status-completed{
    background:#dcfce7;
    color:#166534;
}

.status-searching_driver{
    background:#ede9fe;
    color:#5b21b6;
}

.status-waiting_response{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-dalam_pengiriman,
.status-driver_to_merchant,
.status-driver_to_pickup,
.status-driver_to_destination{
    background:#ffedd5;
    color:#9a3412;
}

.order-actions{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    align-items:center;
}

.assign-form{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-action{
    border:none;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:10px 14px;
    border-radius:14px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
    display:inline-block;
}

.btn-action.blue{
    background:#0ea5e9;
}

.btn-action.green{
    background:#16a34a;
}

.btn-action.red{
    background:#dc2626;
}

.btn-action.orange{
    background:#fb923c;
}

.finished-info{
    background:rgba(15,23,42,.05);
    color:#374151;
    padding:10px 14px;
    border-radius:14px;
    font-weight:900;
}

.modal-detail{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.58);
    z-index:9999;
    padding:24px;
    overflow-y:auto;
}

.modal-box{
    background:white;
    max-width:760px;
    margin:30px auto;
    border-radius:26px;
    padding:22px;
    box-shadow:0 25px 60px rgba(0,0,0,.25);
}

.modal-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:16px;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding-bottom:12px;
    margin-bottom:16px;
}

.modal-head h3{
    font-size:22px;
    font-weight:900;
    color:var(--primary-color);
    margin:0;
}

.modal-head p{
    margin:4px 0 0;
    color:#6b7280;
    font-weight:800;
}

.modal-head button{
    width:42px;
    height:42px;
    border:none;
    border-radius:50%;
    background:#fee2e2;
    color:#991b1b;
    font-size:25px;
    cursor:pointer;
}

.detail-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:12px;
}

.detail-grid div{
    background:rgba(15,23,42,.04);
    border-radius:16px;
    padding:12px;
}

.price-box{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.price-box div{
    display:flex;
    justify-content:space-between;
    background:rgba(15,23,42,.04);
    border-radius:14px;
    padding:12px;
}

.price-box .grand-total{
    background:rgba(15,23,42,.06);
    color:var(--primary-color);
    font-size:18px;
    font-weight:900;
}

.item-list{
    margin-top:10px;
}

.item-row{
    display:flex;
    justify-content:space-between;
    background:rgba(15,23,42,.04);
    padding:12px;
    border-radius:14px;
    margin-bottom:8px;
}

.empty-box{
    background:white;
    color:var(--primary-color);
    border-radius:20px;
    padding:20px;
    font-weight:900;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.cancel-box{
    margin-top:16px;
    background:#fee2e2;
    color:#991b1b;
    border-radius:16px;
    padding:14px;
    font-weight:900;
}

hr{
    border:none;
    border-top:1px solid rgba(15,23,42,.08);
    margin:18px 0;
}

@media(max-width:1000px){
    .order-info-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:700px){
    .page-head,
    .order-top{
        flex-direction:column;
        align-items:flex-start;
    }

    .count-badge{
        width:100%;
        text-align:center;
    }

    .filter-input,
    .filter-select,
    .filter-btn,
    .reset-btn{
        width:100%;
        min-width:100%;
        text-align:center;
    }

    .order-info-grid,
    .detail-grid{
        grid-template-columns:1fr;
    }

    .assign-form,
    .driver-select,
    .btn-action{
        width:100%;
    }

    .btn-action{
        text-align:center;
    }
}
</style>

<script>
function openOrderDetail(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'block';
    }
}

function closeOrderDetail(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'none';
    }
}

window.addEventListener('click', function(e){
    document.querySelectorAll('.modal-detail').forEach(function(modal){
        if(e.target === modal){
            modal.style.display = 'none';
        }
    });
});
</script>

@endsection