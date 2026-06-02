@extends('layouts.customer-page')

@section('content')

<div class="history-page">

    <div class="history-head">
        <div>
            <h2>📜 Riwayat Pesanan</h2>
            <p>Daftar pesanan yang sudah selesai atau dibatalkan.</p>
        </div>

        <a href="/my-orders" class="active-order-btn">
            ← Pesanan Aktif
        </a>
    </div>

    <div class="history-card">
        <div class="table-wrap">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>No Order</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <b>{{ $order->order_number ?? '#'.$order->id }}</b>
                        </td>

                        <td>
                            <span class="type-badge">
                                @if($order->order_type == 'ojek')
                                    🏍️ Ojek
                                @elseif($order->order_type == 'car')
                                    🚗 J-Car
                                @else
                                    🍔 Food
                                @endif
                            </span>
                        </td>

                        <td>
                            <span class="status-badge {{ $order->status }}">
                                {{ strtoupper(str_replace('_',' ', $order->status)) }}
                            </span>
                        </td>

                        <td>
                            <b>Rp {{ number_format($order->total) }}</b>
                        </td>

                        <td>
                            <button type="button"
                                    class="detail-btn"
                                    onclick="openHistoryDetail('historyDetail{{ $order->id }}')">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-history">
                                Belum ada riwayat pesanan.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach($orders as $order)
        <div id="historyDetail{{ $order->id }}" class="history-modal">
            <div class="history-modal-box">

                <div class="history-modal-head">
                    <div>
                        <h3>Detail Pesanan</h3>
                        <p>{{ $order->order_number ?? '#'.$order->id }}</p>
                    </div>

                    <button type="button"
                            onclick="closeHistoryDetail('historyDetail{{ $order->id }}')">
                        ×
                    </button>
                </div>

                <div class="history-modal-body">

                    <div class="detail-row">
                        <span>Jenis</span>
                        <b>
                            @if($order->order_type == 'ojek')
                                🏍️ Ojek
                            @elseif($order->order_type == 'car')
                                🚗 J-Car
                            @else
                                🍔 Food
                            @endif
                        </b>
                    </div>

                    <div class="detail-row">
                        <span>Status</span>
                        <b>{{ strtoupper(str_replace('_',' ', $order->status)) }}</b>
                    </div>

                    <div class="detail-row">
                        <span>Total</span>
                        <b>Rp {{ number_format($order->total) }}</b>
                    </div>

                    <hr>

                    @if($order->order_type == 'ojek' || $order->order_type == 'car')

                        <div class="address-box">
                            <span>📍 Jemput</span>
                            <p>{{ $order->pickup_address ?? '-' }}</p>
                        </div>

                        <div class="address-box">
                            <span>🏁 Tujuan</span>
                            <p>{{ $order->destination_address ?? '-' }}</p>
                        </div>

                    @else

                        <div class="address-box">
                            <span>🏪 Merchant</span>
                            <p>{{ $order->restaurant->name ?? '-' }}</p>
                        </div>

                        <div class="address-box">
                            <span>📍 Alamat Antar</span>
                            <p>{{ $order->address ?? '-' }}</p>
                        </div>

                        <hr>

                        <h4>Item Pesanan</h4>

                        @forelse($order->items as $item)
                            <div class="nota-item">
                                <span>{{ $item->food->name ?? '-' }} x {{ $item->qty }}</span>
                                <b>Rp {{ number_format($item->price * $item->qty) }}</b>
                            </div>
                        @empty
                            <p class="empty-mini">Tidak ada item.</p>
                        @endforelse

                    @endif

                </div>

            </div>
        </div>
    @endforeach

</div>

<style>
.history-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.history-head{
    background:white;
    border-radius:28px;
    padding:20px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
}

.history-head h2{
    margin:0;
    color:var(--primary);
    font-size:28px;
    font-weight:900;
}

.history-head p{
    margin:7px 0 0;
    color:#6b7280;
    font-size:14px;
}

.active-order-btn{
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:12px 16px;
    border-radius:16px;
    text-decoration:none;
    font-weight:900;
    white-space:nowrap;
    box-shadow:0 10px 22px rgba(15,23,42,.14);
}

