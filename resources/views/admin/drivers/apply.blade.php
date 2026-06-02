@extends('layouts.customer-page')

@section('content')

<div class="apply-driver-page">

    <div class="apply-hero">
        <div>
            <h2>🛵 Daftar Sebagai Driver</h2>
            <p>Lengkapi data kendaraan dan lokasi agar admin bisa memverifikasi pengajuan Anda.</p>
        </div>
    </div>

    <form method="POST" action="/apply-driver" class="apply-card">
        @csrf

        <div class="form-section-title">
            👤 Data Kontak
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input type="text"
                   name="phone"
                   class="form-input"
                   placeholder="Contoh: 08123456789"
                   required>
        </div>

        <div class="form-section-title">
            🚗 Data Kendaraan
        </div>

        <div class="vehicle-choice">
            <label class="vehicle-option">
                <input type="radio" name="vehicle_type" value="motor" checked>
                <div>
                    <span>🛵</span>
                    <b>Motor</b>
                </div>
            </label>

            <label class="vehicle-option">
                <input type="radio" name="vehicle_type" value="mobil">
                <div>
                    <span>🚗</span>
                    <b>Mobil</b>
                </div>
            </label>
        </div>

        <div class="plate-grid">
            <div class="form-group">
                <label>Plat Depan</label>
                <input type="text"
                       name="plate_prefix"
                       class="form-input plate-input"
                       placeholder="K"
                       maxlength="2"
                       required>
            </div>

            <div class="form-group">
                <label>Nomor Plat</label>
                <input type="text"
                       name="plate_number_middle"
                       class="form-input plate-input"
                       placeholder="1234"
                       maxlength="4"
                       required>
            </div>

            <div class="form-group">
                <label>Plat Belakang</label>
                <input type="text"
                       name="plate_suffix"
                       class="form-input plate-input"
                       placeholder="AB"
                       maxlength="3"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label>Merk Kendaraan</label>
            <input type="text"
                   name="vehicle_brand"
                   class="form-input"
                   placeholder="Contoh: Honda Beat / Toyota Avanza">
        </div>

        <div class="form-group">
            <label>Warna Kendaraan</label>
            <input type="text"
                   name="vehicle_color"
                   class="form-input"
                   placeholder="Contoh: Hitam">
        </div>

        <div class="form-section-title">
            📍 Lokasi Driver
        </div>

        <div class="form-group">
            <label>Alamat Lengkap</label>
            <textarea name="address"
                      id="address"
                      class="form-input textarea"
                      placeholder="Alamat lengkap tempat Anda biasa online..."
                      required></textarea>
        </div>

        <div class="location-grid">
            <div class="form-group">
                <label>Latitude</label>
                <input type="text"
                       name="latitude"
                       id="latitude"
                       class="form-input"
                       placeholder="-6.53">
            </div>

            <div class="form-group">
                <label>Longitude</label>
                <input type="text"
                       name="longitude"
                       id="longitude"
                       class="form-input"
                       placeholder="111.04">
            </div>
        </div>

        <button type="button" class="location-btn" onclick="getCurrentLocation()">
            📍 Ambil Lokasi Saya
        </button>

        <button type="submit" class="submit-btn">
            Kirim Pengajuan
        </button>
    </form>

</div>

<style>
.apply-driver-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.apply-hero{
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    border-radius:28px;
    padding:22px;
    box-shadow:0 14px 30px rgba(249,115,22,.25);
}

.apply-hero h2{
    margin:0;
    font-size:28px;
    font-weight:900;
}

.apply-hero p{
    margin:8px 0 0;
    opacity:.95;
    line-height:1.5;
}

.apply-card{
    background:white;
    border:1px solid #fed7aa;
    border-radius:28px;
    padding:20px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.form-section-title{
    color:#9a3412;
    font-size:18px;
    font-weight:900;
    margin:10px 0 14px;
}

.form-group{
    margin-bottom:14px;
}

.form-group label{
    display:block;
    color:#9a3412;
    font-size:13px;
    font-weight:900;
    margin-bottom:7px;
}

.form-input{
    width:100%;
    border:none;
    background:#fff7ed;
    border-radius:18px;
    padding:14px;
    font-size:14px;
    font-weight:700;
    outline:none;
    color:#111827;
}

.form-input:focus{
    box-shadow:0 0 0 3px rgba(249,115,22,.18);
}

.textarea{
    min-height:95px;
    resize:vertical;
}

.vehicle-choice{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:12px;
    margin-bottom:16px;
}

.vehicle-option input{
    display:none;
}

.vehicle-option div{
    background:#fff7ed;
    border:2px solid transparent;
    border-radius:22px;
    padding:18px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:8px;
    cursor:pointer;
    transition:.2s;
}

.vehicle-option span{
    font-size:34px;
}

.vehicle-option b{
    color:#9a3412;
    font-weight:900;
}

.vehicle-option input:checked + div{
    background:linear-gradient(135deg,#f97316,#fb923c);
    border-color:#fdba74;
    box-shadow:0 10px 22px rgba(249,115,22,.25);
}

.vehicle-option input:checked + div b{
    color:white;
}

.plate-grid{
    display:grid;
    grid-template-columns:1fr 1.4fr 1fr;
    gap:10px;
}

.plate-input{
    text-transform:uppercase;
    text-align:center;
    font-weight:900;
}

.location-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
}

.location-btn{
    width:100%;
    border:none;
    background:#eff6ff;
    color:#1d4ed8;
    padding:14px;
    border-radius:18px;
    font-weight:900;
    margin-bottom:12px;
    cursor:pointer;
}

.submit-btn{
    width:100%;
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:16px;
    border-radius:20px;
    font-size:16px;
    font-weight:900;
    box-shadow:0 12px 24px rgba(249,115,22,.25);
    cursor:pointer;
}

@media(max-width:640px){
    .vehicle-choice,
    .location-grid{
        grid-template-columns:1fr;
    }

    .plate-grid{
        grid-template-columns:1fr;
    }
}
</style>

<script>
function getCurrentLocation(){
    if(!navigator.geolocation){
        alert('Browser tidak mendukung GPS.');
        return;
    }

    navigator.geolocation.getCurrentPosition(function(pos){
        document.getElementById('latitude').value = pos.coords.latitude;
        document.getElementById('longitude').value = pos.coords.longitude;

        alert('Lokasi berhasil diambil.');
    }, function(){
        alert('Gagal mengambil lokasi. Pastikan izin GPS aktif.');
    });
}
</script>

@endsection