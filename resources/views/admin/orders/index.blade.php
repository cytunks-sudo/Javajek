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
            <option value="driver_to_pickup" {{ request('status') == 'driver_to_pickup' ? 'selected' : '' }}>Driver Ke Jemput</option>
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

    <div class="order-table-card">

        <div class="table-wrap">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Nomor Order</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th>Driver</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)

                        @php
                            $isFinished = in_array($order->status, ['cancelled', 'completed']);
                            $orderType = $order->order_type ?? 'food';

                            $orderIcon = match($orderType) {
                                'ojek' => '🏍️',
                                'car' => '🚗',
                                default => '🍔',
                            };

                            $orderLabel = match($orderType) {
                                'ojek' => 'Ojek',
                                'car' => 'J-Car',
                                default => 'Food',
                            };

                            $drivers = [];

                            if (!$isFinished) {
                                $drivers = \App\Http\Controllers\AdminOrderController::availableDriversForOrder($order);
                            }

                            $displayTotal = ($order->grand_total ?? 0) > 0
    ? $order->grand_total
    : (($order->total ?? 0) + ($order->delivery_fee ?? 0));
                        @endphp

                        <tr>
              
                            <td>
                                
                                <div class="order-code-box">
                                    <b>{{ $order->order_number ?? $order->order_code ?? $order->id }}</b>
                                    <span class="type-badge type-{{ $orderType }}">
                                    {{ $orderIcon }} {{ $orderLabel }}
                                </span>
                                    <small>{{ $order->created_at?->format('d/m/Y H:i') }}</small>
                                                              
                                </div>
                            </td>

                            <td>
                                <b>{{ $order->user->name ?? '-' }}</b>
                                <small>{{ $order->user->phone ?? '' }}</small>
                                
                            </td>

                            <td>
                                <b>{{ $order->restaurant->name ?? $orderLabel }}</b>

                                @if($orderType != 'food')
                                    <small>{{ $order->pickup_address ? \Illuminate\Support\Str::limit($order->pickup_address, 38) : '-' }}</small>
                                @else
                                    <small>{{ $order->address ? \Illuminate\Support\Str::limit($order->address, 38) : '-' }}</small>
                                @endif
                                
                            </td>

                            <td>
                                <b>{{ $order->driver->user->name ?? 'Belum ada' }}</b>
                                <small>{{ strtoupper($order->driver_status ?? '-') }}</small>
                            </td>

                            <td>
                                <b class="price-text">
                                    Rp {{ number_format($displayTotal) }}
                                </b>

                                @if(($order->voucher_discount ?? 0) > 0)
                                    <small class="discount-mini">
                                        Voucher - Rp {{ number_format($order->voucher_discount) }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ strtoupper(str_replace('_',' ', $order->status)) }}
                                </span>
                            </td>

                            <td>
    <div class="action-box">
        

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
                            - {{ number_format($driver->distance_km, 1) }} km
                        </option>
                    @empty
                        <option value="">Tidak ada driver</option>
                    @endforelse
                </select>

                <button class="assign-btn">Kirim</button>
            </form>

            <button type="button"
                class="btn-action blue"
                onclick="openOrderDetail('orderDetail{{ $order->id }}')">
            Detail
        </button>

            <a href="/admin/orders/{{ $order->id }}/status/cancelled"
               class="btn-action red"
               onclick="return confirm('Yakin ingin membatalkan order ini?')">
                Batal
            </a>
        @else
            <span class="finished-info">
                {{ strtoupper($order->status) }}
            </span>
        @endif
    </div>
