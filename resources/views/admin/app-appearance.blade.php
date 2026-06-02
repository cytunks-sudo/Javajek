@extends('layouts.admin')

@section('content')

<div class="appearance-page">

    <div class="page-head">
        <div>
            <h2>🎨 Tampilan Aplikasi</h2>
            <p>Atur identitas visual, radius, logo, icon, banner, dan mode aplikasi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="toast-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="/admin/app-appearance"
          enctype="multipart/form-data">

        @csrf

        <div class="section-card">
            <div class="section-title">
                <h3>⚙️ Pengaturan Utama</h3>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Aplikasi</label>
                    <input type="text"
                           name="app_name"
                           value="{{ old('app_name', $setting->app_name) }}">
                </div>

                <div class="form-group">
                    <label>Warna Utama</label>
                    <div class="color-row">
                        <input type="color"
                               name="primary_color"
                               value="{{ old('primary_color', $setting->primary_color ?? '#f97316') }}">

                        <span style="background:{{ $setting->primary_color ?? '#f97316' }}">
                            {{ $setting->primary_color ?? '#f97316' }}
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Warna Kedua</label>
                    <div class="color-row">
                        <input type="color"
                               name="secondary_color"
                               value="{{ old('secondary_color', $setting->secondary_color ?? '#fb923c') }}">

                        <span style="background:{{ $setting->secondary_color ?? '#fb923c' }}">
                            {{ $setting->secondary_color ?? '#fb923c' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <h3>📍 Radius</h3>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Driver di Peta Customer</label>
                    <input type="number"
                           min="1"
                           name="customer_driver_radius"
                           value="{{ $setting->customer_driver_radius ?? 5 }}">
                    <small>Dalam kilometer</small>
                </div>

                <div class="form-group">
                    <label>Cari Driver Ride/Car</label>
                    <input type="number"
                           min="1"
                           name="ride_search_radius"
                           value="{{ $setting->ride_search_radius ?? 10 }}">
                    <small>Dalam kilometer</small>
                </div>

                <div class="form-group">
                    <label>Merchant Terdekat</label>
                    <input type="number"
                           min="1"
                           name="merchant_radius"
                           value="{{ $setting->merchant_radius ?? 20 }}">
                    <small>Dalam kilometer</small>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">
                <h3>🖼️ Logo & Gambar</h3>
            </div>

            <div class="upload-grid">

                @foreach([
    'login_logo' => ['Logo Login', 'Muncul di halaman login aplikasi.'],
    'customer_logo' => ['Logo Customer', 'Muncul di halaman customer.'],
    'driver_logo' => ['Logo Driver', 'Muncul di halaman driver.'],
    'merchant_logo' => ['Logo Merchant', 'Muncul di halaman merchant.'],
    'driver_map_icon' => ['Icon Driver Map', 'Icon kendaraan pada peta tracking.'],
    'home_banner' => ['Banner Home', 'Banner utama di halaman depan customer.']
] as $field => $info)

@php
    $label = $info[0];
    $desc = $info[1];
@endphp

                    <div class="upload-card">
    <div class="preview-box">
        @if(!empty($setting->$field))
            <img src="{{ asset('storage/'.$setting->$field) }}" alt="{{ $label }}">
        @else
            <div class="empty-preview">No Image</div>
        @endif
    </div>

    <div class="upload-row">
        <strong>{{ $label }}</strong>

        <label class="upload-btn">
            Ganti
            <input type="file"
                   name="{{ $field }}"
                   onchange="previewImage(event, 'preview-{{ $field }}')">
        </label>
    </div>

    <p class="upload-desc">
        {{ $desc }}
    </p>

    <div id="preview-{{ $field }}" class="new-preview"></div>
</div>

                @endforeach

            </div>
        </div>

        <div class="section-card maintenance-card">
            <label class="maintenance-toggle">
                <input type="checkbox"
                       name="maintenance_mode"
                       {{ $setting->maintenance_mode ? 'checked' : '' }}>

                <span></span>

                <div>
                    <b>Maintenance Mode</b>
                    <small>Aktifkan jika aplikasi sedang dalam perbaikan.</small>
                </div>
            </label>
        </div>

        <div class="save-area">
            <button class="save-btn">
                💾 Simpan Tampilan
            </button>
        </div>

    </form>

</div>

<style>
.appearance-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.page-head{
    background:white;
    border-radius:26px;
    padding:22px;
    border:1px solid #fed7aa;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.page-head h2{
    margin:0;
    color:#9a3412;
    font-size:28px;
    font-weight:900;
}

.page-head p{
    margin:6px 0 0;
    color:#6b7280;
}

.section-card{
    background:white;
    border:1px solid #fed7aa;
    border-radius:26px;
    padding:18px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
    margin-bottom:18px;
}

.section-title{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:14px;
}

.section-title h3{
    margin:0;
    color:#9a3412;
    font-size:20px;
    font-weight:900;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:14px;
}

.form-group label{
    display:block;
    margin-bottom:7px;
    color:#9a3412;
    font-weight:900;
    font-size:13px;
}

.form-group input[type="text"],
.form-group input[type="number"]{
    width:100%;
    height:48px;
    border:none;
    background:#fff7ed;
    border-radius:16px;
    padding:0 14px;
    font-weight:800;
    outline:none;
}

.form-group small{
    display:block;
    margin-top:6px;
    color:#9ca3af;
    font-size:12px;
}

.form-group input:focus{
    box-shadow:0 0 0 3px rgba(249,115,22,.16);
}

.upload-info{
    align-items:flex-start;
}

.upload-info small{
    display:block;
    color:#6b7280;
    font-size:11px;
    margin-top:4px;
    line-height:1.3;
}
.color-row{
    display:flex;
    align-items:center;
    gap:10px;
}

.color-row input[type="color"]{
    width:52px;
    height:48px;
    border:none;
    padding:0;
    background:none;
    cursor:pointer;
}

.color-row span{
    flex:1;
    color:white;
    height:48px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    box-shadow:0 8px 18px rgba(15,23,42,.12);
}

.upload-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(190px,1fr));
    gap:14px;
}

