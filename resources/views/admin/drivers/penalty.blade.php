@extends('layouts.admin')

@section('content')

<div class="card-box">
    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Driver Penalti
    </h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Plat</th>
                <th>Sampai</th>
                <th>Alasan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($drivers as $driver)
            <tr>
                <td>{{ $driver->user->name }}</td>
                <td>{{ $driver->plate_number }}</td>
                <td>{{ $driver->penalty_until }}</td>
                <td>{{ $driver->penalty_reason }}</td>
                <td>
                    <a href="/admin/drivers/{{ $driver->id }}/clear-penalty"
                       class="btn-primary">
                        Hapus Penalti
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection