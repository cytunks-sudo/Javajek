@extends('layouts.merchant-page')

@section('content')

<div class="food-card food-page-head">
    <a href="/merchant" class="btn-mini red">
        ← Kembali
    </a>

    <h2>Menu Makanan</h2>

    <a href="/merchant/foods/create" class="btn-mini green">
        + Tambah Menu
    </a>
</div>

@forelse($restaurants as $restaurant)

    <div class="food-card">
        <h3 class="restaurant-title">
            {{ $restaurant->name }}
        </h3>

        <p class="restaurant-subtitle">
            {{ $restaurant->category ?? 'Tanpa kategori' }}
        </p>

        @forelse($restaurant->foods as $food)

            <div class="food-row">
                <div class="food-info">
                    @if($food->photo)
                        <img src="{{ asset('storage/'.$food->photo) }}">
                    @else
                        <div class="food-empty">🍔</div>
                    @endif

                    <div>
                        <b>{{ $food->name }}</b><br>
                        <span>Rp {{ number_format($food->price) }}</span>
                        <small>{{ strtoupper($food->status ?? 'available') }}</small>
                    </div>
                </div>
            </div>

        @empty
            <div class="empty-box">
                Belum ada menu makanan.
            </div>
        @endforelse
    </div>

@empty
    <div class="food-card">
        Anda belum memiliki restoran.
    </div>
@endforelse

<style>
.food-page-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
}

.food-page-head h2{
    margin:0;
    font-size:24px;
    font-weight:900;
}

.btn-mini{
    border:none;
    background:#f97316;
    color:white;
    padding:10px 14px;
    border-radius:14px;
    font-weight:900;
    text-decoration:none;
    cursor:pointer;
    display:inline-block;
}

.btn-mini.green{
    background:#16a34a;
}

.btn-mini.red{
    background:#dc2626;
}

.restaurant-title{
    color:#9a3412;
    font-size:22px;
    font-weight:900;
    margin:0;
}

.restaurant-subtitle{
    color:#6b7280;
    margin:5px 0 16px;
}

.food-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:12px;
    background:#fff7ed;
    border-radius:16px;
    margin-bottom:10px;
}

.food-info{
    display:flex;
    align-items:center;
    gap:12px;
}

.food-info img,
.food-empty{
    width:58px;
    height:58px;
    border-radius:14px;
    object-fit:cover;
    background:#fed7aa;
    display:flex;
    align-items:center;
    justify-content:center;
}

.food-info span{
    color:#ea580c;
    font-weight:900;
}

.food-info small{
    display:block;
    color:#6b7280;
    margin-top:3px;
    font-size:11px;
}

.empty-box{
    background:#fff7ed;
    border-radius:16px;
    padding:14px;
    color:#9a3412;
    font-weight:800;
}
</style>

@endsection