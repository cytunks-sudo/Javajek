@extends('layouts.admin')

@section('content')

<div class="stopped-page">

    <div class="page-head">
        <div>
            <h2>⛔ Driver Diberhentikan</h2>
            <p>Daftar driver yang sudah dinonaktifkan dari sistem JavaJek.</p>
        </div>

        <div class="count-badge">
            {{ $drivers->count() }} Driver
        </div>
    </div>

    <form method="GET" action="/admin/drivers/stopped" class="search-card">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari nama driver, kendaraan, atau plat..."
               class="search-input">

        <button class="search-btn">
            🔍 Cari
        </button>

        @if(request('search'))
            <a href="/admin/drivers/stopped" class="reset-btn">
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
                    <th>Plat Nomor</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($drivers as $driver)

                <tr>

                    <td>
                        <div class="driver-cell">

                            <div class="avatar">
                                {{ strtoupper(substr($driver->user->name ?? 'D',0,1)) }}
                            </div>

                            <div>
                                <b>{{ $driver->user->name ?? '-' }}</b>
                                <small>{{ $driver->user->email ?? '-' }}</small>
                            </div>

                        </div>
                    </td>

                    <td>
                        <span class="vehicle-badge">
                            {{ $driver->vehicle_type == 'mobil' ? '🚗 Mobil' : '🛵 Motor' }}
                        </span>
                    </td>

                    <td>
                        <span class="plate-badge">
                            {{ strtoupper($driver->plate_number ?? '-') }}
                        </span>
                    </td>

                    <td>
                        <span class="stopped-badge">
                            DIBERHENTIKAN
                        </span>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4">
                        <div class="empty-box">
                            Tidak ada driver yang diberhentikan.
                        </div>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<style>

.stopped-page{
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
    background:linear-gradient(
        135deg,
        var(--primary-color),
        var(--secondary-color)
    );

    color:white;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
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
}

.search-btn{
    background:linear-gradient(
        135deg,
        var(--primary-color),
        var(--secondary-color)
    );

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
}

.avatar{
    width:50px;
    height:50px;

    border-radius:14px;

    background:linear-gradient(
        135deg,
        var(--primary-color),
        var(--secondary-color)
    );

    color:white;

    display:flex;
    align-items:center;
    justify-content:center;

    font-weight:900;
}

.driver-cell b{
    display:block;
    color:#111827;
}

.driver-cell small{
    color:#6b7280;
}

.vehicle-badge,
.plate-badge,
.stopped-badge{
    display:inline-block;

    padding:8px 12px;
    border-radius:999px;

    font-size:12px;
    font-weight:900;
}

.vehicle-badge{
    background:rgba(15,23,42,.05);
    color:var(--primary-color);
}

.plate-badge{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    color:#111827;
}

.stopped-badge{
    background:#fee2e2;
    color:#991b1b;
}

.empty-box{
    text-align:center;
    padding:24px;
    color:var(--primary-color);
    font-weight:900;
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