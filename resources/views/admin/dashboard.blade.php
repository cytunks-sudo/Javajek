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

            <div class="rank-list">
                @forelse($topDrivers as $driver)
                    <div class="rank-item">
                        <div>
                            <b>{{ $driver->user->name ?? 'Driver' }}</b>
                            <small>⭐ {{ number_format($driver->average_rating ?? 0, 1) }}</small>
                        </div>

                        <span>{{ $driver->completed_orders_count }} Order</span>
                    </div>
                @empty
                    <div class="empty-rank">Belum ada data driver.</div>
                @endforelse
            </div>
        </div>

        <div class="rank-card">
            <h3>🏪 Top Merchant</h3>

            <div class="rank-list">
                @forelse($topMerchants as $merchant)
                    <div class="rank-item">
                        <div>
                            <b>{{ $merchant->name ?? 'Merchant' }}</b>
                            <small>⭐ {{ number_format($merchant->average_rating ?? 0, 1) }}</small>
                        </div>

                        <span>{{ $merchant->completed_orders_count }} Order</span>
                    </div>
                @empty
                    <div class="empty-rank">Belum ada data merchant.</div>
                @endforelse
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
    color:var(--primary-color, #f97316);
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
    color:var(--primary-color, #f97316);
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
    color:var(--primary-color, #f97316);
    font-size:21px;
    font-weight:900;
}

.rank-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.rank-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:13px 14px;
    border-radius:18px;
    background:rgba(15,23,42,.035);
    border:1px solid rgba(15,23,42,.06);
}

.rank-item b{
    display:block;
    color:#111827;
    font-size:14px;
    font-weight:900;
    word-break:break-word;
}

.rank-item small{
    display:block;
    margin-top:3px;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
}

.rank-item span{
    flex-shrink:0;
    background:linear-gradient(135deg,var(--primary-color, #f97316),var(--secondary-color, #fb923c));
    color:white;
    padding:7px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.empty-rank{
    padding:14px;
    border-radius:18px;
    background:rgba(15,23,42,.035);
    color:#6b7280;
    font-weight:800;
}

@media(max-width:1000px){
    .stat-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .rank-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:640px){
    .admin-dashboard{
        gap:14px;
    }

    .admin-head,
    .stat-card,
    .rank-card{
        border-radius:20px;
        padding:15px;
    }

    .admin-head h2{
        font-size:23px;
    }

    .stat-grid{
        grid-template-columns:repeat(2,1fr);
        gap:10px;
    }

    .stat-card h3{
        font-size:23px;
    }

    .rank-item{
        align-items:flex-start;
        flex-direction:column;
    }

    .rank-item span{
        width:100%;
        text-align:center;
    }
}
</style>

@endsection