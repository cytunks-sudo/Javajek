@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 class="text-2xl font-bold text-orange-600">
        Dashboard Driver
    </h2>

    <p>Status: <b>{{ strtoupper($driver->status) }}</b></p>
    <p>Kendaraan: <b>{{ $driver->vehicle_type }}</b></p>
    <p>Plat: <b>{{ $driver->plate_number }}</b></p>

    <br>

    @if($driver->status == 'offline')
        <a href="/driver/status/online" class="btn-order">
            GO ONLINE
        </a>
    @else
        <a href="/driver/status/offline" class="btn-order" style="background:#dc2626;">
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

        <p>Total: Rp {{ number_format($order->total) }}</p>

        <p>
            Status Order:
            <b>{{ $order->status }}</b>
        </p>

        <p>
            Status Merchant:
            <b>{{ $order->merchant_status }}</b>
        </p>

        <p>
            Status Driver:
            <b>{{ $order->driver_status }}</b>
        </p>

        <br>

        {{-- DRIVER BELUM RESPON --}}
        @if($order->driver_status == 'pending')

            <a href="/driver/order/{{ $order->id }}/accept" class="btn-order">
                Terima Pesanan
            </a>

            <a href="/driver/order/{{ $order->id }}/reject"
               class="btn-order"
               style="background:#dc2626;">
                Tolak
            </a>

        {{-- DRIVER SUDAH ACCEPT, TUNGGU MERCHANT --}}
        @elseif($order->driver_status == 'accepted' && $order->merchant_status == 'pending')

            <div style="color:#f97316;font-weight:bold;">
                Menunggu merchant menerima pesanan.
            </div>

        {{-- DRIVER MENUJU MERCHANT --}}
        @elseif($order->status == 'driver_to_merchant')

            <a href="/driver/order/{{ $order->id }}/status/dalam_pengiriman"
               class="btn-order">
                Pesanan Sudah Diambil
            </a>

        {{-- DRIVER ANTAR KE CUSTOMER --}}
        @elseif($order->status == 'dalam_pengiriman')

            <a href="/driver/order/{{ $order->id }}/status/completed"
               class="btn-order"
               style="background:#16a34a;">
                Selesaikan Pesanan
            </a>

        {{-- SELESAI --}}
        @elseif($order->status == 'completed')

            <div style="color:#16a34a;font-weight:bold;">
                Pesanan selesai.
            </div>

        @endif

    </div>

@empty

    <div class="food-card">
        Belum ada order untuk driver ini.
    </div>

@endforelse

@endsection