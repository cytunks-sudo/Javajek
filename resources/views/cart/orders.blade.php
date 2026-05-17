@extends('layouts.customer')

@section('content')

<h2 class="text-2xl font-bold text-orange-600 mb-5">
    Pesanan Saya
</h2>

@foreach($orders as $order)

    <div class="food-card">

        <h3 class="text-xl font-bold">
            Order #{{ $order->id }}
        </h3>

        <p>
            Status:
            <b class="text-orange-600">
                {{ $order->status }}
            </b>
        </p>

        <p>
            Total:
            Rp {{ number_format($order->total) }}
        </p>

    </div>

@endforeach

@endsection