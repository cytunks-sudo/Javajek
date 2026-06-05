@extends('layouts.admin')

@section('content')

<div class="wallet-page">

    <div class="page-head">
        <div>
            <h2>💰 Saldo Driver</h2>
            <p>Kelola saldo driver, topup manual, withdraw, koreksi saldo, dan history transaksi.</p>
        </div>
    </div>

    <form method="GET" action="/admin/driver-wallet" class="search-card">
        <input type="text"
               id="walletSearch"
               placeholder="Cari nama driver, email, kendaraan, atau nomor plat..."
               class="search-input"
               onkeyup="filterDrivers()">

        <button type="button" class="search-btn">
            🔍 Cari
        </button>
    </form>

    <div class="table-card">

        <table class="wallet-table">

            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Kendaraan</th>
                    <th>Plat</th>
                    <th>Status</th>
                    <th>Saldo</th>
                    <th width="260">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($drivers as $driver)

                <tr class="driver-wallet-item"
                    data-search="{{ strtolower(($driver->user->name ?? '').' '.($driver->user->email ?? '').' '.($driver->vehicle_type ?? '').' '.($driver->plate_number ?? '')) }}">

                    <td>
                        <div class="driver-cell">
                            <div class="avatar">
                                {{ strtoupper(substr($driver->user->name ?? 'D', 0, 1)) }}
                            </div>

                            <div>
                                <b>{{ $driver->user->name ?? '-' }}</b>
                                <small>{{ $driver->user->email ?? '-' }}</small>
                            </div>
                        </div>
                    </td>

                    <td>
                        <span class="soft-badge">
                            {{ $driver->vehicle_type == 'mobil' ? '🚗 Mobil' : '🛵 Motor' }}
                        </span>
                    </td>

                    <td>
                        <span class="plate-badge">
                            {{ strtoupper($driver->plate_number ?? '-') }}
                        </span>
                    </td>

                    <td>
                        <span class="status-badge {{ $driver->status }}">
                            {{ strtoupper($driver->status ?? '-') }}
                        </span>
                    </td>

                    <td>
                        <div class="balance-cell">
                            Rp {{ number_format($driver->balance ?? 0) }}
                        </div>
                    </td>

                    <td>
                        <div class="action-box">

                            <button type="button"
                                    class="btn-topup"
                                    onclick="openWalletModal('topupModal{{ $driver->id }}')">
                                + Topup
                            </button>

                            <button type="button"
                                    class="btn-withdraw"
                                    onclick="openWalletModal('withdrawModal{{ $driver->id }}')">
                                - Withdraw
                            </button>


                            <button type="button"
                                    class="btn-history"
                                    onclick="openWalletModal('historyModal{{ $driver->id }}')">
                                📜 History
                            </button>
                        </div>

                        {{-- MODAL HISTORY --}}
                        <div id="historyModal{{ $driver->id }}" class="wallet-modal">
                            <div class="wallet-modal-box history-box">

                                <div class="modal-head">
                                    <div>
                                        <h3>📜 History Saldo</h3>
                                        <p>{{ $driver->user->name ?? '-' }}</p>
                                    </div>

                                    <button type="button"
                                            onclick="closeWalletModal('historyModal{{ $driver->id }}')">
                                        ×
                                    </button>
                                </div>

                                <div class="history-summary">
                                    <div>
                                        <span>Saldo Saat Ini</span>
                                        <b>Rp {{ number_format($driver->balance ?? 0) }}</b>
                                    </div>

                                    <div>
                                        <span>Total Transaksi</span>
                                        <b>{{ $driver->walletTransactions->count() }}</b>
                                    </div>
                                </div>

                                <div class="history-table-wrap">

                                    <div class="history-row history-head">
                                        <div>Jenis</div>
                                        <div>Keterangan</div>
                                        <div>Nominal</div>
                                        <div>Saldo Akhir</div>
                                        <div>Tanggal</div>
                                    </div>

                                    @forelse($driver->walletTransactions as $trx)

                                        <div class="history-row {{ $trx->amount >= 0 ? 'plus' : 'minus' }}">

                                            <div class="history-type">
                                                @if($trx->type == 'topup')
                                                    💰 Topup
                                                @elseif($trx->type == 'commission')
                                                    📉 Komisi
                                                @elseif($trx->type == 'adjustment')
                                                    🛠️ Withdraw
                                                @else
                                                    📌 {{ ucfirst($trx->type) }}
                                                @endif
                                            </div>

                                            <div class="history-desc">
                                                {{ $trx->description ?? '-' }}
                                            </div>

                                            <div class="history-amount">
                                                {{ $trx->amount >= 0 ? '+' : '-' }}
                                                Rp {{ number_format(abs($trx->amount)) }}
                                            </div>

                                            <div class="history-balance">
                                                Rp {{ number_format($trx->balance_after) }}
                                            </div>

                                            <div class="history-date">
                                                {{ $trx->created_at?->format('d/m/Y H:i') }}
                                            </div>

                                        </div>

                                    @empty

                                        <div class="empty-history">
                                            Belum ada riwayat saldo.
                                        </div>

                                    @endforelse

                                </div>

                            </div>
                        </div>

                        {{-- MODAL TOPUP --}}
                        <div id="topupModal{{ $driver->id }}" class="wallet-modal">
                            <div class="wallet-modal-box">

                                <div class="modal-head">
                                    <div>
                                        <h3>💰 Topup Saldo</h3>
                                        <p>{{ $driver->user->name ?? '-' }}</p>
                                    </div>

                                    <button type="button"
                                            onclick="closeWalletModal('topupModal{{ $driver->id }}')">
                                        ×
                                    </button>
                                </div>

                                <div class="current-balance">
                                    <span>Saldo Sekarang</span>
                                    <b>Rp {{ number_format($driver->balance ?? 0) }}</b>
                                </div>

                                <form method="POST"
                                      action="{{ route('admin.driver-wallet.store', $driver->id) }}">

                                    @csrf

                                    <input type="hidden" name="type" value="topup">
                                    <input type="hidden" name="operation" value="add">

                                    <div class="form-group">
                                        <label>Nominal Topup</label>
                                        <input type="number"
                                               name="amount"
                                               min="1000"
                                               class="form-input"
                                               placeholder="Contoh: 50000"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label>Catatan</label>
                                        <input type="text"
                                               name="description"
                                               class="form-input"
                                               value="Topup saldo manual admin"
                                               placeholder="Contoh: Topup cash via admin">
                                    </div>

                                    <button class="submit-topup"
                                            onclick="return confirm('Yakin tambah saldo driver ini?')">
                                        💾 Simpan Topup
                                    </button>

                                </form>

                            </div>
                        </div>

                        {{-- MODAL WITHDRAW / KURANGI --}}
                        <div id="withdrawModal{{ $driver->id }}" class="wallet-modal">
                            <div class="wallet-modal-box">

                                <div class="modal-head">
                                    <div>
                                        <h3>🏧 Withdraw / Kurangi Saldo</h3>
                                        <p>{{ $driver->user->name ?? '-' }}</p>
                                    </div>

                                    <button type="button"
                                            onclick="closeWalletModal('withdrawModal{{ $driver->id }}')">
                                        ×
                                    </button>
                                </div>

                                <div class="current-balance danger">
                                    <span>Saldo Sekarang</span>
                                    <b>Rp {{ number_format($driver->balance ?? 0) }}</b>
                                </div>

                                <form method="POST"
                                      action="{{ route('admin.driver-wallet.store', $driver->id) }}">

                                    @csrf

                                    <input type="hidden" name="type" value="adjustment">
                                    <input type="hidden" name="operation" value="subtract">

                                    <div class="form-group">
                                        <label>Nominal Withdraw / Pengurangan</label>
                                        <input type="number"
                                               name="amount"
                                               min="1000"
                                               max="{{ $driver->balance ?? 0 }}"
                                               class="form-input"
                                               placeholder="Contoh: 25000"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label>Catatan</label>
                                        <input type="text"
                                               name="description"
                                               class="form-input"
                                               value="Withdraw saldo driver via admin"
                                               placeholder="Contoh: Withdraw cash via admin">
                                    </div>

                                    <button class="submit-withdraw"
                                            onclick="return confirm('Yakin kurangi saldo driver ini?')">
                                        💾 Simpan Withdraw
                                    </button>

                                </form>

                            </div>
                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6">
                        <div class="empty-box">
                            Belum ada driver.
                        </div>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<style>
