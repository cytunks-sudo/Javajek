@extends('layouts.customer')

@section('content')

<h2 class="text-2xl font-bold text-orange-600 mb-5">
    Menu Terdekat
</h2>

@foreach($foods as $food)

    <div class="food-card">

        <h3 class="text-xl font-bold">
            {{ $food->name }}
        </h3>

        <p class="text-gray-500">
            {{ $food->restaurant->name }}
        </p>

        <p class="mt-2">
            {{ $food->description }}
        </p>

        <div class="flex justify-between items-center mt-4">

            <b class="text-orange-600">
                Rp {{ number_format($food->price) }}
            </b>

            <a href="/cart/add/{{ $food->id }}" class="btn-order">
    Pesan
</a>

        </div>

    </div>

@endforeach

@endsection