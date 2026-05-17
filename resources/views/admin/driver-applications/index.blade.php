@extends('layouts.admin')

@section('content')

<div class="card-box">
    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Pengajuan Driver
    </h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>HP</th>
                <th>Kendaraan</th>
                <th>Plat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @foreach($applications as $app)
            <tr>
                <td>{{ $app->user->name }}</td>
                <td>{{ $app->phone }}</td>
                <td>{{ $app->vehicle_type }}</td>
                <td>{{ $app->plate_number }}</td>
                <td>{{ $app->status }}</td>
                <td>
                    <a href="/admin/driver-applications/{{ $app->id }}/approve" class="btn-primary">
                        Terima
                    </a>

                    <a href="/admin/driver-applications/{{ $app->id }}/reject" style="color:red;font-weight:bold;">
                        Tolak
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection