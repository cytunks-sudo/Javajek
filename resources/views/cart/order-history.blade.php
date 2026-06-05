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
                                <b>{{ $order->order_number ?? $order->order_code ?? '#'.$order->id }}</b>
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
                                @php
    $displayTotal = $order->grand_total > 0
        ? $order->grand_total
        : $order->total;
@endphp

<b>Rp {{ number_format($displayTotal) }}</b>
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
                        <p>{{ $order->order_number ?? $order->order_code ?? '#'.$order->id }}</p>
                    </div>

                    <button type="button" onclick="closeHistoryDetail('historyDetail{{ $order->id }}')">
                        ×
                    </button>
                </div>

                <div class="history-modal-body">

                    @if($order->status === 'completed' && !$order->rating)
                        <form method="POST" action="{{ route('orders.rating', $order->id) }}" class="rating-box">
                            @csrf

                            <h4>Beri Rating ⭐</h4>

                            <label>Rating Driver</label>
                            <select name="driver_rating" required>
                                <option value="">Pilih rating</option>
                                <option value="5">⭐⭐⭐⭐⭐ Sangat Baik</option>
                                <option value="4">⭐⭐⭐⭐ Baik</option>
                                <option value="3">⭐⭐⭐ Cukup</option>
                                <option value="2">⭐⭐ Kurang</option>
                                <option value="1">⭐ Buruk</option>
                            </select>

                            <textarea name="driver_review" placeholder="Ulasan untuk driver"></textarea>

                            @if($order->restaurant_id)
                                <label>Rating Merchant</label>
                                <select name="merchant_rating" required>
                                    <option value="">Pilih rating</option>
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Baik</option>
                                    <option value="4">⭐⭐⭐⭐ Baik</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Buruk</option>
                                </select>

                                <textarea name="merchant_review" placeholder="Ulasan untuk merchant"></textarea>
                            @endif

                            <button type="submit" class="btn-rating">
                                Kirim Rating
                            </button>
                        </form>
                    @elseif($order->rating)
                        <div class="rating-done-box">
                            <b>Rating sudah diberikan</b>

                            <div>
                                Driver:
                                <span>⭐ {{ $order->rating->driver_rating ?? '-' }}</span>
                            </div>

                            @if($order->restaurant_id)
                                <div>
                                    Merchant:
                                    <span>⭐ {{ $order->rating->merchant_rating ?? '-' }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

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
                        @php
    $displayTotal = $order->grand_total > 0
        ? $order->grand_total
        : $order->total;
@endphp

<b>Rp {{ number_format($displayTotal) }}</b>
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

    <hr>

    <h4>Rincian Perjalanan</h4>

@php
    $jarak = $order->distance_km ?? 0;
    $totalRide = $order->grand_total > 0
        ? $order->grand_total
        : $order->total;
@endphp


    <div class="nota-table-wrap">
        <table class="nota-table">
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
                {{ Str::limit($order->pickup_address ?? '-',25) }}
                →
                {{ Str::limit($order->destination_address ?? '-',25) }}
            </td>

            <td>
                {{ number_format($jarak,1) }} km
            </td>

            <td>
                Rp {{ number_format($totalRide) }}
            </td>
        </tr>

        <tr class="nota-total">
            <td colspan="2">Total Bayar</td>
            <td>Rp {{ number_format($totalRide) }}</td>
        </tr>
    </tbody>
</table>
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

                        <h4>Rincian Nota</h4>

<div class="nota-table-wrap">
    <table class="nota-table">
        <thead>
            <tr>
                <th>Makan</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @php
                $subtotalMakanan = 0;
            @endphp

            @forelse($order->items ?? [] as $item)
                @php
                    $qty = $item->qty ?? 0;
                    $price = $item->price ?? 0;
                    $lineTotal = $price * $qty;
                    $subtotalMakanan += $lineTotal;
                @endphp

                <tr>
                    <td>{{ $item->food->name ?? '-' }}</td>
                    <td>{{ $qty }}</td>
                    <td>Rp {{ number_format($price) }}</td>
                    <td>Rp {{ number_format($lineTotal) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-mini">Tidak ada item.</td>
                </tr>
            @endforelse

            @php
                $ongkir = $order->delivery_fee ?? 0;
                $jarak = $order->distance_km ?? 0;
                $grandTotalNota = $subtotalMakanan + $ongkir;
            @endphp

            <tr class="nota-ongkir">
                <td>Ongkir</td>
                <td>{{ number_format($jarak, 1) }} km</td>
                <td>Rp {{ number_format($ongkir) }}</td>
                <td>Rp {{ number_format($ongkir) }}</td>
            </tr>

            <tr class="nota-total">
                <td colspan="3">Total Bayar</td>
                <td>Rp {{ number_format($grandTotalNota) }}</td>
            </tr>
        </tbody>
    </table>
</div>


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
.nota-table-wrap{
    overflow-x:auto;
    margin-top:10px;
}

.nota-table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:16px;
    overflow:hidden;
}

.nota-table th{
    background:rgba(15,23,42,.05);
    color:var(--primary);
    font-size:12px;
    font-weight:900;
    padding:10px;
    text-align:left;
    white-space:nowrap;
}

.nota-table td{
    padding:11px 10px;
    border-bottom:1px solid rgba(15,23,42,.06);
    color:#111827;
    font-size:13px;
    font-weight:700;
    white-space:nowrap;
}

.nota-table td:first-child{
    white-space:normal;
}

.nota-ongkir td{
    background:rgba(15,23,42,.03);
    color:#111827;
}

.nota-total td{
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    font-weight:900;
    border-bottom:none;
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
    padding:16px;
    overflow:hidden;
}

.history-modal-box{
    background:white;
    width:100%;
    max-width:560px;
    height:calc(100vh - 32px);
    margin:0 auto;
    border-radius:28px;
    padding:0;
    box-shadow:0 20px 45px rgba(15,23,42,.28);
    overflow:hidden;
    display:flex;
    flex-direction:column;
}

.history-modal-head{
    flex-shrink:0;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding:16px 18px;
    background:white;
    z-index:2;
}

.history-modal-body{
    flex:1;
    overflow-y:auto;
    padding:16px 18px 24px;
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

.rating-box{
    margin-bottom:16px;
    padding:14px;
    border-radius:18px;
    background:rgba(15,23,42,.03);
    border:1px solid rgba(15,23,42,.08);
}

.rating-box h4{
    margin:0 0 12px;
    color:var(--primary);
    font-size:18px;
    font-weight:900;
}

.rating-box label{
    display:block;
    margin-top:10px;
    margin-bottom:6px;
    color:#111827;
    font-weight:800;
}

.rating-box select,
.rating-box textarea{
    width:100%;
    padding:12px;
    border-radius:14px;
    border:1px solid rgba(15,23,42,.18);
    margin-bottom:10px;
    outline:none;
    font-weight:700;
}

.rating-box textarea{
    min-height:64px;
}

.btn-rating{
    width:100%;
    padding:13px;
    border:none;
    border-radius:16px;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    font-weight:900;
    cursor:pointer;
    margin-top:8px;
}

.rating-done-box{
    margin-bottom:16px;
    padding:14px;
    border-radius:18px;
    background:#dcfce7;
    color:#166534;
    font-weight:800;
}

.rating-done-box b{
    display:block;
    margin-bottom:8px;
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
        margin:24px auto;
        border-radius:24px;
        padding:16px;
        max-height:90vh;
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