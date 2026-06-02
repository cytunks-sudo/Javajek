@extends('layouts.customer-page')

@section('content')

<div class="cart-page">

    <div class="cart-header">
        <div>
            <h2>🛒 Keranjang Pesanan</h2>
            <p>Ongkir akan dihitung setelah pilih lokasi pengiriman.</p>
        </div>
    </div>

    @php
        $groupedCart = collect($cart)->groupBy('restaurant');
        $grandTotal = 0;
    @endphp

    @forelse($groupedCart as $restaurant => $items)

        @php
            $merchantTotal = collect($items)->sum(function($item){
                return $item['price'] * $item['qty'];
            });

            $grandTotal += $merchantTotal;
        @endphp

        <div class="merchant-cart-card">

            <div class="merchant-head">
                <div>
                    <h3>🏪 {{ $restaurant }}</h3>
                    <p>{{ count($items) }} Produk</p>
                </div>

                <div class="merchant-total">
                    Rp {{ number_format($merchantTotal) }}
                </div>
            </div>

            @foreach($items as $item)

                @php
                    $subtotal = $item['price'] * $item['qty'];
                @endphp

                <div class="cart-item">

                    <div class="cart-left">
                        @if(!empty($item['photo']))
                            <img src="{{ asset('storage/'.$item['photo']) }}" class="cart-img">
                        @else
                            <div class="cart-img empty">🍔</div>
                        @endif
                    </div>

                    <div class="cart-body">
                        <h4>{{ $item['name'] }}</h4>

                        <div class="qty-control">
                            <a href="/cart/decrease/{{ $item['id'] }}" class="qty-btn">−</a>
                            <span>{{ $item['qty'] }}</span>
                            <a href="/cart/increase/{{ $item['id'] }}" class="qty-btn">+</a>
                        </div>

                        <div class="cart-bottom">
                            <div class="cart-price">
                                Rp {{ number_format($subtotal) }}
                            </div>

                            <a href="/cart/remove/{{ $item['id'] }}"
                               class="remove-btn"
                               onclick="return confirm('Hapus item ini dari keranjang?')">
                                Hapus
                            </a>
                        </div>
                    </div>

                </div>

            @endforeach

        </div>

    @empty

        <div class="empty-cart-card">
            <div class="empty-icon">🛒</div>
            <h3>Keranjang masih kosong</h3>
            <p>Tambahkan makanan favoritmu terlebih dahulu.</p>
            <a href="/" class="shop-btn">Cari Makanan</a>
        </div>

    @endforelse

    @if(count($cart) > 0)

        <div class="checkout-card">
            <div class="checkout-total">
                <span>Total Produk</span>
                <h2>Rp {{ number_format($grandTotal) }}</h2>
                <p>Ongkir dihitung setelah pilih alamat pengiriman.</p>
            </div>

            <div class="checkout-actions">
                <a href="/checkout" class="checkout-btn">
                    Checkout Sekarang
                </a>

                <a href="/cart/clear"
                   class="clear-btn"
                   onclick="return confirm('Kosongkan keranjang?')">
                    Kosongkan
                </a>
            </div>
        </div>

    @endif

</div>

<style>
.cart-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.cart-header{
    background:white;
    border-radius:28px;
    padding:20px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.cart-header h2{
    margin:0;
    font-size:28px;
    font-weight:900;
    color:var(--primary);
}

.cart-header p{
    margin:7px 0 0;
    color:#6b7280;
    font-size:14px;
}

.merchant-cart-card{
    background:white;
    border-radius:28px;
    padding:18px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.merchant-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:14px;
    padding-bottom:14px;
    border-bottom:1px dashed rgba(15,23,42,.12);
}

.merchant-head h3{
    margin:0;
    font-size:20px;
    font-weight:900;
    color:var(--primary);
}

.merchant-head p{
    margin:5px 0 0;
    color:#6b7280;
    font-size:13px;
}

.merchant-total{
    color:var(--primary);
    background:rgba(15,23,42,.04);
    padding:9px 12px;
    border-radius:14px;
    font-weight:900;
    white-space:nowrap;
}

.cart-item{
    display:flex;
    gap:14px;
    padding:16px 0;
    border-bottom:1px solid rgba(15,23,42,.06);
}

.cart-item:last-child{
    border-bottom:none;
    padding-bottom:0;
}

.cart-img{
    width:86px;
    height:86px;
    border-radius:20px;
    object-fit:cover;
    background:rgba(15,23,42,.05);
}

.cart-img.empty{
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:36px;
}

.cart-body{
    flex:1;
    display:flex;
    flex-direction:column;
    min-width:0;
}

.cart-body h4{
    margin:0;
    color:#111827;
    font-size:17px;
    font-weight:900;
    line-height:1.3;
}

.qty-control{
    margin-top:9px;
    display:inline-flex;
    align-items:center;
    gap:10px;
    background:rgba(15,23,42,.04);
    padding:6px;
    border-radius:999px;
    width:max-content;
}

.qty-btn{
    width:30px;
    height:30px;
    border-radius:50%;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-weight:900;
    font-size:18px;
}

.qty-control span{
    min-width:24px;
    text-align:center;
    font-weight:900;
    color:var(--primary);
}

.cart-bottom{
    margin-top:auto;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    padding-top:12px;
}

.cart-price{
    font-size:18px;
    font-weight:900;
    color:var(--primary);
}

.remove-btn{
    background:#fee2e2;
    color:#b91c1c;
    text-decoration:none;
    font-size:13px;
    font-weight:900;
    padding:8px 11px;
    border-radius:12px;
}

.checkout-card{
    position:sticky;
    bottom:18px;
    background:white;
    border-radius:28px;
    padding:18px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 18px 42px rgba(15,23,42,.18);
    z-index:20;
}

.checkout-total span{
    color:#6b7280;
    font-size:13px;
    font-weight:900;
}

.checkout-total h2{
    margin:7px 0 6px;
    font-size:34px;
    color:var(--primary);
    font-weight:900;
}

.checkout-total p{
    margin:0 0 15px;
    color:#6b7280;
    font-size:13px;
}

.checkout-actions{
    display:flex;
    gap:10px;
}

.checkout-btn{
    flex:1;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:15px;
    border-radius:18px;
    text-align:center;
    text-decoration:none;
    font-weight:900;
    box-shadow:0 12px 24px rgba(15,23,42,.14);
}

.clear-btn{
    background:#fee2e2;
    color:#b91c1c;
    padding:15px 16px;
    border-radius:18px;
    text-decoration:none;
    font-weight:900;
}

.empty-cart-card{
    background:white;
    border-radius:28px;
    padding:30px 20px;
    text-align:center;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.empty-icon{
    font-size:48px;
    margin-bottom:10px;
}

.empty-cart-card h3{
    margin:0;
    color:var(--primary);
    font-size:22px;
    font-weight:900;
}

.empty-cart-card p{
    margin:8px 0 18px;
    color:#6b7280;
}

.shop-btn{
    display:inline-block;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:13px 18px;
    border-radius:16px;
    text-decoration:none;
    font-weight:900;
}

@media(max-width:640px){
    .cart-header,
    .merchant-cart-card,
    .checkout-card,
    .empty-cart-card{
        border-radius:24px;
        padding:16px;
    }

    .merchant-head{
        flex-direction:column;
    }

    .merchant-total{
        width:100%;
        text-align:center;
    }

    .cart-img{
        width:78px;
        height:78px;
    }

    .checkout-actions{
        flex-direction:column;
    }

    .checkout-btn,
    .clear-btn{
        width:100%;
    }
}
</style>

@endsection