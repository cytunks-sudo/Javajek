@extends('layouts.admin')

@section('content')

<div class="admin-order-page">

    <div class="page-head">
        <div>
            <h2>📜 History Order</h2>
            <p>Daftar order yang sudah selesai atau dibatalkan.</p>
        </div>

        <div class="count-badge">{{ $orders->count() }} History</div>
    </div>

    <div class="search-card">
        <form method="GET">
            <div class="search-box">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nomor order, customer, driver, restoran...">

                <button type="submit">🔍 Cari</button>
            </div>
        </form>
    </div>

    <div class="table-card">
        <table class="history-table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>Jenis</th>
                    <th>Customer</th>
                    <th>Driver</th>
                    <th>Total</th>
                    <th>Komisi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($orders as $order)
                @php
                    $orderType = $order->order_type ?? 'food';

                    $orderIcon = match($orderType) {
                        'ojek' => '🏍️',
                        'car' => '🚗',
                        default => '🍔',
                    };

                    if ($orderType == 'food') {
                        $tableTotal = ($order->food_original_total ?? 0)
                            + ($order->food_markup_amount ?? 0)
                            + ($order->delivery_fee ?? 0);

                        if ($tableTotal <= 0) {
                            $tableTotal = ($order->grand_total ?? 0) > 0
                                ? $order->grand_total
                                : ($order->total ?? 0);
                        }
                    } else {
                        $tableTotal = ($order->grand_total ?? 0) > 0
                            ? $order->grand_total
                            : ($order->total ?? 0);
                    }

                    $commission = $order->commissionTransaction;
                @endphp

                <tr>
                    <td><b>{{ $order->order_number ?? '#'.$order->id }}</b></td>

                    <td>
                        <span class="type-badge">
                            {{ $orderIcon }} {{ ucfirst($orderType) }}
                        </span>
                    </td>

                    <td><b>{{ $order->user->name ?? '-' }}</b></td>

                    <td><b>{{ $order->driver->user->name ?? '-' }}</b></td>

                    <td><b>Rp {{ number_format($tableTotal) }}</b></td>

                    <td>
                        @if($commission)
                            <span class="money-minus">
                                Rp {{ number_format(abs($commission->amount)) }}
                            </span>
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>

                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ strtoupper(str_replace('_',' ', $order->status)) }}
                        </span>
                    </td>

                    <td>
                        <button type="button"
                                class="btn-detail"
                                onclick="openOrderDetail('historyDetail{{ $order->id }}')">
                            Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-box">Belum ada history order.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@foreach($orders as $order)
    @php
        $orderType = $order->order_type ?? 'food';

        $orderIcon = match($orderType) {
            'ojek' => '🏍️',
            'car' => '🚗',
            default => '🍔',
        };

        $commission = $order->commissionTransaction;

        $itemSubTotal = 0;

        foreach ($order->items as $item) {
            $itemSubTotal += ($item->qty ?? 1) * ($item->price ?? 0);
        }

        $foodOriginalTotal = $order->food_original_total ?? 0;
        $foodMarkupAmount = $order->food_markup_amount ?? 0;
        $deliveryCommissionAmount = $order->delivery_commission_amount ?? 0;

        if ($orderType == 'food') {
            $grandTotal = $itemSubTotal + ($order->delivery_fee ?? 0);

            if ($grandTotal <= 0) {
                $grandTotal = ($order->grand_total ?? 0) > 0
                    ? $order->grand_total
                    : ($order->total ?? 0);
            }
        } else {
            $grandTotal = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : ($order->total ?? 0);
        }

        $adminCommissionAmount = $commission ? abs($commission->amount) : 0;
        $driverCash = $grandTotal;
        $driverNetCash = $driverCash - $adminCommissionAmount;
    @endphp

    <div id="historyDetail{{ $order->id }}" class="modal-detail">
        <div class="modal-box">

            <div class="modal-head">
                <div>
                    <h3>Detail Order {{ $order->order_number ?? '#'.$order->id }}</h3>
                    <p>{{ $orderIcon }} {{ ucfirst($orderType) }}</p>
                </div>

                <button type="button"
                        onclick="closeOrderDetail('historyDetail{{ $order->id }}')">
                    ×
                </button>
            </div>

            <div class="detail-grid">
                <div>
                    <small>Customer</small>
                    <b>{{ $order->user->name ?? '-' }}</b>
                </div>

                <div>
                    <small>Driver</small>
                    <b>{{ $order->driver->user->name ?? '-' }}</b>
                </div>

                <div>
                    <small>Restoran / Layanan</small>
                    <b>{{ $order->restaurant->name ?? ucfirst($orderType) }}</b>
                </div>

                <div>
                    <small>Status</small>
                    <b>{{ strtoupper(str_replace('_',' ', $order->status)) }}</b>
                </div>
            </div>

            @if($orderType == 'food')
                <hr>

                <h4>🍔 Item Pesanan</h4>

                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($order->items as $item)
                        @php
                            $qty = $item->qty ?? 1;
                            $price = $item->price ?? 0;
                            $lineTotal = $qty * $price;
                        @endphp

                        <tr>
                            <td>{{ $item->food->name ?? '-' }}</td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-right">Rp {{ number_format($price) }}</td>
                            <td class="text-right">Rp {{ number_format($lineTotal) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Tidak ada item pesanan.</td>
                        </tr>
                    @endforelse
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="3">Sub Total Produk</td>
                            <td class="text-right">Rp {{ number_format($itemSubTotal) }}</td>
                        </tr>

                        <tr>
                            <td colspan="3">Ongkir</td>
                            <td class="text-right">Rp {{ number_format($order->delivery_fee ?? 0) }}</td>
                        </tr>

                        <tr class="grand-row">
                            <td colspan="3">Grand Total</td>
                            <td class="text-right">Rp {{ number_format($grandTotal) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <hr>

                <div class="price-box">
                    <div>
                        <span>Total Tarif</span>
                        <b>Rp {{ number_format($order->total ?? 0) }}</b>
                    </div>

                    <div class="grand-total">
                        <span>Grand Total</span>
                        <b>Rp {{ number_format($grandTotal) }}</b>
                    </div>
                </div>
            @endif

            @if($commission)
                <hr>

                <div class="commission-box">
                    <h4>💰 Rincian Komisi Admin</h4>

                    <table class="commission-table">
                        <tr>
                            <td>Total Customer Bayar</td>
                            <td>Rp {{ number_format($driverCash) }}</td>
                        </tr>

                        @if($orderType == 'food')
                            <tr>
                                <td>Harga Asli Merchant</td>
                                <td>Rp {{ number_format($foodOriginalTotal) }}</td>
                            </tr>

                            <tr>
                                <td>Ongkir</td>
                                <td>Rp {{ number_format($order->delivery_fee ?? 0) }}</td>
                            </tr>

                            <tr class="divider">
                                <td colspan="2"></td>
                            </tr>

                            <tr class="minus">
                                <td>Komisi Food / Markup</td>
                                <td>- Rp {{ number_format($foodMarkupAmount) }}</td>
                            </tr>

                            <tr class="minus">
                                <td>Komisi Ongkir</td>
                                <td>- Rp {{ number_format($deliveryCommissionAmount) }}</td>
                            </tr>
                        @else
                            <tr class="minus">
                                <td>Komisi {{ ucfirst($orderType) }}</td>
                                <td>- Rp {{ number_format($adminCommissionAmount) }}</td>
                            </tr>
                        @endif

                        <tr class="total">
                            <td>Total Komisi Admin</td>
                            <td>Rp {{ number_format($adminCommissionAmount) }}</td>
                        </tr>

                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>

                        <tr>
                            <td>Driver Pegang Cash</td>
                            <td>Rp {{ number_format($driverCash) }}</td>
                        </tr>

                        <tr class="success">
                            <td>Hak Bersih Driver</td>
                            <td>Rp {{ number_format($driverNetCash) }}</td>
                        </tr>

                        <tr>
                            <td>Saldo Sebelum</td>
                            <td>Rp {{ number_format($commission->balance_before) }}</td>
                        </tr>

                        <tr>
                            <td>Saldo Sesudah</td>
                            <td>Rp {{ number_format($commission->balance_after) }}</td>
                        </tr>

                        <tr>
                            <td>Waktu Potong</td>
                            <td>{{ $commission->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            @endif

        </div>
    </div>
@endforeach

<style>
.admin-order-page{width:100%}

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

.page-head p{margin:6px 0 0;color:#6b7280}

.count-badge{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
    white-space:nowrap;
}

.search-card,
.table-card{
    background:white;
    border-radius:26px;
    padding:18px;
    margin-bottom:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.search-box{display:flex;gap:12px}

.search-box input{
    flex:1;
    border:none;
    outline:none;
    background:rgba(15,23,42,.05);
    border-radius:16px;
    padding:14px 18px;
    font-size:14px;
    font-weight:700;
}

.search-box button{
    border:none;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:0 24px;
    border-radius:16px;
    font-weight:900;
    cursor:pointer;
}

.table-card{
    padding:20px;
    overflow-x:auto;
}

.history-table{
    width:100%;
    border-collapse:collapse;
}

.history-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    padding:12px;
    font-size:10px;
    font-weight:900;
    text-transform:uppercase;
    text-align:left;
}

.history-table td{
    padding:5px;
    border-bottom:1px solid rgba(15,23,42,.06);
    vertical-align:middle;
}

.type-badge{
    display:inline-block;
    background:#fff7ed;
    color:var(--primary-color);
    padding:8px 12px;
    border-radius:999px;
    font-weight:900;
    white-space:nowrap;
}

.status-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:10px;
    font-weight:900;
    white-space:nowrap;
    background:#fef3c7;
    color:#92400e;
}

.status-cancelled{background:#fee2e2;color:#991b1b}
.status-completed{background:#dcfce7;color:#166534}

.money-minus{
    color:#dc2626;
    font-weight:900;
    white-space:nowrap;
}

.muted{
    color:#9ca3af;
    font-weight:900;
}

.btn-detail{
    border:none;
    background:#0ea5e9;
    color:white;
    padding:10px 14px;
    border-radius:14px;
    font-weight:900;
    cursor:pointer;
}

.modal-detail{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.58);
    z-index:99999;
    padding:24px;
    overflow-y:auto;
}

.modal-box{
    background:white;
    max-width:820px;
    margin:30px auto;
    border-radius:26px;
    padding:22px;
    box-shadow:0 25px 60px rgba(0,0,0,.25);
}

.modal-head{
    display:flex;
    justify-content:space-between;
    gap:16px;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding-bottom:14px;
    margin-bottom:16px;
}

.modal-head h3{
    margin:0;
    color:var(--primary-color);
    font-size:22px;
    font-weight:900;
}

.modal-head p{
    margin:5px 0 0;
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

.detail-grid small{
    display:block;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
    margin-bottom:5px;
}

.detail-grid b{
    color:#111827;
    font-weight:900;
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

.item-table,
.commission-table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:18px;
    overflow:hidden;
}

.item-table th{
    background:#fff7ed;
    color:var(--primary-color);
    padding:12px;
    font-size:12px;
    font-weight:900;
    text-align:left;
}

.item-table td,
.commission-table td{
    padding:12px;
    border-bottom:1px solid #f1f5f9;
}

.item-table tfoot td{
    font-weight:900;
}

.item-table .grand-row td{
    background:#fff7ed;
    color:#ea580c;
    font-size:15px;
}

.text-center{text-align:center}
.text-right{text-align:right}

.commission-box{
    background:#fff7ed;
    border-left:6px solid var(--primary-color);
    border-radius:18px;
    padding:14px;
}

.commission-box h4{
    margin:0 0 12px;
    color:var(--primary-color);
    font-size:18px;
    font-weight:900;
}

.commission-table{
    border-radius:16px;
}

.commission-table td:first-child{
    color:#475569;
    font-weight:700;
}

.commission-table td:last-child{
    text-align:right;
    font-weight:900;
    color:#111827;
}

.commission-table .divider td{
    padding:0;
    height:10px;
    background:#f8fafc;
    border:none;
}

.commission-table .minus td{
    color:#dc2626;
    font-weight:900;
}

.commission-table .total td{
    background:#fff7ed;
    color:#ea580c;
    font-size:15px;
    font-weight:900;
}

.commission-table .success td{
    background:#ecfdf5;
    color:#16a34a;
    font-size:15px;
    font-weight:900;
}

.empty-box{
    background:white;
    color:var(--primary-color);
    border-radius:20px;
    padding:20px;
    font-weight:900;
    text-align:center;
}

hr{
    border:none;
    border-top:1px solid rgba(15,23,42,.08);
    margin:18px 0;
}

@media(max-width:900px){
    .history-table{
        min-width:900px;
    }
}

@media(max-width:700px){
    .page-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .count-badge{
        width:100%;
        text-align:center;
    }

    .search-box{
        flex-direction:column;
    }

    .search-box button{
        height:50px;
    }

    .detail-grid{
        grid-template-columns:1fr;
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