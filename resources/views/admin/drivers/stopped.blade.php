@extends('layouts.admin')

@section('content')

<div class="card-box">
    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Driver Diberhentikan
    </h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kendaraan</th>
                <th>Plat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($drivers as $driver)
            <tr>
                <td>{{ $driver->user->name }}</td>
                <td>{{ $driver->vehicle_type }}</td>
                <td>{{ $driver->plate_number }}</td>
                <td>Diberhentikan</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection