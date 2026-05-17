@extends('layouts.admin')
@section('content')

<div class="card-box">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-2xl font-bold text-orange-600">
                Data Restoran
            </h2>

            <p class="text-gray-500">
                Kelola restoran partner JavaJek
            </p>
        </div>

        <a href="/restaurants/create" class="btn-primary">
            + Tambah Restoran
        </a>
    </div>

    <table>

        <thead>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Status</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @foreach($restaurants as $restaurant)

            <tr>
                <td>
                    <b>{{ $restaurant->name }}</b>
                </td>

                <td>
                    {{ $restaurant->address }}
                </td>

                <td>
                    {{ $restaurant->phone }}
                </td>

                <td>
                    @if($restaurant->status == 'open')
                        <span class="badge-open">OPEN</span>
                    @else
                        <span class="badge-close">CLOSED</span>
                    @endif
                </td>

                <td>
                    <a href="/restaurants/{{ $restaurant->id }}/edit" class="text-blue-600 font-bold">
                        Edit
                    </a>

                    |

                    <a href="/restaurants/{{ $restaurant->id }}/delete"
                       class="text-red-600 font-bold"
                       onclick="return confirm('Yakin hapus restoran ini?')">
                        Hapus
                    </a>
                </td>
            </tr>

        @endforeach

        </tbody>

    </table>

</div>

@endsection