.wallet-page{
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

.search-card{
    background:white;
    border-radius:22px;
    padding:14px;
    margin-bottom:18px;
    display:flex;
    gap:10px;
    box-shadow:0 10px 24px rgba(15,23,42,.06);
}

.search-input{
    flex:1;
    border:none;
    outline:none;
    background:rgba(15,23,42,.05);
    border-radius:16px;
    padding:14px;
    font-weight:700;
}

.search-btn{
    border:none;
    padding:14px 18px;
    border-radius:16px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
}

.table-card{
    background:white;
    border-radius:26px;
    padding:20px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
    overflow-x:auto;
}

.wallet-table{
    width:100%;
    border-collapse:collapse;
}

.wallet-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    text-align:left;
    padding:14px;
    font-size:13px;
    font-weight:900;
    text-transform:uppercase;
}

.wallet-table td{
    padding:15px 14px;
    border-bottom:1px solid rgba(15,23,42,.06);
    vertical-align:middle;
}

.driver-cell{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:210px;
}

.driver-cell b{
    display:block;
    color:#111827;
    font-weight:900;
}

.driver-cell small{
    color:#6b7280;
    font-size:12px;
}

.avatar{
    width:50px;
    height:50px;
    border-radius:14px;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    flex-shrink:0;
}

.soft-badge,
.plate-badge,
.status-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.soft-badge{
    background:rgba(15,23,42,.05);
    color:var(--primary-color);
}

.plate-badge{
    background:#f8fafc;
    color:#111827;
    border:1px solid #e5e7eb;
}

.status-badge.online{
    background:#dcfce7;
    color:#166534;
}

.status-badge.offline{
    background:#fee2e2;
    color:#991b1b;
}

