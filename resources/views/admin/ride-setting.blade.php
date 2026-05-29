@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-header">
        <h3>Setting Tarif Ojek</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/admin/ride-setting">
            @csrf

            <div class="form-group">
                <label>Biaya Awal</label>
                <input type="number" name="base_fee" class="form-control"
                       value="{{ old('base_fee', $setting->base_fee ?? 5000) }}">
            </div>

            <div class="form-group">
                <label>Biaya Per KM</label>
                <input type="number" name="per_km_fee" class="form-control"
                       value="{{ old('per_km_fee', $setting->per_km_fee ?? 2500) }}">
            </div>

            <div class="form-group">
                <label>Minimal Tarif</label>
                <input type="number" name="minimum_fee" class="form-control"
                       value="{{ old('minimum_fee', $setting->minimum_fee ?? 8000) }}">
            </div>

            <button class="btn btn-primary">
                Simpan Setting
            </button>
        </form>
    </div>
</div>

@endsection