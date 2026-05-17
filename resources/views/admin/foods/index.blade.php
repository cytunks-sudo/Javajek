@extends('layouts.admin')

@section('content')

<div class="card-box">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-orange-600">
                Menu Makanan
            </h2>
            <p class="text-gray-500">
                Kelola menu makanan JavaJek
            </p>
        </div>

        <a href="/foods/create" class="btn-primary">
            + Tambah Menu
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Restoran</th>
                <th>Harga</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
        @foreach($foods as $food)
            <tr>
                <td><b>{{ $food->name }}</b></td>
                <td>{{ $food->restaurant->name }}</td>
                <td>Rp {{ number_format($food->price) }}</td>
                <td>
                    <span class="badge-open">
                        {{ $food->status }}
                    </span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

@endsection