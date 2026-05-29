@extends('layouts.admin')

@section('content')

<div class="appearance-card">

    <div class="page-title">
        <h2>🎨 Tampilan Aplikasi</h2>
        <p>Atur logo, banner, icon map, dan warna utama aplikasi.</p>
    </div>

    @if(session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="/admin/app-appearance"
          enctype="multipart/form-data">

        @csrf

        <div class="form-grid top-grid">

            <div class="form-group">
                <label>Nama Aplikasi</label>
                <input type="text"
                       name="app_name"
                       value="{{ old('app_name', $setting->app_name) }}">
            </div>

            <div class="form-group color-group">
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

            <div class="form-group color-group">
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

        <hr>
<hr>

<h3 class="sub-title">📍 Pengaturan Radius</h3>

<div class="form-grid">

    <div class="form-group">
        <label>Radius Driver di Peta Customer (KM)</label>

        <input type="number"
               min="1"
               name="customer_driver_radius"
               value="{{ $setting->customer_driver_radius ?? 5 }}">
    </div>

    <div class="form-group">
        <label>Radius Cari Driver Ojek (KM)</label>

        <input type="number"
               min="1"
               name="ride_search_radius"
               value="{{ $setting->ride_search_radius ?? 10 }}">
    </div>

    <div class="form-group">
        <label>Radius Merchant Terdekat (KM)</label>

        <input type="number"
               min="1"
               name="merchant_radius"
               value="{{ $setting->merchant_radius ?? 20 }}">
    </div>

</div>
        <h3 class="sub-title">🖼️ Logo & Gambar Saat Ini</h3>

        <div class="upload-grid">

            @foreach([
                'login_logo' => 'Logo Login',
                'customer_logo' => 'Logo Customer',
                'driver_logo' => 'Logo Driver',
                'merchant_logo' => 'Logo Merchant',
                'driver_map_icon' => 'Icon Driver Map',
                'home_banner' => 'Banner Home'
            ] as $field => $label)

                <div class="upload-card">
                    <div class="upload-head">
                        <strong>{{ $label }}</strong>
                    </div>

                    <div class="preview-box">
                        @if(!empty($setting->$field))
                            <img src="{{ asset('storage/'.$setting->$field) }}"
                                 alt="{{ $label }}">
                        @else
                            <div class="empty-preview">
                                Belum ada gambar
                            </div>
                        @endif
                    </div>

                    <label class="upload-btn">
                        Ganti {{ $label }}
                        <input type="file"
                               name="{{ $field }}"
                               onchange="previewImage(event, 'preview-{{ $field }}')">
                    </label>

                    <div id="preview-{{ $field }}" class="new-preview"></div>
                </div>

            @endforeach

        </div>

        <div class="maintenance-box">
            <label>
                <input type="checkbox"
                       name="maintenance_mode"
                       {{ $setting->maintenance_mode ? 'checked' : '' }}>

                Aktifkan Maintenance Mode
            </label>
        </div>

        <button class="save-btn">
            💾 Simpan Tampilan
        </button>

    </form>

</div>

<style>
.appearance-card{
    background:white;
    padding:24px;
    border-radius:28px;
    box-shadow:0 14px 34px rgba(15,23,42,.08);
}

.page-title h2{
    margin:0;
    color:#9a3412;
    font-size:28px;
    font-weight:900;
}

.page-title p{
    margin:6px 0 22px;
    color:#6b7280;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#9a3412;
    font-weight:900;
}

.form-group input[type="text"]{
    width:100%;
    padding:14px;
    border:1px solid #fed7aa;
    border-radius:16px;
    outline:none;
}

.color-row{
    display:flex;
    align-items:center;
    gap:12px;
}

.color-row input[type="color"]{
    width:62px;
    height:46px;
    border:none;
    background:none;
    cursor:pointer;
}

.color-row span{
    color:white;
    padding:12px 16px;
    border-radius:16px;
    font-weight:900;
    box-shadow:0 8px 20px rgba(15,23,42,.12);
}

.sub-title{
    color:#9a3412;
    font-size:22px;
    font-weight:900;
    margin:22px 0 16px;
}

.upload-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:18px;
}

.upload-card{
    border:1px solid #fed7aa;
    border-radius:22px;
    padding:14px;
    background:#fff7ed;
}

.upload-head{
    color:#9a3412;
    margin-bottom:12px;
}

.preview-box{
    height:145px;
    background:white;
    border-radius:18px;
    border:1px dashed #fdba74;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    margin-bottom:12px;
}

.preview-box img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
}

.empty-preview{
    color:#9ca3af;
    font-weight:800;
}

.upload-btn{
    display:block;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:12px;
    border-radius:16px;
    text-align:center;
    font-weight:900;
    cursor:pointer;
}

.upload-btn input{
    display:none;
}

.new-preview{
    margin-top:10px;
}

.new-preview img{
    width:100%;
    max-height:130px;
    object-fit:contain;
    border-radius:16px;
    background:white;
    border:1px solid #fed7aa;
    padding:8px;
}

.maintenance-box{
    margin:22px 0;
    background:#fff7ed;
    padding:16px;
    border-radius:18px;
    color:#9a3412;
    font-weight:900;
}

.save-btn{
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:15px 26px;
    border-radius:18px;
    font-weight:900;
    cursor:pointer;
    box-shadow:0 10px 24px rgba(249,115,22,.25);
}

.success-box{
    background:#dcfce7;
    color:#166534;
    padding:14px;
    border-radius:16px;
    margin-bottom:18px;
    font-weight:900;
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
            <small style="display:block;margin-bottom:6px;color:#16a34a;font-weight:900;">
                Preview gambar baru:
            </small>
            <img src="${e.target.result}">
        `;
    };

    reader.readAsDataURL(file);
}
</script>

@endsection