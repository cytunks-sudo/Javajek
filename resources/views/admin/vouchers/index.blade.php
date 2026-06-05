@extends('layouts.admin')

@section('content')

<div class="voucher-page">

    @if(session('success'))
        <div class="alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="voucher-head">
        <div>
            <h2>🎁 Voucher Promo</h2>
            <p>Kelola voucher dan promo aplikasi.</p>
        </div>

        <a href="/admin/vouchers/create" class="btn-add">
            ➕ Tambah Voucher
        </a>
    </div>

    <div class="voucher-stat-grid">
        <div class="stat-card">
            <small>Total Voucher</small>
            <h3>{{ number_format($totalVouchers ?? 0) }}</h3>
        </div>

        <div class="stat-card">
            <small>Voucher Aktif</small>
            <h3>{{ number_format($activeVouchers ?? 0) }}</h3>
        </div>

        <div class="stat-card">
            <small>Total Terpakai</small>
            <h3>{{ number_format($usedVouchers ?? 0) }}</h3>
        </div>

        <div class="stat-card">
            <small>User Baru</small>
            <h3>{{ number_format($newUserVouchers ?? 0) }}</h3>
        </div>
    </div>

    <div class="voucher-card">

        <form method="GET" action="/admin/vouchers" class="search-box">
            <input type="text"
                   name="search"
                   value="{{ $search ?? '' }}"
                   placeholder="Cari kode, nama, jenis, atau layanan voucher...">

            <button type="submit">
                🔍 Cari
            </button>

            @if(!empty($search))
                <a href="/admin/vouchers" class="btn-reset">
                    Reset
                </a>
            @endif
        </form>

        <div class="table-wrap">
            <table class="voucher-table">
                <thead>
                    <tr>
                        <th>Voucher</th>
                        <th>Jenis</th>
                        <th>Layanan</th>
                        <th>Nilai</th>
                        <th>Min</th>
                        <th>Kuota</th>
                        <th>Pakai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td>
                                <div class="voucher-info">
                                    @if($voucher->image)
                                        <img src="{{ asset('storage/'.$voucher->image) }}" class="voucher-img">
                                    @else
                                        <div class="voucher-img empty-img">🎁</div>
                                    @endif

                                    <div>
                                        <b class="voucher-code">{{ $voucher->code }}</b>
                                        <div class="voucher-name">{{ $voucher->name }}</div>

                                        @if($voucher->is_new_user_only)
                                            <div class="small-muted">Khusus pengguna baru</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($voucher->type == 'fixed')
                                    <span class="badge orange-light">💰 Nominal</span>
                                @elseif($voucher->type == 'percent')
                                    <span class="badge purple">📊 Persen</span>
                                @else
                                    <span class="badge sky">🚚 Ongkir</span>
                                @endif
                            </td>

                            <td>
                                @if(($voucher->service_type ?? 'all') == 'food')
                                    <span class="badge food">🍔 Food</span>
                                @elseif(($voucher->service_type ?? 'all') == 'ojek')
                                    <span class="badge ojek">🏍️ Ojek</span>
                                @elseif(($voucher->service_type ?? 'all') == 'car')
                                    <span class="badge car">🚗 Car</span>
                                @else
                                    <span class="badge all">✨ Semua</span>
                                @endif
                            </td>

                            <td>
                                @if($voucher->type == 'percent')
                                    {{ number_format($voucher->value) }}%
                                @elseif($voucher->type == 'free_delivery')
                                    Ongkir
                                @else
                                    Rp {{ number_format($voucher->value) }}
                                @endif
                            </td>

                            <td>Rp {{ number_format($voucher->minimum_order ?? 0) }}</td>
                            <td>{{ number_format($voucher->quota ?? 0) }}</td>
                            <td>{{ number_format($voucher->used_count ?? 0) }}</td>

                            <td>
                                @if($voucher->is_active)
                                    <span class="badge green">Aktif</span>
                                @else
                                    <span class="badge red">Off</span>
                                @endif
                            </td>

                            <td>
                                <div class="action-row">
                                    <a href="/admin/vouchers/{{ $voucher->id }}/edit" class="btn-action blue">
                                        Edit
                                    </a>

                                    <a href="/admin/vouchers/{{ $voucher->id }}/toggle" class="btn-action orange">
                                        {{ $voucher->is_active ? 'Off' : 'On' }}
                                    </a>

                                    <a href="/admin/vouchers/{{ $voucher->id }}/delete"
                                       class="btn-action red"
                                       onclick="return confirm('Hapus voucher {{ $voucher->code }}?')">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-box">
                                    Belum ada voucher.
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
.voucher-page{
    width:100%;
    max-width:100%;
    display:flex;
    flex-direction:column;
    gap:16px;
}

