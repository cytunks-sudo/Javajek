@extends('layouts.admin')

@section('content')

<div class="finance-page">

    <div class="page-head">
        <div>
            <h2>💰 Dashboard Keuangan</h2>
            <p>Ringkasan komisi admin, topup driver, withdraw, dan saldo driver.</p>
        </div>
    </div>

    <div class="finance-grid">

        <div class="finance-card green">
            <span>Komisi Hari Ini</span>
            <h3>Rp {{ number_format(abs($commissionToday)) }}</h3>
            <small>Dari potongan saldo driver hari ini</small>
        </div>

        <div class="finance-card blue">
            <span>Komisi Bulan Ini</span>
            <h3>Rp {{ number_format(abs($commissionMonth)) }}</h3>
            <small>Total komisi bulan berjalan</small>
        </div>

        <div class="finance-card orange">
            <span>Total Topup Driver</span>
            <h3>Rp {{ number_format($topupTotal) }}</h3>
            <small>Semua topup saldo driver</small>
        </div>

        <div class="finance-card red">
            <span>Total Withdraw</span>
            <h3>Rp {{ number_format(abs($withdrawTotal)) }}</h3>
            <small>Semua pengurangan saldo driver</small>
        </div>

        <div class="finance-card purple">
            <span>Total Saldo Driver</span>
            <h3>Rp {{ number_format($totalDriverBalance) }}</h3>
            <small>Akumulasi saldo semua driver</small>
        </div>

        <div class="finance-card dark">
            <span>Driver Saldo Minus</span>
            <h3>{{ $minusDrivers->count() }}</h3>
            <small>Driver dengan saldo di bawah Rp 0</small>
        </div>

    </div>

    <div class="finance-two-col">

        <div class="table-card">
            <div class="section-title">
                <h3>⚠️ Driver Saldo Minus</h3>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Saldo</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($minusDrivers as $driver)
                    <tr>
                        <td>
                            <b>{{ $driver->user->name ?? '-' }}</b><br>
                            <small>{{ $driver->user->email ?? '-' }}</small>
                        </td>
                        <td class="text-red">
                            Rp {{ number_format($driver->balance ?? 0) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">
                            <div class="empty-box">Tidak ada driver minus.</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-card">
            <div class="section-title">
                <h3>📉 Driver Saldo Rendah</h3>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Saldo</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($lowBalanceDrivers as $driver)
                    <tr>
                        <td>
                            <b>{{ $driver->user->name ?? '-' }}</b><br>
                            <small>{{ $driver->user->email ?? '-' }}</small>
                        </td>
                        <td class="text-orange">
                            Rp {{ number_format($driver->balance ?? 0) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">
                            <div class="empty-box">Tidak ada saldo rendah.</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="table-card">
        <div class="section-title">
            <h3>📜 Transaksi Saldo Terakhir</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Driver</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Saldo Akhir</th>
                </tr>
            </thead>

            <tbody>
            @forelse($latestTransactions as $trx)
                <tr>
                    <td>{{ $trx->created_at?->format('d/m/Y H:i') }}</td>

                    <td>
                        <b>{{ $trx->driver->user->name ?? '-' }}</b><br>
                        <small>{{ $trx->driver->user->email ?? '-' }}</small>
                    </td>

                    <td>
                        @if($trx->type == 'topup')
                            <span class="badge green">Topup</span>
                        @elseif($trx->type == 'commission')
                            <span class="badge red">Komisi</span>
                        @elseif($trx->type == 'adjustment')
                            <span class="badge orange">Withdraw</span>
                        @else
                            <span class="badge gray">{{ ucfirst($trx->type) }}</span>
                        @endif
                    </td>

                    <td>{{ $trx->description ?? '-' }}</td>

                    <td class="{{ $trx->amount >= 0 ? 'text-green' : 'text-red' }}">
                        {{ $trx->amount >= 0 ? '+' : '-' }}
                        Rp {{ number_format(abs($trx->amount)) }}
                    </td>

                    <td>
                        Rp {{ number_format($trx->balance_after) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-box">Belum ada transaksi saldo.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
.finance-page{
    width:100%;
}

.page-head{
    background:white;
    border-radius:26px;
    padding:22px;
    margin-bottom:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.page-head h2{
    margin:0;
    color:var(--primary-color);
    font-size:28px;
    font-weight:900;
}

.page-head p{
    margin:6px 0 0;
    color:#6b7280;
}

.finance-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
    margin-bottom:18px;
}

.finance-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
    border-left:7px solid #94a3b8;
}

.finance-card span{
    display:block;
    color:#6b7280;
    font-size:13px;
    font-weight:900;
}

.finance-card h3{
    margin:8px 0;
    font-size:28px;
    font-weight:900;
    color:#111827;
}

.finance-card small{
    color:#9ca3af;
    font-size:12px;
    font-weight:700;
}

.finance-card.green{border-left-color:#16a34a;}
.finance-card.blue{border-left-color:#2563eb;}
.finance-card.orange{border-left-color:#f97316;}
.finance-card.red{border-left-color:#dc2626;}
.finance-card.purple{border-left-color:#7c3aed;}
.finance-card.dark{border-left-color:#111827;}

.finance-two-col{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
    margin-bottom:18px;
}

.table-card{
    background:white;
    border-radius:26px;
    padding:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
    overflow-x:auto;
}

.section-title h3{
    margin:0 0 14px;
    color:var(--primary-color);
    font-size:20px;
    font-weight:900;
}

.table-card table{
    width:100%;
    border-collapse:collapse;
}

.table-card th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    padding:13px;
    text-align:left;
    font-size:12px;
    text-transform:uppercase;
    font-weight:900;
}

.table-card td{
    padding:13px;
    border-bottom:1px solid rgba(15,23,42,.06);
    vertical-align:middle;
}

.table-card small{
    color:#6b7280;
    font-size:12px;
}

.badge{
    display:inline-block;
    padding:7px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.badge.green{
    background:#dcfce7;
    color:#166534;
}

.badge.red{
    background:#fee2e2;
    color:#991b1b;
}

.badge.orange{
    background:#ffedd5;
    color:#9a3412;
}

.badge.gray{
    background:#e5e7eb;
    color:#374151;
}

.text-green{
    color:#16a34a;
    font-weight:900;
}

.text-red{
    color:#dc2626;
    font-weight:900;
}

.text-orange{
    color:#f97316;
    font-weight:900;
}

.empty-box{
    text-align:center;
    color:#6b7280;
    font-weight:900;
    padding:18px;
}

@media(max-width:1000px){
    .finance-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .finance-two-col{
        grid-template-columns:1fr;
    }
}

@media(max-width:640px){
    .finance-grid{
        grid-template-columns:1fr;
    }

    .table-card table{
        min-width:760px;
    }
}
</style>

@endsection