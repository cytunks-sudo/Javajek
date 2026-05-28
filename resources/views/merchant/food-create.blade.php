@extends('layouts.merchant-page')

@section('content')

@php
    $restaurant = $restaurants->first();
@endphp

<div class="food-card">

    <div style="
        background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
        border-radius:24px;
        padding:24px;
        color:white;
        margin-bottom:24px;
    ">

        <h2 style="font-size:30px;font-weight:900;margin:0;">
            Tambah Menu Makanan
        </h2>

        <p style="margin-top:8px;opacity:.95;">
            Tambahkan menu baru untuk restoran Anda.
        </p>

    </div>

    @if($restaurant)

        <div style="
            background:#fff7ed;
            border:1px solid #fed7aa;
            border-radius:20px;
            padding:16px;
            margin-bottom:22px;
            display:flex;
            align-items:center;
            gap:14px;
        ">

            @if($restaurant->photo)
                <img src="{{ asset('storage/'.$restaurant->photo) }}"
                     style="
                        width:72px;
                        height:72px;
                        border-radius:18px;
                        object-fit:cover;
                     ">
            @else
                <div style="
                    width:72px;
                    height:72px;
                    border-radius:18px;
                    background:#fed7aa;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:30px;
                ">
                    🏪
                </div>
            @endif

            <div>
                <h3 style="font-size:22px;font-weight:900;color:#9a3412;margin:0;">
                    {{ $restaurant->name }}
                </h3>

                <p style="margin:4px 0;color:#6b7280;">
                    {{ $restaurant->category ?? 'Restoran' }}
                </p>

                <span style="
                    background:#dcfce7;
                    color:#166534;
                    padding:5px 10px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:900;
                ">
                    ACTIVE
                </span>
            </div>

        </div>

    @endif

    <form method="POST"
          action="/merchant/foods/store"
          enctype="multipart/form-data">

        @csrf

        <input type="hidden"
               name="restaurant_id"
               value="{{ $restaurant->id ?? '' }}">

        <div style="margin-bottom:18px;">

            <label style="font-weight:900;color:#1f2937;">
                Nama Menu
            </label>

            <input type="text"
                   name="name"
                   class="form-control"
                   placeholder="Contoh: Ayam Geprek"
                   required>

        </div>

        <div style="margin-bottom:18px;">

            <label style="font-weight:900;color:#1f2937;">
                Harga
            </label>

            <input type="number"
                   name="price"
                   class="form-control"
                   placeholder="Contoh: 15000"
                   required>

        </div>

        <div style="margin-bottom:18px;">

            <label style="font-weight:900;color:#1f2937;">
                Deskripsi Menu
            </label>

            <textarea name="description"
                      class="form-control"
                      rows="4"
                      placeholder="Deskripsi makanan..."></textarea>

        </div>

        <div style="margin-bottom:24px;">

            <label style="font-weight:900;color:#1f2937;">
                Foto Menu
            </label>

            <input type="file"
                   name="photo"
                   class="form-control"
                   accept="image/*">

        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">

            <button type="submit"
                    class="btn-order">
                Simpan Menu
            </button>

            <a href="/merchant"
               class="btn-order"
               style="background:#6b7280;">
                Kembali
            </a>

        </div>

    </form>

</div>

@endsection