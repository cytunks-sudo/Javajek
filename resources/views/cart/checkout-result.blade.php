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
    padding:20px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.result-title{
    margin:0 0 16px;
    font-size:28px;
    font-weight:900;
    color:var(--primary);
}

.address-box{
    background:rgba(15,23,42,.04);
    border-radius:20px;
    padding:16px;
}

.address-box span{
    display:block;
    color:var(--primary);
    font-size:12px;
    font-weight:900;
    margin-bottom:6px;
}

.address-box p{
    margin:0;
    color:#111827;
    font-weight:700;
    line-height:1.6;
}

.merchant-result-card h3{
    margin:0 0 16px;
    color:var(--primary);
    font-size:21px;
    font-weight:900;
}

.summary-row,
.grand-card > div{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:12px 0;
    border-bottom:1px dashed rgba(15,23,42,.08);
}

.summary-row span,
.grand-card span{
    color:#6b7280;
    font-size:14px;
    font-weight:700;
}

.summary-row b,
.grand-card b{
    color:#111827;
    font-weight:900;
}

.summary-row.total,
.grand-total{
    border-bottom:none !important;
}

.summary-row.total span,
.grand-total span{
    color:var(--primary);
    font-weight:900;
}

.summary-row.total b,
.grand-total b{
    color:var(--primary);
    font-size:24px;
    font-weight:900;
}

.order-btn{
    width:100%;
    margin-top:18px;
    border:none;
    cursor:pointer;

    padding:17px;
    border-radius:20px;

    color:white;
    font-size:15px;
    font-weight:900;

    background:linear-gradient(
        135deg,
        var(--primary),
        var(--secondary)
    );

    box-shadow:0 12px 24px rgba(15,23,42,.15);
}

.order-btn:hover{
    transform:translateY(-1px);
}

.back-btn{
    display:block;
    margin-top:12px;

    text-align:center;
    text-decoration:none;

    padding:15px;
    border-radius:18px;

    background:rgba(15,23,42,.05);
    color:var(--primary);
    font-weight:900;
}

.back-btn:hover{
    background:rgba(15,23,42,.08);
}

@media(max-width:640px){

    .result-card,
    .merchant-result-card,
    .grand-card{
        padding:16px;
        border-radius:24px;
    }

    .result-title{
        font-size:24px;
    }

    .summary-row.total b,
    .grand-total b{
        font-size:22px;
    }

}

</style>

@endsection