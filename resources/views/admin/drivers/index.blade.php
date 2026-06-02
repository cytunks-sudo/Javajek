@extends('layouts.admin')

@section('content')

<div class="driver-page">

    <div class="page-head">
        <div>
            <h2>🛵 Data Driver</h2>
            <p>Kelola driver aktif, penalti, dan pemberhentian driver.</p>
        </div>
    </div>

    <form method="GET" action="/admin/drivers" class="search-card">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari nama driver, kendaraan, atau plat..."
               class="search-input">

        <button class="search-btn">
            🔍 Cari
        </button>

        @if(request('search'))
            <a href="/admin/drivers" class="reset-btn">
                Reset
            </a>
        @endif
    </form>

    <div class="table-card">
        <table class="driver-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Kendaraan</th>
                    <th>Plat</th>
                    <th>Status</th>
                    <th>Penalti</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($drivers as $driver)
                <tr>
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
                        @if($driver->penalty_until)
                            <div class="penalty-box">
                                <b>Sampai:</b> {{ $driver->penalty_until }}<br>
                                <small>{{ $driver->penalty_reason }}</small>
                            </div>
                        @else
                            <span class="no-penalty">Tidak ada</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-box">

                            <a href="/admin/drivers/{{ $driver->id }}/stop"
                               onclick="return confirm('Berhentikan driver ini?')"
                               class="btn-stop">
                                Berhentikan
                            </a>

                            @if($driver->penalty_until)
                                <a href="/admin/drivers/{{ $driver->id }}/clear-penalty"
                                   class="btn-clear">
                                    Hapus Penalti
                                </a>
                            @endif

                            <button type="button"
                                    class="btn-penalty"
                                    onclick="openPenaltyModal('penaltyModal{{ $driver->id }}')">
                                Penalti
                            </button>

                        </div>

                        <div id="penaltyModal{{ $driver->id }}" class="penalty-modal">
                            <div class="penalty-modal-box">
                                <div class="modal-head">
                                    <div>
                                        <h3>⚠️ Beri Penalti</h3>
                                        <p>{{ $driver->user->name ?? '-' }}</p>
                                    </div>

                                    <button type="button"
                                            onclick="closePenaltyModal('penaltyModal{{ $driver->id }}')">
                                        ×
                                    </button>
                                </div>

                                <form method="POST" action="/admin/drivers/{{ $driver->id }}/penalty">
                                    @csrf

                                    <div class="form-group">
                                        <label>Jumlah Hari</label>
                                        <input type="number"
                                               name="days"
                                               min="1"
                                               class="form-input"
                                               placeholder="Contoh: 3"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label>Alasan Penalti</label>
                                        <textarea name="reason"
                                                  class="form-input textarea"
                                                  placeholder="Tuliskan alasan penalti..."
                                                  required></textarea>
                                    </div>

                                    <button class="submit-penalty">
                                        Simpan Penalti
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
                            Driver tidak ditemukan.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
.driver-page{
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

.search-input:focus{
    box-shadow:0 0 0 4px rgba(15,23,42,.06);
}

.search-btn,
.reset-btn{
    border:none;
    text-decoration:none;
    padding:14px 18px;
    border-radius:16px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
}

.search-btn{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
}

.reset-btn{
    background:#fee2e2;
    color:#991b1b;
}

.table-card{
    background:white;
    border-radius:26px;
    padding:20px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
    overflow-x:auto;
}

.driver-table{
    width:100%;
    border-collapse:collapse;
}

.driver-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    text-align:left;
    padding:14px;
    font-size:13px;
    font-weight:900;
    text-transform:uppercase;
}

.driver-table td{
    padding:15px 14px;
    border-bottom:1px solid rgba(15,23,42,.06);
    vertical-align:middle;
}

.driver-cell{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:190px;
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

.penalty-box{
    background:#fff7ed;
    color:#92400e;
    border-radius:14px;
    padding:10px;
    font-size:12px;
    font-weight:800;
}

.penalty-box small{
    color:#78350f;
}

.no-penalty{
    color:#16a34a;
    font-weight:900;
}

.action-box{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-stop,
.btn-clear,
.btn-penalty{
    border:none;
    display:inline-block;
    padding:10px 13px;
    border-radius:14px;
    text-decoration:none;
    font-weight:900;
    font-size:13px;
    cursor:pointer;
}

.btn-stop{
    background:#fee2e2;
    color:#b91c1c;
}

.btn-clear{
    background:#dcfce7;
    color:#166534;
}

.btn-penalty{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
}

.empty-box{
    text-align:center;
    color:var(--primary-color);
    padding:24px;
    font-weight:900;
}

.penalty-modal{
    display:none;
    position:fixed;
    inset:0;
    z-index:99999;
    background:rgba(15,23,42,.62);
    padding:24px;
    overflow-y:auto;
}

.penalty-modal-box{
    background:white;
    width:100%;
    max-width:480px;
    margin:80px auto;
    border-radius:26px;
    padding:22px;
    box-shadow:0 24px 60px rgba(15,23,42,.28);
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

.textarea{
    min-height:110px;
    resize:vertical;
}

.submit-penalty{
    width:100%;
    border:none;
    cursor:pointer;
    padding:15px;
    border-radius:18px;
    color:white;
    font-weight:900;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
}

@media(max-width:900px){
    .driver-table{
        min-width:850px;
    }
}

@media(max-width:700px){
    .search-card{
        flex-direction:column;
    }

    .search-btn,
    .reset-btn{
        width:100%;
        text-align:center;
    }
}
</style>

<script>
function openPenaltyModal(id){
    const modal = document.getElementById(id);
    if(modal){
        modal.style.display = 'block';
    }
}

function closePenaltyModal(id){
    const modal = document.getElementById(id);
    if(modal){
        modal.style.display = 'none';
    }
}

document.addEventListener('click', function(e){
    if(e.target.classList.contains('penalty-modal')){
        e.target.style.display = 'none';
    }
});
</script>

@endsection