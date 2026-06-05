@extends('layouts.driver-page')

@section('content')

<div class="wallet-history-page">

    <div class="wallet-head">
        <div>
            <h2>💰 Riwayat Saldo</h2>
            <p>Lihat semua transaksi saldo driver Anda.</p>
        </div>
    </div>

    <div class="balance-card">
        <span>Saldo Saat Ini</span>

        <h3 class="{{ ($driver->balance ?? 0) < 0 ? 'minus' : '' }}">
            Rp {{ number_format($driver->balance ?? 0) }}
        </h3>
    </div>

    <div class="history-card">

        <div class="history-title">
            <h3>📜 Mutasi Saldo</h3>
        </div>

        <div class="table-wrap">
            <table class="wallet-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Saldo Akhir</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td>
                                <b>{{ $trx->created_at?->format('d/m/Y') }}</b><br>
                                <small>{{ $trx->created_at?->format('H:i') }}</small>
                            </td>

                            <td>
                                @if($trx->type == 'topup')
                                    <span class="type-badge plus">Topup</span>
                                @elseif($trx->type == 'commission')
                                    <span class="type-badge minus">Komisi</span>
                                @elseif($trx->type == 'adjustment')
                                    <span class="type-badge neutral">Koreksi</span>
                                @else
                                    <span class="type-badge neutral">{{ ucfirst($trx->type) }}</span>
                                @endif
                            </td>

                            <td>
                                {{ $trx->description ?? '-' }}
                            </td>

                            <td class="money plus">
                                @if($trx->amount > 0)
                                    Rp {{ number_format($trx->amount) }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="money minus">
                                @if($trx->amount < 0)
                                    Rp {{ number_format(abs($trx->amount)) }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="money">
                                Rp {{ number_format($trx->balance_after ?? 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-box">
                                    Belum ada transaksi saldo.
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
.wallet-history-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.wallet-head,
.balance-card,
.history-card{
    background:white;
    border-radius:26px;
    padding:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.wallet-head h2,
.history-title h3{
    margin:0;
    color:var(--primary);
    font-weight:900;
}

.wallet-head h2{
    font-size:26px;
}

.wallet-head p{
    margin:5px 0 0;
    color:#6b7280;
    font-size:13px;
}

.balance-card span{
    color:#6b7280;
    font-size:13px;
    font-weight:800;
}

.balance-card h3{
    margin:8px 0 0;
    color:var(--primary);
    font-size:32px;
    font-weight:900;
}

.balance-card h3.minus{
    color:#dc2626;
}

.history-title h3{
    font-size:21px;
    margin-bottom:14px;
}

.table-wrap{
    width:100%;
    overflow-x:auto;
}

.wallet-table{
    width:100%;
    border-collapse:collapse;
    min-width:780px;
}

.wallet-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary);
    font-size:13px;
    font-weight:900;
    text-align:left;
    padding:13px;
    white-space:nowrap;
}

.wallet-table td{
    padding:14px 13px;
    border-bottom:1px solid rgba(15,23,42,.07);
    color:#111827;
    font-size:13px;
    font-weight:700;
    vertical-align:middle;
}

.wallet-table small{
    color:#6b7280;
    font-weight:700;
}

.type-badge{
    display:inline-block;
    padding:7px 11px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.type-badge.plus{
    background:#dcfce7;
    color:#166534;
}

.type-badge.minus{
    background:#fee2e2;
    color:#991b1b;
}

.type-badge.neutral{
    background:#e5e7eb;
    color:#374151;
}

.money{
    text-align:right;
    white-space:nowrap;
    font-weight:900;
}

.money.plus{
    color:#16a34a;
}

.money.minus{
    color:#dc2626;
}

.empty-box{
    background:#f8fafc;
    border-radius:18px;
    padding:18px;
    text-align:center;
    color:var(--primary);
    font-weight:900;
}

@media(max-width:640px){
    .wallet-head,
    .balance-card,
    .history-card{
        border-radius:22px;
        padding:15px;
    }

    .balance-card h3{
        font-size:28px;
    }
}
</style>

@endsection