@extends('layouts.admin')

@section('content')

<div class="admin-dashboard">

    <div class="admin-head">
        <div>
            <h2>Dashboard Admin</h2>
            <p>Ringkasan performa JavaJek.</p>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <span>Total Order</span>
            <h3>{{ number_format($totalOrders ?? 0) }}</h3>
        </div>

        <div class="stat-card">
            <span>Order Selesai</span>
            <h3>{{ number_format($completedOrders ?? 0) }}</h3>
        </div>

        <div class="stat-card">
            <span>Total Driver</span>
            <h3>{{ number_format($totalDrivers ?? 0) }}</h3>
        </div>

        <div class="stat-card">
            <span>Total Merchant</span>
            <h3>{{ number_format($totalMerchants ?? 0) }}</h3>
        </div>
    </div>

    <div class="rank-grid">

        <div class="rank-card">
            <h3>🏆 Top Driver</h3>

            <div class="rank-table-wrap">
                <table class="rank-table">
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Rating</th>
                            <th>Order</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($topDrivers as $driver)
                            <tr>
                                <td>{{ $driver->user->name ?? 'Driver' }}</td>
                                <td>⭐ {{ number_format($driver->average_rating ?? 0, 1) }}</td>
                                <td>{{ $driver->completed_orders_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada data driver.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rank-card">
            <h3>🏪 Top Merchant</h3>

            <div class="rank-table-wrap">
                <table class="rank-table">
                    <thead>
                        <tr>
                            <th>Merchant</th>
                            <th>Rating</th>
                            <th>Order</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($topMerchants as $merchant)
                            <tr>
                                <td>{{ $merchant->name ?? 'Merchant' }}</td>
                                <td>⭐ {{ number_format($merchant->average_rating ?? 0, 1) }}</td>
                                <td>{{ $merchant->completed_orders_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada data merchant.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<style>
.admin-dashboard{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.admin-head,
.stat-card,
.rank-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 10px 28px rgba(15,23,42,.08);
}

.admin-head h2{
    margin:0;
    color:var(--primary, #f97316);
    font-size:28px;
    font-weight:900;
}

.admin-head p{
    margin:6px 0 0;
    color:#6b7280;
}

.stat-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
}

.stat-card span{
    color:#6b7280;
    font-size:13px;
    font-weight:800;
}

.stat-card h3{
    margin:8px 0 0;
    color:var(--primary, #f97316);
    font-size:28px;
    font-weight:900;
}

.rank-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.rank-card h3{
    margin:0 0 14px;
    color:var(--primary, #f97316);
    font-size:21px;
    font-weight:900;
}

.rank-table-wrap{
    overflow-x:auto;
}

.rank-table{
    width:100%;
    border-collapse:collapse;
    min-width:420px;
}

.rank-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary, #f97316);
    padding:12px;
    text-align:left;
    font-size:13px;
    font-weight:900;
}

.rank-table td{
    padding:12px;
    border-bottom:1px solid rgba(15,23,42,.07);
    color:#111827;
    font-weight:700;
}

@media(max-width:900px){
    .stat-grid,
    .rank-grid{
        grid-template-columns:1fr;
    }
}
</style>

@endsection