@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 class="text-2xl font-bold text-orange-600">
        Dashboard Merchant
    </h2>

    <p>
        Kelola restoran dan menu Anda.
    </p>

</div>

@forelse($restaurants as $restaurant)

    <div class="food-card">

        <h3 class="text-xl font-bold">
            {{ $restaurant->name }}
        </h3>

        <p>
            {{ $restaurant->address }}
        </p>

        <p>
            Status:
            {{ $restaurant->status }}
        </p>

    </div>

@empty

    <div class="food-card">
        Anda belum memiliki restoran.
    </div>

@endforelse

@endsection