</td>
                            
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-box">
                                    Belum ada order masuk.
                                </div>
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

        $orderLabel = match($orderType) {
            'ojek' => 'Ojek',
            'car' => 'J-Car',
            default => 'Food',
        };

        $commission = $order->commissionTransaction;

        $itemSubTotal = 0;

        foreach ($order->items as $item) {
            $itemSubTotal += ($item->qty ?? 1) * ($item->price ?? 0);
        }

        if ($orderType == 'food') {
            $displayTotal = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : (
                    $itemSubTotal
                    + ($order->delivery_fee ?? 0)
                    - ($order->voucher_discount ?? 0)
                );
        } else {
            $displayTotal = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : ($order->total ?? 0);
        }

        $foodOriginalTotal = $order->food_original_total ?? 0;
        $foodMarkupAmount = $order->food_markup_amount ?? 0;
        $deliveryCommissionAmount = $order->delivery_commission_amount ?? 0;
        $adminCommissionAmount = $commission ? abs($commission->amount) : 0;

        $driverCash = $displayTotal;
        $driverNetCash = $driverCash - $adminCommissionAmount;
    @endphp

    <div id="orderDetail{{ $order->id }}" class="modal-detail">
        <div class="modal-box receipt-modal">

            <div class="modal-head">
                <div>
                    <h3>Detail Order #{{ $order->order_number ?? $order->order_code ?? $order->id }}</h3>
                    <p>{{ $orderIcon }} {{ $orderLabel }}</p>
                </div>

                <button type="button" onclick="closeOrderDetail('orderDetail{{ $order->id }}')">
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
                    <b>{{ $order->restaurant->name ?? $orderLabel }}</b>
                </div>

                <div>
                    <small>Status</small>
                    <b>{{ strtoupper(str_replace('_',' ', $order->status)) }}</b>
                </div>
            </div>

            <hr>

            @if($orderType == 'food')

                <div class="address-box">
                    <span>📍 Alamat Antar</span>
                    <p>{{ $order->address ?? '-' }}</p>
                </div>

                <h4>🍔 Rincian Nota</h4>

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

                        @if(($order->voucher_discount ?? 0) > 0)
                            <tr class="voucher-row">
                                <td colspan="3">Voucher {{ $order->voucher_code }}</td>
                                <td class="text-right">- Rp {{ number_format($order->voucher_discount) }}</td>
                            </tr>
                        @endif

                        <tr class="grand-row">
                            <td colspan="3">Grand Total</td>
                            <td class="text-right">Rp {{ number_format($displayTotal) }}</td>
                        </tr>
                    </tfoot>
                </table>

            @else

                <div class="address-box">
                    <span>📍 Jemput</span>
                    <p>{{ $order->pickup_address ?? '-' }}</p>
                </div>

                <div class="address-box">
                    <span>🏁 Tujuan</span>
                    <p>{{ $order->destination_address ?? '-' }}</p>
                </div>

                <h4>🧾 Rincian Perjalanan</h4>

                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Jemput → Tujuan</th>
                            <th>Jarak</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                {{ \Illuminate\Support\Str::limit($order->pickup_address ?? '-', 35) }}
                                →
                                {{ \Illuminate\Support\Str::limit($order->destination_address ?? '-', 35) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($order->distance_km ?? 0, 1) }} km
                            </td>

                            <td class="text-right">
                                Rp {{ number_format($order->total ?? 0) }}
                            </td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2">Tarif {{ $orderLabel }}</td>
                            <td class="text-right">Rp {{ number_format($order->total ?? 0) }}</td>
                        </tr>

                        @if(($order->voucher_discount ?? 0) > 0)
                            <tr class="voucher-row">
                                <td colspan="2">Voucher {{ $order->voucher_code }}</td>
                                <td class="text-right">- Rp {{ number_format($order->voucher_discount) }}</td>
                            </tr>
                        @endif

                        <tr class="grand-row">
                            <td colspan="2">Grand Total</td>
                            <td class="text-right">Rp {{ number_format($displayTotal) }}</td>
                        </tr>
                    </tfoot>
                </table>

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
                                <td>Komisi {{ $orderLabel }}</td>
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

            @if($order->status == 'cancelled')
                <div class="cancel-box">
                    Order ini sudah dibatalkan.
                </div>
            @endif

        </div>
    </div>

@endforeach

</div>

<style>
.admin-order-page{
    width:100%;
    display:flex;
    flex-direction:column;
    gap:16px;
}

.page-head,
.filter-card,
.order-table-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.page-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
}

