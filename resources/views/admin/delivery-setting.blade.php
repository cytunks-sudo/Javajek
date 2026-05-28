@extends('layouts.admin')

@section('content')

<div class="card-box">
    <h2 style="font-size:26px;font-weight:900;color:#ea580c;margin-bottom:8px;">
        Pengaturan Ongkir
    </h2>

    <p style="color:#6b7280;margin-bottom:22px;">
        Atur biaya dasar, tarif per kilometer, ongkir minimal, dan radius maksimal driver.
    </p>

    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:14px;border-radius:16px;margin-bottom:18px;font-weight:900;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/admin/delivery-setting">
        @csrf

        <div class="setting-grid">
            <div class="setting-field">
                <label>Biaya Dasar</label>
                <input type="number" name="base_fee" value="{{ $setting->base_fee }}" required>
                <small>Contoh: 3000</small>
            </div>

            <div class="setting-field">
                <label>Tarif Per KM</label>
                <input type="number" name="per_km_fee" value="{{ $setting->per_km_fee }}" required>
                <small>Contoh: 2000</small>
            </div>

            <div class="setting-field">
                <label>Ongkir Minimal</label>
                <input type="number" name="minimum_fee" value="{{ $setting->minimum_fee }}" required>
                <small>Contoh: 5000</small>
            </div>

            <div class="setting-field">
                <label>Radius Maksimal Driver</label>
                <input type="number" name="max_driver_radius_km" value="{{ $setting->max_driver_radius_km }}" required>
                <small>Contoh: 5 KM</small>
            </div>
        </div>

        <button class="save-btn">
            Simpan Pengaturan
        </button>
    </form>
</div>

<style>
.setting-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
}

.setting-field{
    background:#fff7ed;
    border:1px solid #fed7aa;
    border-radius:20px;
    padding:16px;
}

.setting-field label{
    display:block;
    font-weight:900;
    color:#9a3412;
    margin-bottom:8px;
}

.setting-field input{
    width:100%;
    border:1px solid #fed7aa;
    border-radius:14px;
    padding:13px;
    font-size:15px;
    outline:none;
}

.setting-field small{
    display:block;
    margin-top:6px;
    color:#6b7280;
}

.save-btn{
    margin-top:20px;
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:14px 20px;
    border-radius:16px;
    font-weight:900;
    cursor:pointer;
}

@media(max-width:700px){
    .setting-grid{
        grid-template-columns:1fr;
    }
}
</style>

@endsection