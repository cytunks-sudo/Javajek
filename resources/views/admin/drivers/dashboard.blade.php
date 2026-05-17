@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 class="text-2xl font-bold text-orange-600">
        Dashboard Driver
    </h2>

    <p>
        Status:
        <b>{{ $driver->status }}</b>
    </p>

</div>

@foreach($orders as $order)

@if(session('error'))
    <div class="food-card" style="background:#fee2e2;color:#991b1b;">
        {{ session('error') }}
    </div>
@endif

    <div class="food-card">
        
        <h3 class="text-xl font-bold">
            Order #{{ $order->id }}
        </h3>

        <p>
            Total:
            Rp {{ number_format($order->total) }}
        </p>

        <p>
            Status:
            {{ $order->status }}
        </p>

    </div>

@endforeach

@endsection