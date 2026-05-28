@extends('layouts.admin')

@section('content')

<div class="card-box">

    <div class="admin-order-head">
        <div>
            <h2>Order Masuk</h2>
            <p>Kelola order customer, driver, ongkir, dan status pesanan.</p>
        </div>
    </div>

    @forelse($orders as $order)

        @php
            $drivers = \App\Models\Driver::with('user')
                ->where('status', 'online')
                ->get();

            $isFinished = in_array($order->status, ['cancelled', 'completed']);
        @endphp

        <div class="order-row">

            <div class="order-left">
                <div class="order-id">
                    Order #{{ $order->id }}
                </div>

                <div class="order-meta">
                    Customer:
                    <b>{{ $order->user->name ?? '-' }}</b>
                </div>

                <div class="order-meta">
                    Status:
                    <span class="status-badge status-{{ $order->status }}">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>

                <div class="order-total">
                    Rp {{ number_format($order->grand_total ?? $order->total) }}
                </div>
            </div>

            <div class="order-right">

                <button type="button"
                        class="btn-mini blue"
                        onclick="openOrderDetail('orderDetail{{ $order->id }}')">
                    Detail
                </button>

                @if(!$isFinished)

                    <form method="POST"
                          action="/admin/orders/{{ $order->id }}/assign-driver"
                          class="assign-form">
                        @csrf

                        <select name="driver_id" class="driver-select">
                            @forelse($drivers as $driver)
                                <option value="{{ $driver->id }}">
                                    {{ $driver->user->name ?? '-' }}
                                    - {{ $driver->vehicle_type }}
                                </option>
                            @empty
                                <option value="">
                                    Tidak ada driver online
                                </option>
                            @endforelse
                        </select>

                        <button class="btn-mini">
                            Assign
                        </button>
                    </form>

                    <div class="action-list">
                        <a href="/admin/orders/{{ $order->id }}/status/accepted"
                           class="btn-mini">
                            Terima
                        </a>

                        <a href="/admin/orders/{{ $order->id }}/status/dalam_pengiriman"
                           class="btn-mini orange">
                            Dalam Pengiriman
                        </a>

                        <a href="/admin/orders/{{ $order->id }}/status/completed"
                           class="btn-mini green">
                            Selesai
                        </a>

                        <a href="/admin/orders/{{ $order->id }}/status/cancelled"
                           class="btn-mini red"
                           onclick="return confirm('Yakin ingin membatalkan order ini?')">
                            Batalkan
                        </a>
                    </div>

                @else

                    <div class="finished-info">
                        Order {{ $order->status }}.
                    </div>

                @endif

            </div>

        </div>

        <div id="orderDetail{{ $order->id }}" class="modal-detail">
            <div class="modal-box">

                <div class="modal-head">
                    <h3>Detail Order #{{ $order->id }}</h3>
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
                            <small>Restaurant</small>
                            <b>{{ $order->restaurant->name ?? '-' }}</b>
                        </div>

                        <div>
                            <small>Driver</small>
                            <b>{{ $order->driver->user->name ?? '-' }}</b>
                        </div>

                        <div>
                            <small>Status Order</small>
                            <b>{{ $order->status }}</b>
                        </div>

                        <div>
                            <small>Status Merchant</small>
                            <b>{{ $order->merchant_status }}</b>
                        </div>

                        <div>
                            <small>Status Driver</small>
                            <b>{{ $order->driver_status }}</b>
                        </div>
                    </div>

                    <hr>

                    <div class="price-box">
                        <div>
                            <span>Total Makanan</span>
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
    .admin-order-head{
        background:linear-gradient(135deg,#fff7ed,#ffffff);
        border:1px solid #fed7aa;
        border-radius:22px;
        padding:18px;
        margin-bottom:18px;
    }

    .admin-order-head h2{
        margin:0;
        font-size:26px;
        font-weight:900;
        color:#ea580c;
    }

    .admin-order-head p{
        margin-top:5px;
        color:#6b7280;
    }

    .order-row{
        display:flex;
        justify-content:space-between;
        gap:16px;
        align-items:flex-start;
        border:1px solid #fed7aa;
        background:linear-gradient(135deg,#ffffff,#fff7ed);
        border-radius:22px;
        padding:18px;
        margin-bottom:14px;
    }

    .order-left{
        flex:1;
    }

    .order-id{
        font-size:20px;
        font-weight:900;
        color:#111827;
        margin-bottom:8px;
    }

    .order-meta{
        margin-bottom:6px;
        color:#374151;
    }

    .order-total{
        color:#ea580c;
        font-size:18px;
        font-weight:900;
        margin-top:10px;
    }

    .order-right{
        min-width:330px;
        display:flex;
        flex-direction:column;
        gap:10px;
        align-items:flex-end;
    }

    .status-badge{
        display:inline-block;
        padding:5px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        color:white;
        background:#f97316;
    }

    .status-cancelled{
        background:#dc2626;
    }

    .status-completed{
        background:#16a34a;
    }

    .status-searching_driver{
        background:#7c3aed;
    }

    .status-waiting_response{
        background:#0ea5e9;
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
        box-shadow:0 8px 18px rgba(249,115,22,.18);
    }

    .btn-mini.blue{
        background:#0ea5e9;
    }

    .btn-mini.green{
        background:#16a34a;
    }

    .btn-mini.red{
        background:#dc2626;
    }

    .btn-mini.orange{
        background:#fb923c;
    }

    .assign-form{
        display:flex;
        gap:8px;
        width:100%;
    }

    .driver-select{
        flex:1;
        border:1px solid #fed7aa;
        border-radius:14px;
        padding:10px;
        outline:none;
    }

    .action-list{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        justify-content:flex-end;
    }

    .finished-info{
        background:#f3f4f6;
        color:#374151;
        padding:10px 14px;
        border-radius:14px;
        font-weight:800;
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
        max-width:720px;
        margin:30px auto;
        border-radius:26px;
        padding:20px;
        box-shadow:0 25px 60px rgba(0,0,0,.25);
    }

    .modal-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        border-bottom:1px solid #fed7aa;
        padding-bottom:12px;
        margin-bottom:16px;
    }

    .modal-head h3{
        font-size:22px;
        font-weight:900;
        color:#ea580c;
        margin:0;
    }

    .modal-head button{
        width:40px;
        height:40px;
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
        background:#fff7ed;
        border-radius:16px;
        padding:12px;
    }

    .detail-grid small{
        display:block;
        color:#6b7280;
        margin-bottom:4px;
    }

    .detail-grid b{
        color:#111827;
    }

    .price-box{
        display:flex;
        flex-direction:column;
        gap:8px;
    }

    .price-box div{
        display:flex;
        justify-content:space-between;
        background:#f9fafb;
        border-radius:14px;
        padding:12px;
    }

    .price-box .grand-total{
        background:#fff7ed;
        color:#ea580c;
        font-size:18px;
        font-weight:900;
    }

    .item-list{
        margin-top:10px;
    }

    .item-row{
        display:flex;
        justify-content:space-between;
        background:#f9fafb;
        padding:12px;
        border-radius:14px;
        margin-bottom:8px;
    }

    .empty-box{
        background:#fff7ed;
        color:#9a3412;
        border-radius:16px;
        padding:16px;
        font-weight:800;
    }

    .cancel-box{
        margin-top:16px;
        background:#fee2e2;
        color:#991b1b;
        border-radius:16px;
        padding:14px;
        font-weight:900;
    }

    @media(max-width:760px){
        .order-row{
            flex-direction:column;
        }

        .order-right{
            width:100%;
            min-width:0;
            align-items:stretch;
        }

        .assign-form{
            flex-direction:column;
        }

        .action-list{
            justify-content:flex-start;
        }

        .btn-mini{
            text-align:center;
        }

        .detail-grid{
            grid-template-columns:1fr;
        }
    }
</style>

<script>
function openOrderDetail(id)
{
    document.getElementById(id).style.display = 'block';
}

function closeOrderDetail(id)
{
    document.getElementById(id).style.display = 'none';
}

window.addEventListener('click', function(e){
    document.querySelectorAll('.modal-detail').forEach(function(modal){
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>

@endsection