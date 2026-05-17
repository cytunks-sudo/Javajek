@extends('layouts.admin')

@section('content')

<div class="card-box">

    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Tambah Menu Makanan
    </h2>

    <form method="POST" action="/foods/store">
        @csrf

        <p>Restoran</p>
        <select name="restaurant_id" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;">
            @foreach($restaurants as $restaurant)
                <option value="{{ $restaurant->id }}">
                    {{ $restaurant->name }}
                </option>
            @endforeach
        </select>

        <p class="mt-4">Nama Makanan</p>
        <input type="text" name="name" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;">

        <p class="mt-4">Deskripsi</p>
        <textarea name="description" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;"></textarea>

        <p class="mt-4">Harga</p>
        <input type="number" name="price" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;">

        <br><br>

        <button type="submit" class="btn-primary">
            Simpan
        </button>
    </form>

</div>

@endsection