@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 class="text-2xl font-bold text-red-600">
        Pengajuan Ditolak
    </h2>

    <p>
        Pengajuan driver Anda ditolak admin.
    </p>

    <p>
        Silakan hubungi admin atau daftar ulang.
    </p>

    <br>

    <a href="/apply-driver"
       class="btn-order">
        Daftar Ulang
    </a>

</div>

@endsection