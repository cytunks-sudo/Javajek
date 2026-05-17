@extends('layouts.customer')

@section('content')

<h2 class="text-2xl font-bold text-orange-600 mb-5">
    Keranjang Pesanan
</h2>

@php
    $total = 0;
@endphp

@forelse($cart as $item)

    @php
        $subtotal = $item['price'] * $item['qty'];
        $total += $subtotal;
    @endphp

    <div class="food-card">
        <h3 class="text-xl font-bold">{{ $item['name'] }}</h3>

        <p class="text-gray-500">{{ $item['restaurant'] }}</p>

        <p>Qty: {{ $item['qty'] }}</p>

        <b class="text-orange-600">
            Rp {{ number_format($subtotal) }}
        </b>

        <br><br>

        <a href="/cart/remove/{{ $item['id'] }}" class="text-red-600 font-bold">
            Hapus
        </a>
    </div>

@empty

    <div class="food-card">
        Keranjang masih kosong.
    </div>

@endforelse

<div class="food-card">
    <h3 class="text-xl font-bold">
        Total: Rp {{ number_format($total) }}
    </h3>

    <br>

    <a href="/checkout" class="btn-order">
        Checkout
    </a>

    <a href="/cart/clear" class="text-red-600 font-bold ml-4">
        Kosongkan
    </a>
</div>

@endsection