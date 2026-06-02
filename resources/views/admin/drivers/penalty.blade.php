@extends('layouts.admin')

@section('content')

<div class="penalty-page">

    <div class="page-head">
        <div>
            <h2>⚠️ Driver Penalti</h2>
            <p>Kelola daftar driver yang sedang terkena penalti.</p>
        </div>

        <div class="count-badge">
            {{ $drivers->count() }} Penalti
        </div>
    </div>

    <form method="GET" action="/admin/drivers/penalty" class="search-card">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari nama driver, plat, atau alasan..."
               class="search-input">

        <button class="search-btn">
            🔍 Cari
        </button>

        @if(request('search'))
            <a href="/admin/drivers/penalty" class="reset-btn">
                Reset
            </a>
        @endif
    </form>

    <div class="table-card">
        <table class="penalty-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Plat</th>
                    <th>Sampai</th>
                    <th>Alasan</th>
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
                        <span class="plate-badge">
                            {{ strtoupper($driver->plate_number ?? '-') }}
                        </span>
                    </td>

                    <td>
                        <span class="date-badge">
                            {{ $driver->penalty_until ?? '-' }}
                        </span>
                    </td>

                    <td>
                        <div class="reason-box">
                            {{ $driver->penalty_reason ?? '-' }}
                        </div>
                    </td>

                    <td>
                        <a href="/admin/drivers/{{ $driver->id }}/clear-penalty"
                           class="btn-clear"
                           onclick="return confirm('Hapus penalti driver ini?')">
                            Hapus Penalti
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-box">
                            Tidak ada driver penalti.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
.penalty-page{
    width:100%;
}

.page-head{
    background:white;
    border-radius:26px;
    padding:22px;
    margin-bottom:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
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

.count-badge{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
    white-space:nowrap;
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

.penalty-table{
    width:100%;
    border-collapse:collapse;
}

.penalty-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    text-align:left;
    padding:14px;
    font-size:13px;
    font-weight:900;
    text-transform:uppercase;
}

.penalty-table td{
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

.plate-badge,
.date-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.plate-badge{
    background:#f8fafc;
    color:#111827;
    border:1px solid #e5e7eb;
}

.date-badge{
    background:#fef3c7;
    color:#92400e;
}

.reason-box{
    background:#fff7ed;
    color:#92400e;
    border-radius:14px;
    padding:10px 12px;
    font-weight:800;
    max-width:360px;
}

.btn-clear{
    display:inline-block;
    background:#dcfce7;
    color:#166534;
    text-decoration:none;
    padding:10px 14px;
    border-radius:14px;
    font-weight:900;
    white-space:nowrap;
}

.empty-box{
    text-align:center;
    color:var(--primary-color);
    padding:24px;
    font-weight:900;
}

@media(max-width:900px){
    .penalty-table{
        min-width:760px;
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

@endsection