.page-head h2{
    margin:0;
    color:var(--primary-color, var(--primary, #f97316));
    font-size:26px;
    font-weight:900;
}

.page-head p{
    margin:5px 0 0;
    color:#6b7280;
}

.count-badge{
    background:linear-gradient(
        135deg,
        var(--primary-color, var(--primary, #f97316)),
        var(--secondary-color, var(--secondary, #fb923c))
    );
    color:white;
    padding:12px 18px;
    border-radius:14px;
    font-weight:900;
    white-space:nowrap;
}

.filter-card{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.filter-input,
.filter-select,
.driver-select{
    border:none;
    outline:none;
    background:rgba(15,23,42,.05);
    border-radius:14px;
    padding:12px 13px;
    font-weight:700;
}

.filter-input{
    flex:1;
    min-width:240px;
}

.filter-select{
    min-width:200px;
}

.filter-btn,
.reset-btn{
    border:none;
    text-decoration:none;
    padding:12px 16px;
    border-radius:14px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
}

.filter-btn{
    background:linear-gradient(
        135deg,
        var(--primary-color, var(--primary, #f97316)),
        var(--secondary-color, var(--secondary, #fb923c))
    );
    color:white;
}

.reset-btn{
    background:#fee2e2;
    color:#991b1b;
}

.table-wrap{
    width:100%;
    overflow-x:auto;
}

.order-table{
    width:100%;
    min-width:900px;
    border-collapse:collapse;
}

.action-box{
    display:flex;
    gap:6px;
    flex-wrap:wrap;
    align-items:center;
    min-width:220px;
}

.assign-form{
    display:flex;
    gap:6px;
    align-items:center;
    width:100%;
}

.driver-select{
    flex:1;
    min-width:130px;
    font-size:11px;
    padding:8px 9px;
}

.assign-btn{
    padding:8px 10px;
}

.order-table th{
    background:#fff7ed;
    color:var(--primary-color, var(--primary, #f97316));
    padding:12px 10px;
    text-align:left;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.order-table td{
    padding:12px 10px;
    border-bottom:1px solid #f3f4f6;
    vertical-align:middle;
    font-size:12px;
    color:#111827;
}

.order-table td b{
    display:block;
    font-weight:900;
}

.order-table td small{
    display:block;
    margin-top:3px;
    color:#6b7280;
    font-size:11px;
    font-weight:700;
}

.order-code-box b{
    color:var(--primary-color, var(--primary, #f97316));
}

.price-text{
    color:#111827;
    white-space:nowrap;
}

.discount-mini{
    color:#16a34a !important;
}

.type-badge,
.status-badge{
    display:inline-block;
    padding:7px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    white-space:nowrap;
}

.type-food{
    background:#ffedd5;
    color:#9a3412;
}

.type-ojek{
    background:#dcfce7;
    color:#166534;
}

.type-car{
    background:#e5e7eb;
    color:#374151;
}

.status-badge{
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

.assign-form{
    display:flex;
    gap:6px;
    align-items:center;
    min-width:230px;
}

.driver-select{
    width:150px;
    font-size:11px;
    padding:9px 10px;
}

.assign-btn{
    border:none;
    background:linear-gradient(
        135deg,
        var(--primary-color, var(--primary, #f97316)),
        var(--secondary-color, var(--secondary, #fb923c))
    );
    color:white;
    padding:9px 10px;
    border-radius:11px;
    font-size:11px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
}

.action-row{
    display:flex;
    gap:6px;
    flex-wrap:wrap;
}

.btn-action{
    border:none;
    color:white;
    padding:8px 10px;
    border-radius:10px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
    font-size:11px;
    display:inline-block;
}

.btn-action.blue{
    background:#0ea5e9;
}

.btn-action.red{
    background:#dc2626;
}

.finished-info{
    background:#f3f4f6;
    color:#374151;
    padding:8px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    white-space:nowrap;
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
    max-width:780px;
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
    color:var(--primary-color, var(--primary, #f97316));
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

.detail-grid div,
.address-box,
.commission-grid div{
    background:rgba(15,23,42,.04);
    border-radius:16px;
    padding:12px;
}

.detail-grid small,
.address-box span,
.commission-grid span{
    display:block;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
    margin-bottom:5px;
}

.detail-grid b,
.commission-grid b{
    color:#111827;
    font-weight:900;
}

.address-box{
    margin-bottom:10px;
}

.address-box p{
    margin:0;
    font-weight:800;
    line-height:1.5;
}

.price-box{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.price-box div{
    display:flex;
    justify-content:space-between;
    gap:12px;
    background:rgba(15,23,42,.04);
    border-radius:14px;
    padding:12px;
}

.price-box .grand-total{
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:white;
    font-size:18px;
    font-weight:900;
}

.price-box .grand-total span,
.price-box .grand-total b{
    color:white;
}

.text-green{
    color:#16a34a !important;
}

.text-red{
    color:#dc2626 !important;
}

.commission-box{
    background:#fff7ed;
    border-left:6px solid var(--primary-color, var(--primary, #f97316));
    border-radius:18px;
    padding:14px;
}

.commission-box h4{
    margin:0 0 12px;
    color:var(--primary-color, var(--primary, #f97316));
    font-size:18px;
    font-weight:900;
}

.commission-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
}

.commission-note{
    margin:12px 0 0;
    color:#9a3412;
    font-weight:800;
    font-size:13px;
}

.item-list{
    margin-top:10px;
}

.item-row{
    display:flex;
    justify-content:space-between;
    gap:12px;
    background:rgba(15,23,42,.04);
    padding:12px;
    border-radius:14px;
    margin-bottom:8px;
}

.empty-box,
.empty-mini{
    background:white;
    color:var(--primary-color, var(--primary, #f97316));
    border-radius:20px;
    padding:20px;
    font-weight:900;
    text-align:center;
}

.empty-box{
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.empty-mini{
    background:#f8fafc;
    box-shadow:none;
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

@media(max-width:900px){
    .filter-input,
    .filter-select,
    .filter-btn,
    .reset-btn{
        width:100%;
        min-width:100%;
        text-align:center;
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

    .detail-grid,
    .commission-grid{
        grid-template-columns:1fr;
    }

    .modal-detail{
        padding:14px;
    }

    .modal-box{
        margin:12px auto;
        padding:18px;
    }
}

.receipt-modal{
    max-width:820px;
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
    color:var(--primary-color, var(--primary, #f97316));
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
    background:#16a34a;
    color:white;
    font-size:15px;
    font-weight:900;
}

.item-table .voucher-row td{
    color:#16a34a;
    font-weight:900;
}

.text-center{
    text-align:center;
}

.text-right{
    text-align:right;
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

.modal-box h4{
    margin:14px 0 10px;
    color:var(--primary-color, var(--primary, #f97316));
    font-weight:900;
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