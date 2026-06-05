@extends('layouts.admin')

@section('content')

<div class="appearance-page">

    <div class="page-head">
        <div>
            <h2>⚙️ Pengaturan Aplikasi</h2>
            <p>Atur tema, radius, saldo driver, komisi, logo, icon, dan banner.</p>
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
                <h3>🎨 Tampilan Utama</h3>
            </div>

            <div class="form-grid compact-grid">
                <div class="form-group">
                    <label>Nama Aplikasi</label>
                    <input type="text"
                           name="app_name"
                           value="{{ old('app_name', $setting->app_name ?? 'JavaJek') }}">
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

        <div class="dual-section">

            <div class="section-card">
                <div class="section-title">
                    <h3>📍 Radius</h3>
                </div>

                <div class="form-stack">
                    <div class="form-group mini">
                        <label>Driver di Peta Customer</label>
                        <input type="number"
                               min="1"
                               name="customer_driver_radius"
                               value="{{ old('customer_driver_radius', $setting->customer_driver_radius ?? 5) }}">
                        <small>Dalam kilometer</small>
                    </div>

                    <div class="form-group mini">
                        <label>Cari Driver Ride/Car</label>
                        <input type="number"
                               min="1"
                               name="ride_search_radius"
                               value="{{ old('ride_search_radius', $setting->ride_search_radius ?? 10) }}">
                        <small>Dalam kilometer</small>
                    </div>

                    <div class="form-group mini">
                        <label>Merchant Terdekat</label>
                        <input type="number"
                               min="1"
                               name="merchant_radius"
                               value="{{ old('merchant_radius', $setting->merchant_radius ?? 20) }}">
                        <small>Dalam kilometer</small>
                    </div>
                </div>
            </div>

            <div class="section-card wallet-card">
                <div class="section-title">
                    <h3>💰 Saldo & Komisi</h3>
                </div>

                <div class="form-stack">
                    <div class="form-group mini">
                        <label>Minimal Saldo Driver</label>
                        <input type="number"
                               min="0"
                               name="driver_min_balance"
                               value="{{ old('driver_min_balance', $setting->driver_min_balance ?? 20000) }}">
                        <small>Driver tidak bisa online jika saldo kurang.</small>
                    </div>

                    <div class="form-group mini">
                        <label>Markup Harga Food (%)</label>
                        <input type="number"
                               min="0"
                               step="0.01"
                               name="food_price_markup_percent"
                               value="{{ old('food_price_markup_percent', $setting->food_price_markup_percent ?? 0) }}">
                        <small>Harga makanan dinaikkan untuk customer.</small>
                    </div>

                    <div class="commission-grid">
                        <div class="form-group mini">
                            <label>Komisi Ongkir Food (%)</label>
                            <input type="number"
                                   min="0"
                                   step="0.01"
                                   name="food_driver_commission_percent"
                                   value="{{ old('food_driver_commission_percent', $setting->food_driver_commission_percent ?? 0) }}">
                        </div>

                        <div class="form-group mini">
                            <label>Komisi Ride (%)</label>
                            <input type="number"
                                   min="0"
                                   step="0.01"
                                   name="ride_driver_commission_percent"
                                   value="{{ old('ride_driver_commission_percent', $setting->ride_driver_commission_percent ?? 0) }}">
                        </div>

                        <div class="form-group mini">
                            <label>Komisi Car (%)</label>
                            <input type="number"
                                   min="0"
                                   step="0.01"
                                   name="car_driver_commission_percent"
                                   value="{{ old('car_driver_commission_percent', $setting->car_driver_commission_percent ?? 0) }}">
                        </div>
                    </div>

                    <div class="info-note">
                        Komisi driver dipotong dari saldo driver saat order selesai.
                    </div>
                </div>
            </div>

        </div>

        <div class="section-card">
            <div class="section-title">
                <h3>🖼️ Logo & Gambar</h3>
            </div>

            <div class="upload-grid">

                @foreach([
                    'login_logo' => ['Logo Login', 'Halaman login.'],
                    'customer_logo' => ['Logo Customer', 'Halaman customer.'],
                    'driver_logo' => ['Logo Driver', 'Halaman driver.'],
                    'merchant_logo' => ['Logo Merchant', 'Halaman merchant.'],
                    'driver_map_icon' => ['Icon Driver Map', 'Icon kendaraan peta.'],
                    'home_banner' => ['Banner Home', 'Banner utama customer.']
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

        <div class="bottom-row">

            <div class="section-card maintenance-card">
                <label class="maintenance-toggle">
                    <input type="checkbox"
                           name="maintenance_mode"
                           {{ !empty($setting->maintenance_mode) ? 'checked' : '' }}>

                    <span></span>

                    <div>
                        <b>Maintenance Mode</b>
                        <small>Aplikasi sedang dalam perbaikan.</small>
                    </div>
                </label>
            </div>

            <div class="save-area">
                <button class="save-btn">
                    💾 Simpan Setting
                </button>
            </div>

        </div>

    </form>

</div>

<style>
.appearance-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.page-head,
.section-card{
    background:white;
    border:1px solid #fed7aa;
    border-radius:24px;
    box-shadow:0 10px 24px rgba(15,23,42,.06);
}

.page-head{
    padding:20px;
}

.page-head h2{
    margin:0;
    color:#9a3412;
    font-size:26px;
    font-weight:900;
}

.page-head p{
    margin:5px 0 0;
    color:#6b7280;
    font-size:13px;
}

.section-card{
    padding:16px;
    margin-bottom:14px;
}

.section-title{
    margin-bottom:12px;
}

.section-title h3{
    margin:0;
    color:#9a3412;
    font-size:18px;
    font-weight:900;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(210px,1fr));
    gap:12px;
}

.compact-grid{
    grid-template-columns:1.2fr 1fr 1fr;
}

.dual-section{
    display:grid;
    grid-template-columns:1fr 1.3fr;
    gap:14px;
}

.form-stack{
    display:flex;
    flex-direction:column;
    gap:11px;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    color:#9a3412;
    font-weight:900;
    font-size:12px;
}

.form-group input[type="text"],
.form-group input[type="number"]{
    width:100%;
    height:44px;
    border:none;
    background:#fff7ed;
    border-radius:14px;
    padding:0 13px;
    font-weight:800;
    outline:none;
}

.form-group.mini input{
    height:42px;
}

.form-group small{
    display:block;
    margin-top:5px;
    color:#9ca3af;
    font-size:11px;
    line-height:1.3;
}

.form-group input:focus{
    box-shadow:0 0 0 3px rgba(249,115,22,.14);
}

.color-row{
    display:flex;
    align-items:center;
    gap:9px;
}

.color-row input[type="color"]{
    width:50px;
    height:44px;
    border:none;
    padding:0;
    background:none;
    cursor:pointer;
}

.color-row span{
    flex:1;
    color:white;
    height:44px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    font-size:12px;
    box-shadow:0 6px 14px rgba(15,23,42,.12);
}

.commission-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
}

