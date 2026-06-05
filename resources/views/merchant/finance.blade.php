@extends('layouts.merchant-page')

@section('content')

<div class="finance-page">

    <div class="finance-head">
        <div>
            <h2>💰 Keuangan Merchant</h2>
            <p>Riwayat pendapatan dari order yang sudah selesai.</p>
        </div>

        <a href="/merchant" class="back-btn">
            ← Dashboard
        </a>
    </div>

    <div class="finance-grid">
        <div class="finance-card">
            <small>💰 Pendapatan Hari Ini</small>
            <h3>Rp {{ number_format($todayRevenue) }}</h3>
        </div>

        <div class="finance-card">
            <small>📈 Pendapatan Bulan Ini</small>
            <h3>Rp {{ number_format($monthRevenue) }}</h3>
        </div>

        <div class="finance-card">
            <small>🍔 Order Selesai</small>
            <h3>{{ $completedOrders }}</h3>
        </div>
    </div>

    <div class="search-card">
        <form method="GET">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nomor order atau customer...">

            <button type="submit">
                🔍 Cari
            </button>
        </form>
    </div>

    <div class="table-card">
        <table class="finance-table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Restoran</th>
                    <th>Pendapatan</th>
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
                        {{ $order->updated_at?->format('d/m/Y H:i') }}
                    </td>

                    <td>
                        {{ $order->user->name ?? '-' }}
                    </td>

                    <td>
                        {{ $order->restaurant->name ?? '-' }}
                    </td>

                    <td>
                        <b class="money">
                            Rp {{ number_format($order->food_original_total ?? 0) }}
                        </b>
                    </td>

                    <td>
                        <button type="button"
                                class="detail-btn"
                                onclick="openFinanceDetail('financeDetail{{ $order->id }}')">
                            Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-box">
                            Belum ada riwayat pendapatan.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@foreach($orders as $order)
    <div id="financeDetail{{ $order->id }}" class="modal-detail">
        <div class="modal-box">

            <div class="modal-head">
                <div>
                    <h3>Detail Pendapatan</h3>
                    <p>{{ $order->order_number ?? '#'.$order->id }}</p>
                </div>

                <button type="button"
                        onclick="closeFinanceDetail('financeDetail{{ $order->id }}')">
                    ×
                </button>
            </div>

            <div class="info-grid">
                <div>
                    <small>Customer</small>
                    <b>{{ $order->user->name ?? '-' }}</b>
                </div>

                <div>
                    <small>Tanggal</small>
                    <b>{{ $order->updated_at?->format('d/m/Y H:i') }}</b>
                </div>

                <div>
                    <small>Restoran</small>
                    <b>{{ $order->restaurant->name ?? '-' }}</b>
                </div>

                <div>
                    <small>Status</small>
                    <b>{{ strtoupper($order->status) }}</b>
                </div>
            </div>

            <hr>

            <h4>🍔 Item Pesanan</h4>

            <table class="item-table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Qty</th>
                        <th>Harga Merchant</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                @php
                    $merchantSubtotal = 0;
                @endphp

                @forelse($order->items as $item)
                    @php
                        $qty = $item->qty ?? 1;
                        $price = $item->food->price ?? 0;
                        $lineTotal = $qty * $price;
                        $merchantSubtotal += $lineTotal;
                    @endphp

                    <tr>
                        <td>{{ $item->food->name ?? '-' }}</td>
                        <td class="center">{{ $qty }}</td>
                        <td class="right">Rp {{ number_format($price) }}</td>
                        <td class="right">Rp {{ number_format($lineTotal) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            Tidak ada item.
                        </td>
                    </tr>
                @endforelse
                </tbody>

                <tfoot>
                    <tr class="grand-row">
                        <td colspan="3">Total Pendapatan Merchant</td>
                        <td class="right">
                            Rp {{ number_format($merchantSubtotal) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
@endforeach

<style>
.finance-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.finance-head,
.search-card,
.table-card,
.finance-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}

.finance-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
}

.finance-head h2{
    margin:0;
    color:#ea580c;
    font-size:26px;
    font-weight:900;
}

.finance-head p{
    margin:5px 0 0;
    color:#6b7280;
}

.back-btn,
.detail-btn,
.search-card button{
    border:none;
    background:#f97316;
    color:white;
    padding:10px 14px;
    border-radius:14px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
}

.finance-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
}

.finance-card small{
    color:#6b7280;
    font-weight:800;
}

.finance-card h3{
    margin:8px 0 0;
    color:#ea580c;
    font-size:26px;
    font-weight:900;
}

.search-card form{
    display:flex;
    gap:10px;
}

.search-card input{
    flex:1;
    border:none;
    outline:none;
    background:#f8fafc;
    border-radius:16px;
    padding:14px;
    font-weight:700;
}

.table-card{
    overflow-x:auto;
}

.finance-table{
    width:100%;
    border-collapse:collapse;
}

.finance-table th{
    background:#fff7ed;
    color:#ea580c;
    padding:13px;
    font-size:12px;
    text-transform:uppercase;
    text-align:left;
    font-weight:900;
}

.finance-table td{
    padding:13px;
    border-bottom:1px solid #f1f5f9;
}

.money{
    color:#16a34a;
}

.modal-detail{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.58);
    z-index:99999;
    padding:22px;
    overflow-y:auto;
}

.modal-box{
    background:white;
    max-width:760px;
    margin:35px auto;
    border-radius:24px;
    padding:20px;
}

.modal-head{
    display:flex;
    justify-content:space-between;
    gap:14px;
    border-bottom:1px solid #fed7aa;
    padding-bottom:12px;
    margin-bottom:14px;
}

.modal-head h3{
    margin:0;
    color:#ea580c;
    font-weight:900;
}

.modal-head p{
    margin:4px 0 0;
    color:#6b7280;
}

.modal-head button{
    width:40px;
    height:40px;
    border:none;
    border-radius:50%;
    background:#fee2e2;
    color:#991b1b;
    font-size:24px;
    cursor:pointer;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
}

.info-grid div{
    background:#f8fafc;
    border-radius:14px;
    padding:12px;
}

.info-grid small{
    display:block;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
}

.info-grid b{
    color:#111827;
}

.item-table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:16px;
    overflow:hidden;
}

.item-table th{
    background:#fff7ed;
    color:#ea580c;
    padding:12px;
    text-align:left;
    font-size:12px;
    font-weight:900;
}

.item-table td{
    padding:12px;
    border-bottom:1px solid #f1f5f9;
}

.item-table .grand-row td{
    background:#ecfdf5;
    color:#16a34a;
    font-weight:900;
}

.center{
    text-align:center;
}

.right{
    text-align:right;
}

.empty-box{
    text-align:center;
    padding:18px;
    color:#ea580c;
    font-weight:900;
}

hr{
    border:none;
    border-top:1px solid #fed7aa;
    margin:16px 0;
}

@media(max-width:800px){
    .finance-grid,
    .info-grid{
        grid-template-columns:1fr;
    }

    .finance-head,
    .search-card form{
        flex-direction:column;
        align-items:stretch;
    }

    .finance-table{
        min-width:760px;
    }
}
</style>

<script>
function openFinanceDetail(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'block';
    }
}

function closeFinanceDetail(id){
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