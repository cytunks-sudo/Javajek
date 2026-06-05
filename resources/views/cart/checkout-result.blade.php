@extends('layouts.customer-page')

@section('content')

<div class="result-wrapper">

    <div class="result-card">
        <h2 class="result-title">✅ Ringkasan Checkout</h2>

        <div class="address-box">
            <span>📍 Alamat Pengiriman</span>
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
                <span>Total Sebelum Voucher</span>
                <b>Rp {{ number_format($summary['merchant_total']) }}</b>
            </div>

        </div>

    @endforeach

    <div class="grand-card">

        <h3 class="payment-title">💳 Rincian Pembayaran</h3>

        <div class="payment-row">
            <span>Total Produk</span>
            <b>Rp {{ number_format($subtotalProduk) }}</b>
        </div>

        <div class="payment-row">
            <span>Total Ongkir</span>
            <b>Rp {{ number_format($totalOngkir) }}</b>
        </div>

        <div class="payment-row subtotal">
            <span>Total Sebelum Voucher</span>
            <b>Rp {{ number_format($grandTotalBefore ?? ($subtotalProduk + $totalOngkir)) }}</b>
        </div>

        <form method="POST" action="{{ route('checkout.calculate') }}" class="voucher-form">
            @csrf

            <input type="hidden" name="latitude" value="{{ $latitude }}">
            <input type="hidden" name="longitude" value="{{ $longitude }}">
            <input type="hidden" name="address" value="{{ $address }}">

            <div class="voucher-title">
                <div>
                    <b>🎁 Voucher</b>
                    <small>Pilih voucher yang tersedia</small>
                </div>

                @if(($voucherDiscount ?? 0) > 0)
                    <span class="voucher-save">
                        Hemat Rp {{ number_format($voucherDiscount) }}
                    </span>
                @endif
            </div>

            <div class="voucher-input-row">
                <select name="voucher_code" class="voucher-select">
                    <option value="">Tidak Pakai Voucher</option>

                    @foreach($availableVouchers ?? [] as $voucher)
                        <option value="{{ $voucher->code }}"
                            {{ ($voucherCode ?? '') == $voucher->code ? 'selected' : '' }}>

                            {{ $voucher->name }}

                            @if($voucher->type == 'fixed')
                                - Potongan Rp {{ number_format($voucher->value) }}
                            @elseif($voucher->type == 'percent')
                                - Diskon {{ number_format($voucher->value) }}%
                            @else
                                - Gratis Ongkir
                            @endif

                            | Min Rp {{ number_format($voucher->minimum_order ?? 0) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">
                    Terapkan
                </button>
            </div>

            @if(!empty($voucherMessage))
                <div class="voucher-message {{ ($voucherDiscount ?? 0) > 0 ? 'success' : 'error' }}">
                    {{ $voucherMessage }}
                </div>
            @endif
        </form>

        @if(($voucherDiscount ?? 0) > 0)
            <div class="payment-row discount-row">
                <span>Voucher {{ $voucherCode }}</span>
                <b>- Rp {{ number_format($voucherDiscount) }}</b>
            </div>
        @endif

        <div class="final-total-box">
            <span>Total Bayar</span>
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
.payment-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:12px 0;
    border-bottom:1px dashed rgba(15,23,42,.08);
}

.summary-row span,
.payment-row span{
    color:#6b7280;
    font-size:14px;
    font-weight:700;
}

.summary-row b,
.payment-row b{
    color:#111827;
    font-weight:900;
}

.summary-row.total{
    border-bottom:none;
}

.summary-row.total span,
.summary-row.total b{
    color:var(--primary);
    font-weight:900;
}

.payment-title{
    margin:0 0 12px;
    color:var(--primary);
    font-size:22px;
    font-weight:900;
}

.payment-row.subtotal span,
.payment-row.subtotal b{
    color:#111827;
    font-weight:900;
}

.discount-row span,
.discount-row b{
    color:#16a34a;
    font-weight:900;
}

.voucher-form{
    margin:16px 0;
    padding:14px;
    background:rgba(15,23,42,.04);
    border-radius:20px;
}

.voucher-title{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
    margin-bottom:10px;
}

.voucher-title b{
    display:block;
    color:var(--primary);
    font-size:15px;
    font-weight:900;
}

.voucher-title small{
    color:#6b7280;
    font-size:12px;
    font-weight:700;
}

.voucher-save{
    background:#dcfce7;
    color:#166534;
    border-radius:999px;
    padding:7px 10px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.voucher-input-row{
    display:flex;
    gap:8px;
}

.voucher-select{
    flex:1;
    min-width:0;
    border:none;
    outline:none;
    background:white;
    border-radius:14px;
    padding:13px;
    font-weight:800;
    color:#111827;
}

.voucher-input-row button{
    border:none;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:0 18px;
    border-radius:14px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
}

.voucher-message{
    margin-top:10px;
    padding:10px;
    border-radius:12px;
    font-size:13px;
    font-weight:800;
}

.voucher-message.success{
    background:#dcfce7;
    color:#166534;
}

.voucher-message.error{
    background:#fee2e2;
    color:#991b1b;
}

.final-total-box{
    margin-top:16px;
    padding:18px;
    border-radius:22px;

    background:linear-gradient(
        135deg,
        #16a34a,
        #22c55e
    );

    color:white;

    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;

    box-shadow:0 12px 24px rgba(34,197,94,.25);
}

.final-total-box span{
    font-size:14px;
    font-weight:900;
    color:white;
}

.final-total-box b{
    font-size:28px;
    font-weight:900;
    color:white;
    white-space:nowrap;
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
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    box-shadow:0 12px 24px rgba(15,23,42,.15);
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

    .voucher-title{
        flex-direction:column;
    }

    .voucher-input-row{
        flex-direction:column;
    }

    .voucher-input-row button{
        padding:13px;
    }

    .final-total-box{
        align-items:flex-start;
        flex-direction:column;
    }

    .final-total-box b{
        font-size:26px;
    }
}
</style>

@endsection