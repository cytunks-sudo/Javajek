@extends('layouts.admin')

@section('content')

<div class="appearance-page">

    <div class="page-head">
        <div>
            <h2>⚙️ Pengaturan Aplikasi</h2>
            <p>Atur nama aplikasi, warna tema, radius, saldo, komisi, logo, favicon, icon map, dan banner.</p>
        </div>
    </div>

       <form method="POST"
          action="/admin/app-appearance"
          enctype="multipart/form-data">

        @csrf

        <div class="section-card main-setting-card">
            <div class="section-title">
                <div>
                    <h3>🎨 Tampilan Utama</h3>
                    <p>Nama aplikasi dan warna utama dari App Setting.</p>
                </div>
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

                        <span style="background:{{ old('primary_color', $setting->primary_color ?? '#f97316') }}">
                            {{ old('primary_color', $setting->primary_color ?? '#f97316') }}
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Warna Kedua</label>
                    <div class="color-row">
                        <input type="color"
                               name="secondary_color"
                               value="{{ old('secondary_color', $setting->secondary_color ?? '#fb923c') }}">

                        <span style="background:{{ old('secondary_color', $setting->secondary_color ?? '#fb923c') }}">
                            {{ old('secondary_color', $setting->secondary_color ?? '#fb923c') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dual-section">

            <div class="section-card">
                <div class="section-title">
                    <div>
                        <h3>📍 Radius</h3>
                        <p>Jarak pencarian driver dan merchant.</p>
                    </div>
                </div>

                <div class="form-stack">
                    <div class="form-group mini">
                        <label>Driver di Peta Customer</label>
                        <input type="number"
                               min="0"
                               step="0.1"
                               name="customer_driver_radius"
                               value="{{ old('customer_driver_radius', $setting->customer_driver_radius ?? 5) }}">
                        <small>Dalam kilometer.</small>
                    </div>

                    <div class="form-group mini">
                        <label>Cari Driver Ride / Car</label>
                        <input type="number"
                               min="0"
                               step="0.1"
                               name="ride_search_radius"
                               value="{{ old('ride_search_radius', $setting->ride_search_radius ?? 10) }}">
                        <small>Radius pencarian driver saat pesan ojek/mobil.</small>
                    </div>

                    <div class="form-group mini">
                        <label>Merchant Terdekat</label>
                        <input type="number"
                               min="0"
                               step="0.1"
                               name="merchant_radius"
                               value="{{ old('merchant_radius', $setting->merchant_radius ?? 20) }}">
                        <small>Radius daftar merchant di halaman customer.</small>
                    </div>
                </div>
            </div>

            <div class="section-card wallet-card">
                <div class="section-title">
                    <div>
                        <h3>💰 Saldo & Komisi</h3>
                        <p>Pengaturan saldo minimum dan potongan komisi.</p>
                    </div>
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
            <div class="section-title media-title">
                <div>
                    <h3>🖼️ Logo, Favicon, Icon & Banner</h3>
                    <p>Upload gambar untuk setiap halaman. Semua akan tersimpan di App Setting.</p>
                </div>
            </div>

            <div class="upload-grid">

                @foreach([
                    'login_logo' => [
                        'title' => 'Logo Login / Admin',
                        'desc' => 'Dipakai di login dan panel admin.',
                        'icon' => '🔐',
                        'type' => 'logo'
                    ],
                    'customer_logo' => [
                        'title' => 'Logo Customer',
                        'desc' => 'Dipakai di halaman customer.',
                        'icon' => '👤',
                        'type' => 'logo'
                    ],
                    'driver_logo' => [
                        'title' => 'Logo Driver',
                        'desc' => 'Dipakai di halaman driver.',
                        'icon' => '🛵',
                        'type' => 'logo'
                    ],
                    'merchant_logo' => [
                        'title' => 'Logo Merchant',
                        'desc' => 'Dipakai di halaman merchant.',
                        'icon' => '🏪',
                        'type' => 'logo'
                    ],
                    'favicon' => [
                        'title' => 'Favicon',
                        'desc' => 'Icon kecil pada tab browser. Rekomendasi PNG 32x32 / 64x64.',
                        'icon' => '🌐',
                        'type' => 'favicon'
                    ],
                    'driver_map_icon' => [
                        'title' => 'Icon Driver Map',
                        'desc' => 'Icon kendaraan pada peta.',
                        'icon' => '📍',
                        'type' => 'icon'
                    ],
                    'home_banner' => [
                        'title' => 'Banner Home',
                        'desc' => 'Banner utama di halaman customer.',
                        'icon' => '🖼️',
                        'type' => 'banner'
                    ],
                ] as $field => $info)

                    <div class="upload-card {{ $info['type'] }}">
                        <div class="upload-card-head">
                            <div class="upload-icon">{{ $info['icon'] }}</div>

                            <div>
                                <strong>{{ $info['title'] }}</strong>
                                <p>{{ $info['desc'] }}</p>
                            </div>
                        </div>

                        <div class="preview-box {{ $info['type'] }}">
                            @if(!empty($setting->$field))
                                <img src="{{ asset('storage/'.$setting->$field) }}"
                                     alt="{{ $info['title'] }}">
                            @else
                                <div class="empty-preview">
                                    <span>{{ $info['icon'] }}</span>
                                    <small>Belum ada gambar</small>
                                </div>
                            @endif
                        </div>

                        <div class="upload-action">
                            <label class="upload-btn">
                                Pilih File
                                <input type="file"
                                       name="{{ $field }}"
                                       accept="image/png,image/jpeg,image/jpg,image/webp,image/x-icon,image/vnd.microsoft.icon"
                                       onchange="previewImage(event, 'preview-{{ $field }}')">
                            </label>
                        </div>

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
                <button type="submit" class="save-btn">
                    💾 Simpan Setting
                </button>
            </div>

        </div>

    </form>

</div>

<style>
:root{
    --app-primary: {{ $setting->primary_color ?? '#f97316' }};
    --app-secondary: {{ $setting->secondary_color ?? '#fb923c' }};
    --app-soft: {{ ($setting->secondary_color ?? '#fb923c') }}22;
    --app-soft-2: {{ ($setting->secondary_color ?? '#fb923c') }}33;
}

.appearance-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.page-head,
.section-card{
    background:white;
    border:1px solid color-mix(in srgb, var(--app-secondary) 35%, white);
    border-radius:24px;
    box-shadow:0 10px 26px rgba(15,23,42,.06);
}

.page-head{
    padding:20px;
    background:
        radial-gradient(circle at top right, color-mix(in srgb, var(--app-secondary) 22%, transparent), transparent 34%),
        white;
}

.page-head h2{
    margin:0;
    color:var(--app-primary);
    font-size:26px;
    font-weight:900;
}

.page-head p{
    margin:5px 0 0;
    color:#6b7280;
    font-size:13px;
    line-height:1.4;
}

.section-card{
    padding:16px;
    margin-bottom:14px;
}

.section-title{
    margin-bottom:14px;
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
}

.section-title h3{
    margin:0;
    color:var(--app-primary);
    font-size:18px;
    font-weight:900;
}

.section-title p{
    margin:4px 0 0;
    color:#6b7280;
    font-size:12px;
    line-height:1.35;
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
    grid-template-columns:1fr 1.35fr;
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
    color:var(--app-primary);
    font-weight:900;
    font-size:12px;
}

.form-group input[type="text"],
.form-group input[type="number"]{
    width:100%;
    height:44px;
    border:1px solid color-mix(in srgb, var(--app-secondary) 25%, white);
    background:color-mix(in srgb, var(--app-secondary) 10%, white);
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
    border-color:var(--app-primary);
    box-shadow:0 0 0 3px color-mix(in srgb, var(--app-primary) 18%, transparent);
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
    background:color-mix(in srgb, var(--app-secondary) 12%, white);
    border:1px dashed color-mix(in srgb, var(--app-secondary) 45%, white);
    color:var(--app-primary);
    font-size:12px;
    line-height:1.4;
    padding:10px 12px;
    border-radius:14px;
    font-weight:800;
}

.upload-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(210px,1fr));
    gap:14px;
}

