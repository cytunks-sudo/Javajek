@extends('layouts.customer-page')

@section('content')

<div class="car-result-page">

    <div class="car-result-card">

        <div class="result-header">
            <h2>🚗 Ringkasan J-Car</h2>
            <p>Periksa detail perjalanan sebelum memesan mobil.</p>
        </div>

        <div class="info-card">
            <span>📍 Alamat Jemput</span>
            <p>{{ $pickupAddress }}</p>
        </div>

        <div class="info-card">
            <span>🏁 Alamat Tujuan</span>
            <p>{{ $destinationAddress }}</p>
        </div>

        <div class="fare-card">

            <div class="fare-row">
                <span>Jarak Perjalanan</span>
                <b>{{ number_format($distanceKm,1) }} km</b>
            </div>

            <div class="fare-row total">
                <span>Tarif Mobil</span>
                <b>Rp {{ number_format($fare) }}</b>
            </div>

        </div>

        <form method="POST" action="{{ route('car.order') }}">
            @csrf

            <button type="submit" class="order-btn">
                🚗 Pesan Mobil Sekarang
            </button>
        </form>

        <a href="/car" class="back-btn">
            ← Ubah Lokasi
        </a>

    </div>

</div>

<style>

.car-result-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.car-result-card{
    background:white;
    border-radius:28px;
    padding:22px;
    border:1px solid rgba(15,23,42,.06);
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.result-header{
    margin-bottom:18px;
}

.result-header h2{
    margin:0;
    font-size:28px;
    font-weight:900;
    color:var(--primary);
}

.result-header p{
    margin:7px 0 0;
    color:#6b7280;
    font-size:14px;
}

.info-card{
    background:rgba(15,23,42,.04);
    border-radius:20px;
    padding:16px;
    margin-bottom:14px;
}

.info-card span{
    display:block;
    font-size:12px;
    font-weight:900;
    color:var(--primary);
    margin-bottom:6px;
}

.info-card p{
    margin:0;
    color:#111827;
    font-weight:700;
    line-height:1.6;
}

.fare-card{
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
}

.fare-row:last-child{
    border-bottom:none;
}

.fare-row span{
    color:#6b7280;
    font-weight:700;
}

.fare-row b{
    color:#111827;
    font-size:18px;
    font-weight:900;
}

.fare-row.total b{
    color:var(--primary);
    font-size:26px;
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

@media(max-width:640px){

    .car-result-card{
        padding:18px;
        border-radius:24px;
    }

    .result-header h2{
        font-size:24px;
    }

    .fare-row.total b{
        font-size:22px;
    }

}

</style>

@endsection