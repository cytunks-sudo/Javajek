@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 class="text-2xl font-bold text-orange-600 mb-4">
        Merchant Menunggu Approval
    </h2>

    <p>
        Pengajuan merchant Anda sudah masuk dan sedang menunggu persetujuan admin.
    </p>

    <br>

    @foreach($restaurants as $restaurant)
        <div style="border:1px solid #fed7aa;border-radius:16px;padding:14px;margin-bottom:12px;">
            <b>{{ $restaurant->name }}</b><br>
            Status: <b>{{ strtoupper($restaurant->status) }}</b>
        </div>
    @endforeach

</div>

@endsection