.history-card{
    background:white;
    border-radius:28px;
    padding:16px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.table-wrap{
    overflow-x:auto;
}

.history-table{
    width:100%;
    border-collapse:collapse;
}

.history-table th{
    color:var(--primary);
    font-size:13px;
    font-weight:900;
    text-align:left;
    padding:14px;
    border-bottom:2px solid rgba(15,23,42,.08);
    white-space:nowrap;
}

.history-table td{
    padding:15px 14px;
    border-bottom:1px solid rgba(15,23,42,.06);
    color:#111827;
    vertical-align:middle;
}

.type-badge{
    display:inline-block;
    background:rgba(15,23,42,.05);
    color:var(--primary);
    padding:8px 12px;
    border-radius:999px;
    font-weight:900;
    white-space:nowrap;
}

.status-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    background:#fef3c7;
    color:#92400e;
    white-space:nowrap;
}

.status-badge.completed{
    background:#dcfce7;
    color:#166534;
}

.status-badge.cancelled{
    background:#fee2e2;
    color:#991b1b;
}

.detail-btn{
    border:none;
    cursor:pointer;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:9px 14px;
    border-radius:14px;
    font-weight:900;
}

.empty-history{
    text-align:center;
    color:var(--primary);
    font-weight:900;
    padding:24px;
    background:rgba(15,23,42,.04);
    border-radius:18px;
}

.history-modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.58);
    z-index:9999;
    padding:24px;
    overflow-y:auto;
}

.history-modal-box{
    background:white;
    width:100%;
    max-width:560px;
    margin:60px auto;
    border-radius:28px;
    padding:20px;
    box-shadow:0 20px 45px rgba(15,23,42,.28);
}

.history-modal-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding-bottom:14px;
    margin-bottom:14px;
}

.history-modal-head h3{
    margin:0;
    color:var(--primary);
    font-size:22px;
    font-weight:900;
}

.history-modal-head p{
    margin:4px 0 0;
    color:#6b7280;
    font-size:13px;
    font-weight:800;
}

.history-modal-head button{
    width:40px;
    height:40px;
    border:none;
    border-radius:50%;
    background:#fee2e2;
    color:#991b1b;
    font-size:24px;
    cursor:pointer;
    font-weight:900;
}

.detail-row{
    display:flex;
    justify-content:space-between;
    gap:12px;
    padding:10px 0;
    border-bottom:1px dashed rgba(15,23,42,.08);
}

.detail-row span{
    color:#6b7280;
    font-weight:700;
}

.detail-row b{
    color:#111827;
    text-align:right;
}

.address-box{
    background:rgba(15,23,42,.04);
    padding:14px;
    border-radius:18px;
    margin-bottom:12px;
}

.address-box span{
    display:block;
    color:var(--primary);
    font-size:12px;
    font-weight:900;
    margin-bottom:6px;
}

.address-box p{
    margin:0;
    color:#111827;
    font-weight:700;
    line-height:1.5;
}

.history-modal-body h4{
    margin:0 0 12px;
    color:var(--primary);
    font-size:18px;
    font-weight:900;
}

.nota-item{
    display:flex;
    justify-content:space-between;
    gap:12px;
    background:rgba(15,23,42,.04);
    padding:12px;
    border-radius:16px;
    margin-bottom:8px;
}

.nota-item span{
    color:#111827;
    font-weight:700;
}

.nota-item b{
    color:var(--primary);
    white-space:nowrap;
}

.empty-mini{
    color:#6b7280;
    font-weight:700;
}

@media(max-width:640px){
    .history-head{
        flex-direction:column;
        align-items:flex-start;
        border-radius:24px;
        padding:16px;
    }

    .active-order-btn{
        width:100%;
        text-align:center;
    }

    .history-card{
        border-radius:24px;
        padding:12px;
    }

    .history-table{
        min-width:680px;
    }

    .history-modal{
        padding:14px;
    }

    .history-modal-box{
        margin:30px auto;
        border-radius:24px;
        padding:16px;
    }
}
</style>

<script>
function openHistoryDetail(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'block';
    }
}

function closeHistoryDetail(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'none';
    }
}

document.addEventListener('click', function(e){
    if(e.target.classList.contains('history-modal')){
        e.target.style.display = 'none';
    }
});
</script>

@endsection