@extends('layouts.customer-page')

@section('content')

<div class="ojek-result-card">

    <h2>✅ Ringkasan Ojek</h2>

    <div class="info-box">
        <span>Alamat Jemput</span>
        <p>{{ $pickupAddress }}</p>
    </div>

    <div class="info-box">
        <span>Alamat Tujuan</span>
        <p>{{ $destinationAddress }}</p>
    </div>

    <div class="fare-box">
        <div>
            <span>Jarak</span>
            <b>{{ number_format($distanceKm, 1) }} km</b>
        </div>

        <div>
            <span>Tarif</span>
            <b>Rp {{ number_format($fare) }}</b>
        </div>
    </div>

    <form method="POST" action="{{ route('ojek.order') }}">
        @csrf

        <button type="submit" class="order-btn">
            Pesan Ojek Sekarang
        </button>
    </form>

    <a href="/ojek" class="back-btn">
        Ubah Lokasi
    </a>

</div>

<style>
.ojek-result-card{
    background:white;
    border-radius:28px;
    padding:18px;
    border:1px solid #fed7aa;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.ojek-result-card h2{
    margin:0 0 16px;
    color:#9a3412;
    font-size:26px;
    font-weight:900;
}

.info-box{
    background:#fff7ed;
    border-radius:18px;
    padding:14px;
    margin-bottom:14px;
}

.info-box span{
    color:#6b7280;
    font-size:13px;
}

.info-box p{
    margin:6px 0 0;
    font-weight:700;
    line-height:1.5;
}

.fare-box{
    background:white;
    border:1px dashed #fed7aa;
    border-radius:18px;
    padding:14px;
    margin-bottom:16px;
}

.fare-box div{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px dashed #fed7aa;
}

.fare-box div:last-child{
    border-bottom:none;
}

.fare-box span{
    color:#6b7280;
}

.fare-box b{
    color:#ea580c;
    font-size:22px;
    font-weight:900;
}

.order-btn{
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