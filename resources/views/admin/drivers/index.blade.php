@extends('layouts.admin')

@section('content')

<div class="card-box">
    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Data Driver
    </h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kendaraan</th>
                <th>Plat</th>
                <th>Status</th>
                <th>Penalti</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @foreach($drivers as $driver)
            <tr>
                <td>{{ $driver->user->name }}</td>
                <td>{{ $driver->vehicle_type }}</td>
                <td>{{ $driver->plate_number }}</td>
                <td>{{ $driver->status }}</td>
                <td>
                    @if($driver->penalty_until)
                        Sampai: {{ $driver->penalty_until }}<br>
                        {{ $driver->penalty_reason }}
                    @else
                        Tidak ada
                    @endif
                </td>
                <td>
                    <a href="/admin/drivers/{{ $driver->id }}/stop"
                       onclick="return confirm('Berhentikan driver ini?')"
                       style="color:red;font-weight:bold;">
                        Berhentikan
                    </a>

                    <br><br>

                    <form method="POST" action="/admin/drivers/{{ $driver->id }}/penalty">
                        @csrf

                        <input type="number" name="days" placeholder="Hari"
                               style="width:70px;padding:8px;">

                        <input type="text" name="reason" placeholder="Alasan"
                               style="padding:8px;">

                        <button class="btn-primary">
                            Penalti
                        </button>
                    </form>

                    @if($driver->penalty_until)
                        <br>
                        <a href="/admin/drivers/{{ $driver->id }}/clear-penalty"
                           style="color:green;font-weight:bold;">
                            Hapus Penalti
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection