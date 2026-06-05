@extends('layouts.customer-page')

@section('content')

<div class="ride-result-page">

    <div class="ride-card">

        <div class="ride-head">
            <h2>🏍️ Ringkasan Ojek</h2>
            <p>Periksa detail perjalanan sebelum memesan ojek.</p>
        </div>

        <div class="info-box">
            <span>📍 Alamat Jemput</span>
            <p>{{ $pickupAddress }}</p>
        </div>

        <div class="info-box">
            <span>🏁 Alamat Tujuan</span>
            <p>{{ $destinationAddress }}</p>
        </div>

        <div class="fare-box">

            <div class="fare-row">
                <span>Jarak Perjalanan</span>
                <b>{{ number_format($distanceKm, 1) }} km</b>
            </div>

            <div class="fare-row">
                <span>Tarif Awal</span>
                <b>Rp {{ number_format($fareBefore ?? $fare) }}</b>
            </div>

            <form method="POST" action="/ojek/calculate" class="voucher-form">
                @csrf

                <input type="hidden" name="pickup_latitude" value="{{ session('ojek_data.pickup_latitude') }}">
                <input type="hidden" name="pickup_longitude" value="{{ session('ojek_data.pickup_longitude') }}">
                <input type="hidden" name="destination_latitude" value="{{ session('ojek_data.destination_latitude') }}">
                <input type="hidden" name="destination_longitude" value="{{ session('ojek_data.destination_longitude') }}">
                <input type="hidden" name="pickup_address" value="{{ $pickupAddress }}">
                <input type="hidden" name="destination_address" value="{{ $destinationAddress }}">

                <label>🎁 Voucher</label>

                <div class="voucher-row">
                    <select name="voucher_code">
                        <option value="">Tidak Pakai Voucher</option>

                        @foreach($availableVouchers ?? [] as $voucher)
                            <option value="{{ $voucher->code }}"
                                {{ ($voucherCode ?? '') == $voucher->code ? 'selected' : '' }}>
                                {{ $voucher->code }} - {{ $voucher->name }}

                                @if($voucher->type == 'fixed')
                                    | Potongan Rp {{ number_format($voucher->value) }}
                                @elseif($voucher->type == 'percent')
                                    | Diskon {{ number_format($voucher->value) }}%
                                @else
                                    | Gratis Ongkir
                                @endif

                                | Min Rp {{ number_format($voucher->minimum_order ?? 0) }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit">Pakai</button>
                </div>

                @if(!empty($voucherMessage))
                    <div class="voucher-message {{ ($voucherDiscount ?? 0) > 0 ? 'success' : 'error' }}">
                        {{ $voucherMessage }}
                    </div>
                @endif
            </form>

            @if(($voucherDiscount ?? 0) > 0)
                <div class="fare-row discount">
                    <span>Voucher {{ $voucherCode }}</span>
                    <b>- Rp {{ number_format($voucherDiscount) }}</b>
                </div>
            @endif

            <div class="final-total">
                <span>Total Bayar</span>
                <b>Rp {{ number_format($fare) }}</b>
            </div>

        </div>

        <form method="POST" action="{{ route('ojek.order') }}">
            @csrf

            <button type="submit" class="order-btn">
                🏍️ Pesan Ojek Sekarang
            </button>
        </form>

        <a href="/ojek" class="back-btn">
            ← Ubah Lokasi
        </a>

    </div>

</div>

<style>
.ride-result-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.ride-card{
    background:white;
    border-radius:28px;
    padding:22px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.ride-head{
    margin-bottom:18px;
}

.ride-head h2{
    margin:0;
    font-size:28px;
    font-weight:900;
    color:var(--primary);
}

.ride-head p{
    margin:7px 0 0;
    color:#6b7280;
    font-size:14px;
}

.info-box{
    background:rgba(15,23,42,.04);
    border-radius:20px;
    padding:16px;
    margin-bottom:14px;
}

.info-box span{
    display:block;
    font-size:12px;
    font-weight:900;
    color:var(--primary);
    margin-bottom:6px;
}

.info-box p{
    margin:0;
    color:#111827;
    font-weight:700;
    line-height:1.6;
}

.fare-box{
    background:white;
    border:2px dashed rgba(15,23,42,.08);
    border-radius:22px;
    padding:16px;
    margin:18px 0;
}

.fare-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px 0;
    border-bottom:1px dashed rgba(15,23,42,.08);
    gap:12px;
}

.fare-row span{
    color:#6b7280;
    font-weight:700;
}

.fare-row b{
    color:#111827;
    font-size:17px;
    font-weight:900;
}

.fare-row.discount span,
.fare-row.discount b{
    color:#16a34a;
}

.voucher-form{
    margin:14px 0;
    padding:14px;
    background:rgba(15,23,42,.04);
    border-radius:18px;
}

.voucher-form label{
    display:block;
    color:var(--primary);
    font-size:13px;
    font-weight:900;
    margin-bottom:8px;
}

.voucher-row{
    display:flex;
    gap:8px;
}

.voucher-row select{
    flex:1;
    min-width:0;
    border:none;
    outline:none;
    background:white;
    border-radius:14px;
    padding:13px;
    font-weight:800;
}

.voucher-row button{
    border:none;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:white;
    padding:0 18px;
    border-radius:14px;
    font-weight:900;
    cursor:pointer;
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

.final-total{
    margin-top:14px;
    padding:18px;
    border-radius:22px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    box-shadow:0 12px 24px rgba(34,197,94,.25);
}

.final-total span{
    color:white;
    font-size:14px;
    font-weight:900;
}

.final-total b{
    color:white;
    font-size:28px;
    font-weight:900;
}

.order-btn{
    width:100%;
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
    .ride-card{
        padding:18px;
        border-radius:24px;
    }

    .ride-head h2{
        font-size:24px;
    }

    .voucher-row{
        flex-direction:column;
    }

    .voucher-row button{
        padding:13px;
    }

    .final-total{
        flex-direction:column;
        align-items:flex-start;
    }

    .final-total b{
        font-size:25px;
    }
}
</style>

@endsection