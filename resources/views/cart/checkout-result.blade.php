@extends('layouts.customer-page')

@section('content')

<div class="result-wrapper">

    <div class="result-card">
        <h2 class="result-title">✅ Ringkasan Checkout</h2>

        <div class="address-box">
            <span>Alamat Pengiriman</span>
            <p>{{ $address }}</p>
        </div>
        
    </div>

    @foreach($merchantSummaries as $summary)

        <div class="merchant-result-card">

            <h3>🏪 {{ $summary['restaurant'] }}</h3>

            <div class="summary-row">
                <span>Subtotal Produk</span>
                <b>Rp {{ number_format($summary['subtotal']) }}</b>
            </div>

            <div class="summary-row">
                <span>Jarak Pengiriman</span>
                <b>{{ number_format($summary['distance_km'], 1) }} km</b>
            </div>

            <div class="summary-row">
                <span>Ongkir</span>
                <b>Rp {{ number_format($summary['delivery_fee']) }}</b>
            </div>

            <div class="summary-row total">
                <span>Total Merchant</span>
                <b>Rp {{ number_format($summary['merchant_total']) }}</b>
            </div>

        </div>

    @endforeach

    <div class="grand-card">
        <div>
            <span>Total Produk</span>
            <b>Rp {{ number_format($subtotalProduk) }}</b>
        </div>

        <div>
            <span>Total Ongkir</span>
            <b>Rp {{ number_format($totalOngkir) }}</b>
        </div>

        <div class="grand-total">
            <span>Grand Total</span>
            <b>Rp {{ number_format($grandTotal) }}</b>
        </div>

        <form method="POST" action="{{ route('checkout.order') }}">
            @csrf

            <button type="submit" class="order-btn">
                Buat Pesanan Sekarang
            </button>
        </form>

        <a href="/checkout" class="back-btn">
            Ubah Lokasi
        </a>
    </div>

</div>

<style>
.result-wrapper{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.result-card,
.merchant-result-card,
.grand-card{
    background:white;
    border-radius:28px;
    padding:18px;
    border:1px solid #fed7aa;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.result-title{
    margin:0 0 14px;
    color:#9a3412;
    font-size:26px;
    font-weight:900;
}

.address-box{
    background:#fff7ed;
    border-radius:18px;
    padding:14px;
}

.address-box span{
    color:#6b7280;
    font-size:13px;
}

.address-box p{
    margin:6px 0 0;
    color:#111827;
    font-weight:700;
    line-height:1.5;
}

.merchant-result-card h3{
    margin:0 0 14px;
    color:#9a3412;
    font-size:20px;
    font-weight:900;
}

.summary-row,
.grand-card div{
    display:flex;
    justify-content:space-between;
    gap:12px;
    padding:10px 0;
    border-bottom:1px dashed #fed7aa;
}

.summary-row span,
.grand-card span{
    color:#6b7280;
    font-size:13px;
}

.summary-row b,
.grand-card b{
    color:#9a3412;
    font-weight:900;
}

.summary-row.total,
.grand-total{
    border-bottom:none;
    margin-top:6px;
}

.summary-row.total span,
.grand-total span{
    color:#9a3412;
    font-weight:900;
}

.summary-row.total b,
.grand-total b{
    color:#ea580c;
    font-size:22px;
}

.order-btn{
    margin-top:18px;
    width:100%;
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:16px;
    border-radius:18px;
    font-size:15px;
    font-weight:900;
}

.back-btn{
    display:block;
    margin-top:12px;
    background:#fff7ed;
    color:#9a3412;
    padding:14px;
    border-radius:18px;
    text-align:center;
    text-decoration:none;
    font-weight:900;
}
</style>

@endsection