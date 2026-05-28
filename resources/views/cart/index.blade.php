@extends('layouts.customer-page')

@section('content')

<div class="cart-header">
    <h2>🛒 Keranjang Pesanan</h2>
    <p>Produk dikelompokkan berdasarkan merchant.</p>
</div>

@php
    $groupedCart = collect($cart)->groupBy('restaurant');
    $grandTotal = 0;

    $customerLat = auth()->user()->latitude;
    $customerLng = auth()->user()->longitude;

    $setting = \App\Models\DeliverySetting::first();

    $baseFee = $setting->base_fee ?? 3000;
    $perKmFee = $setting->per_km_fee ?? 2000;
    $minimumFee = $setting->minimum_fee ?? 5000;
@endphp

@forelse($groupedCart as $restaurant => $items)

    @php
        $merchantTotal = collect($items)->sum(function($item){
            return $item['price'] * $item['qty'];
        });

        $firstItem = collect($items)->first();

        $distanceKm = 0;

        if (
            !empty($customerLat) &&
            !empty($customerLng) &&
            !empty($firstItem['restaurant_latitude']) &&
            !empty($firstItem['restaurant_longitude'])
        ) {
            $theta = $customerLng - $firstItem['restaurant_longitude'];

            $distanceKm = (
                acos(
                    sin(deg2rad($customerLat)) *
                    sin(deg2rad($firstItem['restaurant_latitude'])) +

                    cos(deg2rad($customerLat)) *
                    cos(deg2rad($firstItem['restaurant_latitude'])) *
                    cos(deg2rad($theta))
                )
                * 180 / pi()
            ) * 60 * 1.1515 * 1.609344;
        }

        $deliveryFee = $baseFee + round($distanceKm * $perKmFee);

        $deliveryFee = max($minimumFee, $deliveryFee);

        $merchantGrandTotal = $merchantTotal + $deliveryFee;

        $grandTotal += $merchantGrandTotal;
    @endphp

    <div class="merchant-cart-card">

        <div class="merchant-head">
            <div>
                <div class="merchant-title">
                    🏪 {{ $restaurant }}
                </div>

                <div class="merchant-sub">
                    {{ count($items) }} Produk
                </div>
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
                    <h3>{{ $item['name'] }}</h3>

                    <div class="qty-control">
                        <a href="/cart/decrease/{{ $item['id'] }}" class="qty-btn">
                            −
                        </a>

                        <span>{{ $item['qty'] }}</span>

                        <a href="/cart/increase/{{ $item['id'] }}" class="qty-btn">
                            +
                        </a>
                    </div>

                    <div class="cart-bottom">
                        <div class="cart-price">
                            Rp {{ number_format($subtotal) }}
                        </div>

                        <a href="/cart/remove/{{ $item['id'] }}"
                           class="remove-btn">
                            Hapus
                        </a>
                    </div>
                </div>

            </div>

        @endforeach

        <div class="merchant-summary-box">

            <div>
                <span>Subtotal Produk</span>
                <b>Rp {{ number_format($merchantTotal) }}</b>
            </div>

            <div>
                <span>Jarak Merchant</span>
                <b>{{ number_format($distanceKm, 1) }} km</b>
            </div>

            <div>
                <span>Ongkir</span>
                <b>Rp {{ number_format($deliveryFee) }}</b>
            </div>

            <div class="merchant-grand">
                <span>Total Merchant</span>
                <b>Rp {{ number_format($merchantGrandTotal) }}</b>
            </div>

        </div>

    </div>

@empty

    <div class="food-card empty-cart">
        Keranjang masih kosong.
    </div>

@endforelse

@if(count($cart) > 0)

<div class="checkout-card">
    <div class="checkout-total">
        <span>Grand Total Semua Merchant</span>
        <h2>Rp {{ number_format($grandTotal) }}</h2>
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

<style>
.cart-header{
    margin-bottom:18px;
}

.cart-header h2{
    margin:0;
    font-size:28px;
    font-weight:900;
    color:#9a3412;
}

.cart-header p{
    margin-top:6px;
    color:#6b7280;
}

.merchant-cart-card{
    background:white;
    border-radius:28px;
    padding:16px;
    margin-bottom:18px;
    border:1px solid #fed7aa;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.merchant-head{
    padding-bottom:14px;
    margin-bottom:4px;
    border-bottom:2px dashed #fed7aa;
}

.merchant-title{
    font-size:20px;
    font-weight:900;
    color:#9a3412;
}

.merchant-sub{
    margin-top:4px;
    color:#6b7280;
    font-size:12px;
}

.cart-item{
    display:flex;
    gap:14px;
    padding:14px 0;
    border-bottom:1px solid #ffedd5;
}

.cart-img{
    width:85px;
    height:85px;
    border-radius:18px;
    object-fit:cover;
    background:#ffedd5;
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
}

.cart-body h3{
    margin:0;
    font-size:17px;
    font-weight:900;
    color:#111827;
}

.qty-control{
    margin-top:8px;
    display:inline-flex;
    align-items:center;
    gap:10px;
    background:#fff7ed;
    padding:6px;
    border-radius:999px;
    width:max-content;
}

.qty-btn{
    width:28px;
    height:28px;
    border-radius:50%;
    background:#f97316;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-weight:900;
    font-size:18px;
}

.qty-control span{
    min-width:22px;
    text-align:center;
    font-weight:900;
    color:#9a3412;
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
    color:#ea580c;
}

.remove-btn{
    color:#dc2626;
    text-decoration:none;
    font-size:13px;
    font-weight:900;
}

.merchant-summary-box{
    margin-top:14px;
    background:#fff7ed;
    border:1px solid #fed7aa;
    border-radius:22px;
    padding:14px;
    display:flex;
    flex-direction:column;
    gap:10px;
}

.merchant-summary-box div{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.merchant-summary-box span{
    color:#6b7280;
    font-size:13px;
}

.merchant-summary-box b{
    color:#9a3412;
    font-weight:900;
}

.merchant-grand{
    border-top:2px dashed #fed7aa;
    padding-top:12px;
    margin-top:4px;
}

.merchant-grand span{
    color:#9a3412;
    font-weight:900;
}

.merchant-grand b{
    color:#ea580c;
    font-size:20px;
}

.checkout-card{
    position:sticky;
    bottom:18px;
    background:white;
    border-radius:28px;
    padding:18px;
    box-shadow:0 18px 40px rgba(15,23,42,.14);
    border:1px solid #fed7aa;
    margin-top:24px;
}

.checkout-total span{
    color:#6b7280;
    font-size:13px;
}

.checkout-total h2{
    margin:6px 0 14px;
    font-size:32px;
    color:#9a3412;
    font-weight:900;
}

.checkout-actions{
    display:flex;
    gap:10px;
}

.checkout-btn{
    flex:1;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:14px;
    border-radius:18px;
    text-align:center;
    text-decoration:none;
    font-weight:900;
}

.clear-btn{
    background:#fee2e2;
    color:#b91c1c;
    padding:14px 16px;
    border-radius:18px;
    text-decoration:none;
    font-weight:900;
}

.empty-cart{
    text-align:center;
    font-size:16px;
    font-weight:900;
    color:#9a3412;
}

@media(max-width:640px){
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