.alert-success,
.voucher-head,
.voucher-card,
.stat-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.alert-success{
    color:#166534;
    background:#dcfce7;
    font-weight:900;
}

.voucher-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
}

.voucher-head h2{
    margin:0;
    color:var(--primary, #f97316);
    font-size:26px;
    font-weight:900;
}

.voucher-head p{
    margin:5px 0 0;
    color:#6b7280;
}

.btn-add{
    background:linear-gradient(135deg,var(--primary, #f97316),var(--secondary, #fb923c));
    color:white;
    padding:12px 18px;
    border-radius:14px;
    text-decoration:none;
    font-weight:900;
}

.voucher-stat-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
}

.stat-card small{
    display:block;
    color:#6b7280;
    font-weight:800;
    margin-bottom:8px;
}

.stat-card h3{
    margin:0;
    color:var(--primary, #f97316);
    font-size:26px;
    font-weight:900;
}

.search-box{
    display:flex;
    gap:10px;
    margin-bottom:16px;
}

.search-box input{
    flex:1;
    min-width:0;
    padding:12px 14px;
    border-radius:14px;
    border:1px solid #e5e7eb;
    outline:none;
}

.search-box button,
.btn-reset{
    border:none;
    background:var(--primary, #f97316);
    color:white;
    padding:12px 16px;
    border-radius:14px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
    white-space:nowrap;
}

.btn-reset{
    background:#6b7280;
}

.table-wrap{
    width:100%;
    max-width:100%;
    overflow-x:auto;
}

.voucher-table{
    width:100%;
    border-collapse:collapse;
    min-width:980px;
}

.voucher-table th{
    background:#fff7ed;
    color:var(--primary, #f97316);
    padding:11px 10px;
    text-align:left;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.voucher-table td{
    padding:11px 10px;
    border-bottom:1px solid #f3f4f6;
    vertical-align:middle;
    font-size:12px;
    color:#111827;
}

.voucher-info{
    display:flex;
    align-items:center;
    gap:10px;
    min-width:190px;
}

.voucher-img{
    width:52px;
    height:40px;
    object-fit:cover;
    border-radius:12px;
    flex-shrink:0;
    box-shadow:0 5px 12px rgba(15,23,42,.12);
}

.empty-img{
    background:#f8fafc;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    box-shadow:none;
}

.voucher-code{
    display:block;
    color:var(--primary, #f97316);
    font-size:13px;
    font-weight:900;
    line-height:1.2;
}

.voucher-name{
    color:#111827;
    font-weight:800;
    margin-top:2px;
}

.small-muted{
    color:#6b7280;
    font-size:11px;
    margin-top:3px;
    font-weight:700;
}

.badge{
    display:inline-block;
    padding:6px 9px;
    border-radius:999px;
    font-size:10px;
    font-weight:900;
    margin:2px;
    white-space:nowrap;
}

.green{background:#dcfce7;color:#166534;}
.red{background:#fee2e2;color:#991b1b;}
.blue{background:#dbeafe;color:#1d4ed8;}
.food{background:#ffedd5;color:#9a3412;}
.ojek{background:#dcfce7;color:#166534;}
.car{background:#e5e7eb;color:#374151;}
.all{background:#fef3c7;color:#92400e;}
.sky{background:#e0f2fe;color:#0369a1;}
.purple{background:#ede9fe;color:#6d28d9;}
.orange-light{background:#ffedd5;color:#9a3412;}

.action-row{
    display:flex;
    gap:5px;
    flex-wrap:wrap;
    min-width:100px;
}

.btn-action{
    padding:7px 9px;
    border-radius:10px;
    color:white;
    text-decoration:none;
    font-size:11px;
    font-weight:900;
    white-space:nowrap;
}

.btn-action.blue{background:#0ea5e9;}
.btn-action.orange{background:var(--primary, #f97316);}
.btn-action.red{background:#dc2626;}

.empty-box{
    text-align:center;
    padding:20px;
    color:var(--primary, #f97316);
    font-weight:900;
}

@media(max-width:900px){
    .voucher-stat-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:640px){
    .voucher-stat-grid{
        grid-template-columns:1fr;
    }

    .search-box{
        flex-direction:column;
    }

    .search-box input,
    .search-box button,
    .btn-reset{
        width:100%;
    }

    .voucher-head h2{
        font-size:22px;
    }
}
</style>

@endsection