.info-note{
    background:#fff7ed;
    border:1px dashed #fdba74;
    color:#9a3412;
    font-size:12px;
    line-height:1.4;
    padding:10px 12px;
    border-radius:14px;
    font-weight:800;
}

.upload-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:12px;
}

.upload-card{
    background:#fff7ed;
    border:1px solid #fed7aa;
    border-radius:18px;
    padding:10px;
}

.preview-box{
    height:82px;
    background:white;
    border-radius:14px;
    border:1px dashed #fdba74;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    margin-bottom:9px;
}

.preview-box img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
}

.empty-preview{
    color:#9ca3af;
    font-weight:900;
    font-size:11px;
}

.upload-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:8px;
}

.upload-row strong{
    color:#9a3412;
    font-size:12px;
    font-weight:900;
}

.upload-desc{
    margin:6px 0 0;
    color:#6b7280;
    font-size:10px;
    line-height:1.35;
}

.upload-btn{
    background:#f97316;
    color:white;
    padding:7px 10px;
    border-radius:11px;
    font-size:11px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
}

.upload-btn input{
    display:none;
}

.new-preview{
    margin-top:7px;
}

.new-preview small{
    display:block;
    margin-bottom:4px;
    color:#16a34a;
    font-size:11px;
    font-weight:900;
}

.new-preview img{
    width:100%;
    max-height:78px;
    object-fit:contain;
    border-radius:12px;
    background:white;
    border:1px solid #fed7aa;
    padding:5px;
}

.bottom-row{
    display:grid;
    grid-template-columns:1fr auto;
    gap:14px;
    align-items:center;
}

.maintenance-card{
    margin:0;
    padding:14px;
}

.maintenance-toggle{
    display:flex;
    align-items:center;
    gap:13px;
    cursor:pointer;
}

.maintenance-toggle input{
    display:none;
}

.maintenance-toggle span{
    width:52px;
    height:28px;
    background:#e5e7eb;
    border-radius:999px;
    position:relative;
    flex-shrink:0;
}

.maintenance-toggle span::after{
    content:"";
    width:22px;
    height:22px;
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
    font-size:13px;
}

.maintenance-toggle small{
    color:#6b7280;
    font-size:11px;
}

.save-area{
    display:flex;
    justify-content:flex-end;
}

.save-btn{
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:15px 24px;
    border-radius:18px;
    font-weight:900;
    cursor:pointer;
    box-shadow:0 10px 24px rgba(249,115,22,.26);
}

.toast-success{
    background:#dcfce7;
    color:#166534;
    padding:13px 16px;
    border-radius:15px;
    font-weight:900;
}

@media(max-width:900px){
    .compact-grid,
    .dual-section,
    .bottom-row{
        grid-template-columns:1fr;
    }

    .commission-grid{
        grid-template-columns:1fr;
    }
}

@media(max-width:640px){
    .page-head h2{
        font-size:23px;
    }

    .upload-grid{
        grid-template-columns:1fr 1fr;
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