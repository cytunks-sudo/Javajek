@extends('layouts.admin')

@section('content')

<div class="usage-page">

    <div class="usage-head">
        <div>
            <h2>🎟️ Riwayat Voucher</h2>
            <p>Catatan voucher yang dipakai customer di Food, Ojek, dan Car.</p>
        </div>
    </div>

    <div class="usage-stat-grid">
        <div class="stat-card">
            <span>Total Pemakaian</span>
            <b>{{ number_format($totalUsage ?? 0) }}</b>
        </div>

        <div class="stat-card">
            <span>Total Diskon</span>
            <b>Rp {{ number_format($totalDiscount ?? 0) }}</b>
        </div>

        <div class="stat-card">
            <span>Food</span>
            <b>{{ number_format($foodUsage ?? 0) }}</b>
        </div>

        <div class="stat-card">
            <span>Ojek / Car</span>
            <b>{{ number_format($rideUsage ?? 0) }}</b>
        </div>
    </div>

    <div class="usage-card">

        <form method="GET" action="/admin/voucher-usages" class="filter-box">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari voucher, customer, no order...">

            <select name="service_type">
                <option value="">Semua Layanan</option>
                <option value="food" {{ request('service_type') == 'food' ? 'selected' : '' }}>Food</option>
                <option value="ojek" {{ request('service_type') == 'ojek' ? 'selected' : '' }}>Ojek</option>
                <option value="car" {{ request('service_type') == 'car' ? 'selected' : '' }}>Car</option>
            </select>

            <button type="submit">🔍 Cari</button>

            @if(request('search') || request('service_type'))
                <a href="/admin/voucher-usages" class="reset-btn">Reset</a>
            @endif
        </form>

        <div class="table-wrap">
            <table class="usage-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Voucher</th>
                        <th>Customer</th>
                        <th>Order</th>
                        <th>Layanan</th>
                        <th>Diskon</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usages as $usage)
                        <tr>
                            <td>
                                <b>{{ $usage->created_at?->format('d/m/Y') }}</b>
                                <small>{{ $usage->created_at?->format('H:i') }}</small>
                            </td>

                            <td>
                                <b class="voucher-code">{{ $usage->voucher_code }}</b>
                                <small>{{ $usage->voucher->name ?? '-' }}</small>
                            </td>

                            <td>
                                <b>{{ $usage->user->name ?? '-' }}</b>
                                <small>{{ $usage->user->phone ?? '' }}</small>
                            </td>

                            <td>
                                <b>{{ $usage->order->order_number ?? $usage->order->order_code ?? '-' }}</b>
                            </td>

                            <td>
                                @if($usage->service_type == 'food')
                                    <span class="badge food">🍔 Food</span>
                                @elseif($usage->service_type == 'ojek')
                                    <span class="badge ojek">🏍️ Ojek</span>
                                @elseif($usage->service_type == 'car')
                                    <span class="badge car">🚗 Car</span>
                                @else
                                    <span class="badge all">✨ Semua</span>
                                @endif
                            </td>

                            <td>
                                <b class="discount-text">
                                    Rp {{ number_format($usage->discount_amount) }}
                                </b>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-box">
                                    Belum ada voucher yang digunakan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>

<style>
.usage-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.usage-head,
.usage-card,
.stat-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.usage-head h2{
    margin:0;
    color:var(--primary, #f97316);
    font-size:28px;
    font-weight:900;
}

.usage-head p{
    margin:6px 0 0;
    color:#6b7280;
}

.usage-stat-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
}

.stat-card span{
    display:block;
    color:#6b7280;
    font-size:13px;
    font-weight:800;
}

.stat-card b{
    display:block;
    margin-top:8px;
    color:var(--primary, #f97316);
    font-size:26px;
    font-weight:900;
}

.filter-box{
    display:flex;
    gap:10px;
    margin-bottom:16px;
}

.filter-box input,
.filter-box select{
    border:none;
    outline:none;
    background:#f8fafc;
    border-radius:14px;
    padding:12px;
    font-weight:700;
}

.filter-box input{
    flex:1;
}

.filter-box button,
.reset-btn{
    border:none;
    background:linear-gradient(135deg,var(--primary, #f97316),var(--secondary, #fb923c));
    color:white;
    padding:12px 16px;
    border-radius:14px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
}

.reset-btn{
    background:#6b7280;
}

.table-wrap{
    overflow-x:auto;
}

.usage-table{
    width:100%;
    border-collapse:collapse;
    min-width:850px;
}

.usage-table th{
    background:#fff7ed;
    color:var(--primary, #f97316);
    padding:12px;
    text-align:left;
    font-size:12px;
    font-weight:900;
}

.usage-table td{
    padding:12px;
    border-bottom:1px solid #f3f4f6;
    vertical-align:middle;
    font-size:13px;
}

.usage-table td b{
    display:block;
    color:#111827;
    font-weight:900;
}

.usage-table td small{
    display:block;
    margin-top:4px;
    color:#6b7280;
    font-size:12px;
}

.voucher-code{
    color:var(--primary, #f97316) !important;
}

.discount-text{
    color:#16a34a !important;
}

.badge{
    display:inline-block;
    padding:7px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    white-space:nowrap;
}

.food{
    background:#ffedd5;
    color:#9a3412;
}

.ojek{
    background:#dcfce7;
    color:#166534;
}

.car{
    background:#e5e7eb;
    color:#374151;
}

.all{
    background:#ede9fe;
    color:#6d28d9;
}

.empty-box{
    text-align:center;
    color:var(--primary, #f97316);
    font-weight:900;
    padding:20px;
}

@media(max-width:900px){
    .usage-stat-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .filter-box{
        flex-direction:column;
    }
}

@media(max-width:640px){
    .usage-stat-grid{
        grid-template-columns:1fr;
    }
}
</style>

@endsection