.status-badge.busy{
    background:#dbeafe;
    color:#1d4ed8;
}

.balance-cell{
    color:var(--primary-color);
    font-size:18px;
    font-weight:900;
    white-space:nowrap;
}

.action-box{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-history,
.btn-topup,
.btn-withdraw{
    border:none;
    display:inline-block;
    padding:10px 13px;
    border-radius:14px;
    text-decoration:none;
    font-weight:900;
    font-size:13px;
    cursor:pointer;
}

.btn-history{
    background:#e0f2fe;
    color:#0369a1;
}

.btn-topup{
    background:#dcfce7;
    color:#166534;
}

.btn-withdraw{
    background:#fee2e2;
    color:#991b1b;
}

.wallet-modal{
    display:none;
    position:fixed;
    inset:0;
    z-index:99999;
    background:rgba(15,23,42,.62);
    padding:24px;
    overflow-y:auto;
}

.wallet-modal-box{
    background:white;
    width:100%;
    max-width:480px;
    margin:70px auto;
    border-radius:26px;
    padding:22px;
    box-shadow:0 24px 60px rgba(15,23,42,.28);
}

.history-box{
    width:95%;
    max-width:920px;
}

.modal-head{
    display:flex;
    justify-content:space-between;
    gap:16px;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding-bottom:14px;
    margin-bottom:18px;
}

.modal-head h3{
    margin:0;
    color:var(--primary-color);
    font-size:23px;
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
    font-size:26px;
    font-weight:900;
    cursor:pointer;
}

.current-balance,
.history-summary{
    background:#fff7ed;
    border-radius:18px;
    padding:14px;
    margin-bottom:16px;
}

.history-summary{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.current-balance span,
.history-summary span{
    display:block;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
}

.current-balance b,
.history-summary b{
    display:block;
    margin-top:5px;
    color:var(--primary-color);
    font-size:24px;
    font-weight:900;
}

.current-balance.danger{
    background:#fff1f2;
}

.current-balance.danger b{
    color:#dc2626;
}

.history-table-wrap{
    max-height:430px;
    overflow:auto;
    border:1px solid #e5e7eb;
    border-radius:18px;
}

.history-row{
    display:grid;
    grid-template-columns:130px minmax(220px,1fr) 140px 140px 140px;
    gap:12px;
    align-items:center;
    padding:13px 14px;
    border-bottom:1px solid #e5e7eb;
    min-width:760px;
}

.history-row:last-child{
    border-bottom:none;
}

.history-head{
    background:#f8fafc;
    color:var(--primary-color);
    font-size:12px;
    font-weight:900;
    text-transform:uppercase;
    position:sticky;
    top:0;
    z-index:2;
}

.history-row.plus{
    background:#f0fdf4;
}

.history-row.minus{
    background:#fef2f2;
}

.history-type{
    font-weight:900;
    color:#111827;
}

.history-desc{
    color:#374151;
    font-size:13px;
    font-weight:700;
}

.history-amount{
    text-align:right;
    font-weight:900;
    color:#111827;
}

.history-row.plus .history-amount{
    color:#16a34a;
}

.history-row.minus .history-amount{
    color:#dc2626;
}

.history-balance{
    text-align:right;
    font-weight:900;
    color:#2563eb;
}

.history-date{
    font-size:12px;
    color:#6b7280;
    font-weight:800;
}

.empty-history{
    padding:20px;
    text-align:center;
    color:#6b7280;
    font-weight:900;
    min-width:760px;
}

.form-group{
    margin-bottom:14px;
}

.form-group label{
    display:block;
    margin-bottom:7px;
    color:var(--primary-color);
    font-weight:900;
}

.form-input{
    width:100%;
    border:none;
    outline:none;
    background:rgba(15,23,42,.05);
    border-radius:16px;
    padding:14px;
    font-weight:700;
}

.submit-topup,
.submit-withdraw{
    width:100%;
    border:none;
    cursor:pointer;
    padding:15px;
    border-radius:18px;
    color:white;
    font-weight:900;
}

.submit-topup{
    background:#16a34a;
}

.submit-withdraw{
    background:#dc2626;
}

.empty-box{
    text-align:center;
    color:var(--primary-color);
    padding:24px;
    font-weight:900;
}

@media(max-width:900px){
    .wallet-table{
        min-width:880px;
    }

    .history-box{
        width:98%;
    }
}

@media(max-width:700px){
    .search-card{
        flex-direction:column;
    }

    .search-btn{
        width:100%;
        text-align:center;
    }
}
</style>

<script>
function filterDrivers(){
    const keyword = document.getElementById('walletSearch').value.toLowerCase();
    const items = document.querySelectorAll('.driver-wallet-item');

    items.forEach(function(item){
        const text = item.dataset.search || '';

        item.style.display = text.includes(keyword)
            ? ''
            : 'none';
    });
}

function openWalletModal(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'block';
    }
}

function closeWalletModal(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'none';
    }
}

document.addEventListener('click', function(e){
    if(e.target.classList.contains('wallet-modal')){
        e.target.style.display = 'none';
    }
});
</script>

@endsection