@extends('layouts.driver-page')

@section('content')

<div class="driver-income-page">

    <div class="income-head">
        <div>
            <h2>💵 Pendapatan Driver</h2>
            <p>Riwayat cash customer, komisi admin, dan hak bersih driver.</p>
        </div>

        <a href="/driver" class="back-btn">
            ← Dashboard
        </a>
    </div>

    <div class="income-summary-grid">

        <div class="summary-box">
            <small>💰 Pendapatan Hari Ini</small>
            <h3>Rp {{ number_format($todayIncome ?? 0) }}</h3>
        </div>

        <div class="summary-box">
            <small>📈 Pendapatan Bulan Ini</small>
            <h3>Rp {{ number_format($monthIncome ?? 0) }}</h3>
        </div>

        <div class="summary-box danger">
            <small>💸 Total Komisi Admin</small>
            <h3>Rp {{ number_format($totalCommission ?? 0) }}</h3>
        </div>

        <div class="summary-box">
            <small>💳 Saldo Saat Ini</small>
            <h3>Rp {{ number_format($driver->balance ?? 0) }}</h3>
        </div>

    </div>

    <div class="income-card">
        <h3>📜 Riwayat Pendapatan</h3>

        <div style="overflow-x:auto;">

<table class="income-table">

    <thead>
        <tr>
            <th>Tanggal</th>
            <th>No Order</th>
            <th>Jenis</th>
            <th>Cash</th>
            <th>Komisi</th>
            <th>Hak Driver</th>
        </tr>
    </thead>

    <tbody>

    @forelse($orders as $order)

        @php

            $orderType = $order->order_type ?? 'food';

            $cashCustomer = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : ($order->total ?? 0);

            $commission = $order->commissionTransaction
                ? abs($order->commissionTransaction->amount)
                : ($order->admin_commission_amount ?? 0);

            $driverNet = $cashCustomer - $commission;

        @endphp

        <tr>

            <td>
                {{ $order->updated_at?->format('d/m/Y') }}
            </td>

            <td>
                {{ $order->order_number ?? '#'.$order->id }}
            </td>

            <td>

                @if($orderType=='food')
                    🍔 Food
                @elseif($orderType=='ojek')
                    🏍️ Ojek
                @else
                    🚗 Car
                @endif

            </td>

            <td>
                Rp {{ number_format($cashCustomer) }}
            </td>

            <td class="text-danger">
                - Rp {{ number_format($commission) }}
            </td>

            <td class="text-success">
                Rp {{ number_format($driverNet) }}
            </td>

        </tr>

    @empty

        <tr>
            <td colspan="6" style="text-align:center;">
                Belum ada pendapatan.
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

</div>
    </div>

</div>

<style>
.driver-income-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.income-head,
.summary-box,
.income-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 10px 24px rgba(15,23,42,.07);
}

.income-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
}

.income-head h2{
    margin:0;
    color:var(--primary);
    font-size:26px;
    font-weight:900;
}

.income-head p{
    margin:6px 0 0;
    color:#6b7280;
    font-size:13px;
}
.income-table{
    width:100%;
    border-collapse:collapse;
    min-width:700px;
}

.income-table th{
    background:#fff7ed;
    color:var(--primary-color);
    padding:14px;
    font-size:13px;
    text-align:left;
    font-weight:900;
}

.income-table td{
    padding:14px;
    border-bottom:1px solid #eee;
    font-weight:700;
}

.income-table tr:hover{
    background:#fafafa;
}

.text-danger{
    color:#dc2626;
    font-weight:900;
}

.text-success{
    color:#16a34a;
    font-weight:900;
}
.back-btn{
    background:var(--primary);
    color:white;
    text-decoration:none;
    padding:11px 14px;
    border-radius:14px;
    font-weight:900;
}

.income-summary-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.summary-box small{
    display:block;
    color:#6b7280;
    font-size:13px;
    font-weight:800;
    margin-bottom:8px;
}

.summary-box h3{
    margin:0;
    color:var(--primary);
    font-size:25px;
    font-weight:900;
}

.summary-box.danger h3{
    color:#dc2626;
}

.income-card h3{
    margin:0 0 14px;
    color:var(--primary);
    font-size:22px;
    font-weight:900;
}

.income-list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.income-item{
    border:1px solid rgba(15,23,42,.06);
    border-radius:20px;
    padding:14px;
    background:#fff7ed;
}

.income-main{
    display:flex;
    gap:12px;
    align-items:flex-start;
    margin-bottom:12px;
}

.income-icon{
    width:44px;
    height:44px;
    border-radius:15px;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    flex-shrink:0;
}

.income-main b{
    display:block;
    color:#111827;
    font-weight:900;
}

.income-main p{
    margin:4px 0;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
}

.income-main small{
    color:#6b7280;
    font-size:12px;
}

.income-values{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:8px;
}

.income-values div{
    background:white;
    border-radius:14px;
    padding:10px;
}

.income-values span{
    display:block;
    color:#6b7280;
    font-size:11px;
    font-weight:800;
    margin-bottom:5px;
}

.income-values b{
    color:#111827;
    font-size:13px;
    font-weight:900;
}

.income-values b.minus{
    color:#dc2626;
}

.income-values .net{
    background:#ecfdf5;
}

.income-values .net b{
    color:#16a34a;
}

.empty-box{
    background:#fff7ed;
    color:var(--primary);
    border-radius:18px;
    padding:18px;
    text-align:center;
    font-weight:900;
}

@media(max-width:700px){
    .income-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .back-btn{
        width:100%;
        text-align:center;
    }

    .income-summary-grid,
    .income-values{
        grid-template-columns:1fr;
    }
}
</style>

@endsection