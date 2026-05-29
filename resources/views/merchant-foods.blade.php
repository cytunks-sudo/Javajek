@extends('layouts.customer-page')

@section('content')

<div class="food-card">

    <h2 class="section-title">
        Menu {{ $merchant->name }}
    </h2>

    <div class="product-grid">

        @forelse($foods as $food)

            <div class="product-card">

                @if($food->photo)
                    <img src="{{ asset('storage/'.$food->photo) }}"
                         class="product-img"
                         alt="{{ $food->name }}">
                @else
                    <div class="product-img product-empty">
                        🍔
                    </div>
                @endif

                <div class="product-body">

                    <h3>
                        {{ $food->name }}
                    </h3>

                    <p class="product-merchant">
                        {{ $merchant->name }}
                    </p>

                    @if($food->description)
                        <p class="product-desc">
                            {{ $food->description }}
                        </p>
                    @endif

                    <div class="product-bottom">

                        <div class="product-price">
                            Rp {{ number_format($food->price) }}
                        </div>

                        <a href="/cart/add/{{ $food->id }}"
                           class="product-cart-btn">
                            + Keranjang
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="empty-product">
                Belum ada menu di merchant ini.
            </div>

        @endforelse

    </div>

</div>

<style>

.section-title{
    font-size:22px;
    font-weight:900;
    color:#9a3412;
    margin-bottom:16px;
}

.product-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.product-card{
    background:#ffffff;
    border:1px solid #fed7aa;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 12px 28px rgba(15,23,42,.10);
    display:flex;
    flex-direction:column;
}

.product-img{
    width:100%;
    height:135px;
    object-fit:cover;
    display:block;
    background:#ffedd5;
}

.product-empty{
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:44px;
}

.product-body{
    padding:13px;
    display:flex;
    flex-direction:column;
    flex:1;
}

.product-body h3{
    font-size:15px;
    font-weight:900;
    color:#9a3412;
    margin:0 0 5px;
    line-height:1.25;
}

.product-merchant{
    font-size:12px;
    color:#6b7280;
    margin:0 0 7px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.product-desc{
    font-size:12px;
    color:#4b5563;
    line-height:1.35;
    margin:0 0 10px;

    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}

.product-bottom{
    margin-top:auto;
}

.product-price{
    color:#ea580c;
    font-size:16px;
    font-weight:900;
    margin-bottom:10px;
}

.product-cart-btn{
    display:block;
    width:100%;
    text-align:center;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:10px;
    border-radius:14px;
    font-size:13px;
    font-weight:900;
    text-decoration:none;
    box-shadow:0 8px 18px rgba(249,115,22,.25);
}

.empty-product{
    grid-column:1 / -1;
    background:#fff7ed;
    color:#9a3412;
    padding:16px;
    border-radius:18px;
    font-weight:900;
}

@media(min-width:768px){

    .product-grid{
        grid-template-columns:repeat(4,1fr);
    }

    .product-img{
        height:160px;
    }

}

</style>

@endsection