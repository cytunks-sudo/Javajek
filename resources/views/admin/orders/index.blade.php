@extends('layouts.admin')

@section('content')

<div class="card-box">

    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Order Masuk
    </h2>

    @foreach($orders as $order)

        <div style="border-bottom:1px solid #eee;padding:18px 0;">

            <h3 style="font-weight:bold;font-size:20px;">
                Order #{{ $order->id }}
            </h3>

            <p>
                Customer:
                <b>{{ $order->user->name }}</b>
            </p>

            <p>
                Status:
                <b style="color:#ff5a00;">
                    {{ $order->status }}
                </b>
            </p>
@php
    $drivers = \App\Models\Driver::where('status', 'online')->get();
@endphp

<form method="POST" action="/admin/orders/{{ $order->id }}/assign-driver">

    @csrf

    <select name="driver_id">

        @foreach($drivers as $driver)

            <option value="{{ $driver->id }}">
                {{ $driver->user->name }}
                - {{ $driver->vehicle_type }}
            </option>

        @endforeach

    </select>

    <button class="btn-primary">
        Assign Driver
    </button>

</form>

<br>
            <p>
                Total:
                <b>Rp {{ number_format($order->total) }}</b>
            </p>

            <p><b>Item:</b></p>

            <ul style="margin-left:20px;">
                @foreach($order->items as $item)
                    <li>
                        {{ $item->food->name }}
                        x {{ $item->qty }}
                    </li>
                @endforeach
            </ul>

            <br>

            <a href="/admin/orders/{{ $order->id }}/status/accepted" class="btn-primary">
                Terima
            </a>

            <a href="/admin/orders/{{ $order->id }}/status/delivery" class="btn-primary">
                Delivery
            </a>

            <a href="/admin/orders/{{ $order->id }}/status/completed" class="btn-primary">
                Selesai
            </a>

            <a href="/admin/orders/{{ $order->id }}/status/cancelled"
               style="color:red;font-weight:bold;margin-left:10px;">
                Batalkan
            </a>

        </div>

    @endforeach

</div>

@endsection