@extends('layouts.admin')

@section('content')

<div class="card-box">

    <div class="page-header">

        <div>
            <h2>🍔 Menu Makanan</h2>
            <p>Kelola seluruh menu makanan JavaJek Food.</p>
        </div>

        <div class="header-action">
            <form method="GET">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari menu..."
                    class="search-box">
            </form>

            <a href="/foods/create" class="btn-add">
                + Tambah Menu
            </a>
        </div>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Restoran</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($foods as $food)

                <tr>

                    <td>
                        <div class="menu-info">
                            @if($food->photo)
                                <img src="{{ asset('storage/'.$food->photo) }}">
                            @else
                                <div class="menu-placeholder">
                                    🍔
                                </div>
                            @endif

                            <div>
                                <b>{{ $food->name }}</b>

                                @if($food->description)
                                    <small>
                                        {{ Str::limit($food->description,50) }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td>
                        {{ $food->restaurant->name ?? '-' }}
                    </td>

                    <td>
                        <b class="price">
                            Rp {{ number_format($food->price,0,',','.') }}
                        </b>
                    </td>

                    <td>

                        @if($food->status=='available')
                            <span class="badge badge-success">
                                Aktif
                            </span>
                        @else
                            <span class="badge badge-danger">
                                Nonaktif
                            </span>
                        @endif

                    </td>

                    <td>

                        <div class="action-group">

                            <a href="/foods/{{ $food->id }}/edit"
                               class="btn-edit">
                                Edit
                            </a>

                            <a href="/foods/{{ $food->id }}/delete"
                               onclick="return confirm('Hapus menu ini?')"
                               class="btn-delete">
                                Hapus
                            </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5">

                        <div class="empty-box">
                            🍔 Belum ada menu makanan.
                        </div>

                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<style>

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:25px;
    flex-wrap:wrap;
}

.page-header h2{
    margin:0;
    color:#ea580c;
    font-size:30px;
    font-weight:900;
}

.page-header p{
    margin-top:6px;
    color:#6b7280;
}

.header-action{
    display:flex;
    gap:12px;
    align-items:center;
    flex-wrap:wrap;
}

.search-box{
    padding:12px 16px;
    border:1px solid #ddd;
    border-radius:14px;
    min-width:240px;
    outline:none;
}

.btn-add{
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:12px 18px;
    border-radius:14px;
    text-decoration:none;
    font-weight:800;
}

.table-wrapper{
    overflow-x:auto;
}

.custom-table{
    width:100%;
    border-collapse:collapse;
}

.custom-table th{
    background:#fff7ed;
    color:#ea580c;
    padding:16px;
    text-align:left;
    font-size:13px;
    font-weight:900;
    text-transform:uppercase;
}

.custom-table td{
    padding:16px;
    border-bottom:1px solid #f1f1f1;
}

.menu-info{
    display:flex;
    align-items:center;
    gap:12px;
}

.menu-info img{
    width:60px;
    height:60px;
    border-radius:14px;
    object-fit:cover;
}

.menu-placeholder{
    width:60px;
    height:60px;
    border-radius:14px;
    background:#fff7ed;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
}

.menu-info b{
    display:block;
    font-size:15px;
}

.menu-info small{
    display:block;
    margin-top:4px;
    color:#6b7280;
}

.price{
    color:#16a34a;
}

.badge{
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.badge-success{
    background:#dcfce7;
    color:#166534;
}

.badge-danger{
    background:#fee2e2;
    color:#991b1b;
}

.action-group{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-edit{
    background:#2563eb;
    color:white;
    padding:8px 14px;
    border-radius:10px;
    text-decoration:none;
    font-weight:700;
}

.btn-delete{
    background:#ef4444;
    color:white;
    padding:8px 14px;
    border-radius:10px;
    text-decoration:none;
    font-weight:700;
}

.empty-box{
    text-align:center;
    padding:40px;
    color:#9ca3af;
    font-weight:700;
}

@media(max-width:768px){

    .page-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .search-box{
        width:100%;
        min-width:100%;
    }

    .header-action{
        width:100%;
    }

    .btn-add{
        width:100%;
        text-align:center;
    }

}

</style>

@endsection