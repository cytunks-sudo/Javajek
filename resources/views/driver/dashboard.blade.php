@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 class="text-2xl font-bold text-orange-600">
        Dashboard Driver
    </h2>

    <p>
        Status:
        <b>{{ strtoupper($driver->status) }}</b>
    </p>

    <p>
        Kendaraan:
        <b>{{ $driver->vehicle_type }}</b>
    </p>

    <p>
        Plat:
        <b>{{ $driver->plate_number }}</b>
    </p>

    <br>

    @if($driver->status == 'offline')

        <a href="/driver/status/online"
           class="btn-order">
            GO ONLINE
        </a>

    @else

        <a href="/driver/status/offline"
           class="btn-order"
           style="background:#dc2626;">
            GO OFFLINE
        </a>

    @endif

</div>

<h3 class="text-xl font-bold text-orange-600 mb-4">
    Order Saya
</h3>

@forelse($orders as $order)

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
            <b>{{ $order->status }}</b>
        </p>

    </div>
@if($order->status != 'completed')

    <br><br>

    <a href="/driver/order/{{ $order->id }}/status/delivery"
       class="btn-order">
        Mulai Antar
    </a>

    <a href="/driver/order/{{ $order->id }}/status/completed"
       class="btn-order"
       style="background:#16a34a;">
        Selesai
    </a>
@endif
@if($order->status == 'pending')

    <a href="/driver/order/{{ $order->id }}/accept" class="btn-order">
        Terima Pesanan
    </a>

    <a href="/driver/order/{{ $order->id }}/reject"
       class="btn-order"
       style="background:#dc2626;">
        Tolak
    </a>

@endif
@empty

    <div class="food-card">
        Belum ada order untuk driver ini.
    </div>

@endforelse


@endsection