.upload-card{
    background:linear-gradient(180deg, color-mix(in srgb, var(--app-secondary) 9%, white), white);
    border:1px solid color-mix(in srgb, var(--app-secondary) 34%, white);
    border-radius:22px;
    padding:12px;
    display:flex;
    flex-direction:column;
    gap:10px;
}

.upload-card-head{
    display:flex;
    gap:10px;
    align-items:flex-start;
    min-height:58px;
}

.upload-icon{
    width:38px;
    height:38px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,var(--app-primary),var(--app-secondary));
    color:white;
    box-shadow:0 8px 18px color-mix(in srgb, var(--app-primary) 25%, transparent);
    flex-shrink:0;
}

.upload-card-head strong{
    display:block;
    color:var(--app-primary);
    font-size:13px;
    font-weight:900;
}

.upload-card-head p{
    margin:3px 0 0;
    color:#6b7280;
    font-size:10.5px;
    line-height:1.35;
}

.preview-box{
    height:104px;
    background:white;
    border-radius:18px;
    border:1px dashed color-mix(in srgb, var(--app-secondary) 55%, white);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.preview-box.logo,
.preview-box.icon{
    height:104px;
}

.preview-box.favicon{
    height:104px;
}

.preview-box.banner{
    height:120px;
}

.preview-box img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
}