.upload-card{
    background:#fff7ed;
    border:1px solid #fed7aa;
    border-radius:20px;
    padding:12px;
}

.preview-box{
    height:105px;
    background:white;
    border-radius:16px;
    border:1px dashed #fdba74;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    margin-bottom:10px;
}

.preview-box img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
}

.empty-preview{
    color:#9ca3af;
    font-weight:900;
    font-size:12px;
}

.upload-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
}

.upload-row strong{
    color:#9a3412;
    font-size:13px;
    font-weight:900;
}

.upload-desc{
    margin:8px 0 0;
    color:#6b7280;
    font-size:11px;
    line-height:1.4;
}

.upload-info strong{
    color:#9a3412;
    font-size:13px;
}

.upload-btn{
    background:#f97316;
    color:white;
    padding:8px 12px;
    border-radius:12px;
    font-size:12px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
}

.upload-btn input{
    display:none;
}

.new-preview{
    margin-top:8px;
}

.new-preview small{
    display:block;
    margin-bottom:5px;
    color:#16a34a;
    font-size:12px;
    font-weight:900;
}

.new-preview img{
    width:100%;
    max-height:95px;
    object-fit:contain;
    border-radius:14px;
    background:white;
    border:1px solid #fed7aa;
    padding:6px;
}

.maintenance-card{
    padding:16px;
}

.maintenance-toggle{
    display:flex;
    align-items:center;
    gap:14px;
    cursor:pointer;
}

.maintenance-toggle input{
    display:none;
}

.maintenance-toggle span{
    width:54px;
    height:30px;
    background:#e5e7eb;
    border-radius:999px;
    position:relative;
    flex-shrink:0;
}

.maintenance-toggle span::after{
    content:"";
    width:24px;
    height:24px;
    background:white;
    border-radius:50%;
    position:absolute;
    top:3px;
    left:3px;
    box-shadow:0 3px 8px rgba(15,23,42,.2);
    transition:.2s;
}

.maintenance-toggle input:checked + span{
    background:#f97316;
}

.maintenance-toggle input:checked + span::after{
    left:27px;
}

.maintenance-toggle b{
    color:#9a3412;
    display:block;
}

.maintenance-toggle small{
    color:#6b7280;
}

.save-area{
    position:sticky;
    bottom:16px;
    display:flex;
    justify-content:flex-end;
    z-index:10;
}

.save-btn{
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:15px 28px;
    border-radius:18px;
    font-weight:900;
    cursor:pointer;
    box-shadow:0 12px 28px rgba(249,115,22,.28);
}

.toast-success{
    background:#dcfce7;
    color:#166534;
    padding:14px 18px;
    border-radius:16px;
    font-weight:900;
}

@media(max-width:640px){
    .page-head h2{
        font-size:24px;
    }

    .upload-grid{
        grid-template-columns:1fr 1fr;
    }

    .save-area{
        justify-content:stretch;
    }

    .save-btn{
        width:100%;
    }
}
</style>

<script>
function previewImage(event, targetId){
    const file = event.target.files[0];
    const target = document.getElementById(targetId);

    if(!file || !target){
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e){
        target.innerHTML = `
            <small>Preview baru:</small>
            <img src="${e.target.result}">
        `;
    };

    reader.readAsDataURL(file);
}
</script>

@endsection