.preview-box.favicon img{
    width:52px;
    height:52px;
    object-fit:contain;
}

.preview-box.banner img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.empty-preview{
    text-align:center;
    color:#9ca3af;
    font-weight:900;
    font-size:11px;
}

.empty-preview span{
    display:block;
    font-size:25px;
    margin-bottom:4px;
}

.upload-action{
    display:flex;
    justify-content:flex-end;
}

.upload-btn{
    background:linear-gradient(135deg,var(--app-primary),var(--app-secondary));
    color:white;
    padding:9px 12px;
    border-radius:13px;
    font-size:11px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
    box-shadow:0 8px 18px color-mix(in srgb, var(--app-primary) 22%, transparent);
}

.upload-btn input{
    display:none;
}

.new-preview{
    margin-top:2px;
}

.new-preview small{
    display:block;
    margin-bottom:5px;
    color:#16a34a;
    font-size:11px;
    font-weight:900;
}

.new-preview img{
    width:100%;
    max-height:90px;
    object-fit:contain;
    border-radius:14px;
    background:white;
    border:1px solid color-mix(in srgb, var(--app-secondary) 35%, white);
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
    background:linear-gradient(135deg,var(--app-primary),var(--app-secondary));
}

.maintenance-toggle input:checked + span::after{
    left:27px;
}

.maintenance-toggle b{
    color:var(--app-primary);
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
    background:linear-gradient(135deg,var(--app-primary),var(--app-secondary));
    color:white;
    padding:15px 24px;
    border-radius:18px;
    font-weight:900;
    cursor:pointer;
    box-shadow:0 10px 24px color-mix(in srgb, var(--app-primary) 25%, transparent);
}

.toast-success{
    background:#dcfce7;
    color:#166534;
    padding:13px 16px;
    border-radius:15px;
    font-weight:900;
}

.toast-error{
    background:#fee2e2;
    color:#991b1b;
    padding:13px 16px;
    border-radius:15px;
    font-weight:800;
}

.toast-error ul{
    margin:8px 0 0;
    padding-left:20px;
}

@media(max-width:1000px){
    .compact-grid,
    .dual-section,
    .bottom-row{
        grid-template-columns:1fr;
    }

    .commission-grid{
        grid-template-columns:1fr;
    }

    .save-area{
        justify-content:stretch;
    }

    .save-btn{
        width:100%;
    }
}

@media(max-width:640px){
    .page-head{
        padding:17px;
        border-radius:22px;
    }

    .page-head h2{
        font-size:23px;
    }

    .section-card{
        padding:14px;
        border-radius:22px;
    }

    .upload-grid{
        grid-template-columns:1fr;
    }

    .upload-card{
        border-radius:20px;
    }

    .preview-box,
    .preview-box.logo,
    .preview-box.icon,
    .preview-box.favicon{
        height:112px;
    }

    .preview-box.banner{
        height:130px;
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