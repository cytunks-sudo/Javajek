@extends('layouts.driver-page')

@section('content')

<div class="driver-setting-page">

    <div class="setting-hero">
        <div>
            <h2>⚙️ Setting Driver</h2>
            <p>Kelola akun, kendaraan, plat nomor, dan lokasi driver.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif


    {{-- FORM DATA AKUN & LOKASI --}}
    <form method="POST"
          action="/driver/settings"
          enctype="multipart/form-data"
          class="setting-form">
        @csrf

        <div class="setting-card">
            <h3>👤 Data Akun</h3>

            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>

            <label>Nomor HP</label>
            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">

            <label>Foto Profil</label>
            <input type="file" name="photo">
        </div>

        <div class="setting-card">
            <h3>📍 Lokasi Driver</h3>

            <input type="hidden" name="latitude" id="latitude" value="{{ $driver->latitude }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ $driver->longitude }}">

            <div id="gpsStatus" class="gps-box">
                Mengambil lokasi GPS...
            </div>
        </div>

        <button type="submit" class="save-btn">
            Simpan Setting Driver
        </button>
    </form>


    {{-- LIST KENDARAAN --}}
    <div class="setting-card">
    <div class="card-title-row">
        <h3>🚗 Kendaraan Saya</h3>

        <button type="button" class="add-vehicle-btn" onclick="openVehicleModal()">
            + Tambah
        </button>
    </div>

    @forelse($driver->vehicles as $vehicle)
        <div class="vehicle-item">
            <div class="vehicle-left">
                <div class="vehicle-icon">
                    {{ $vehicle->vehicle_type == 'mobil' ? '🚗' : '🛵' }}
                </div>

                <div>
                    <b>{{ strtoupper($vehicle->vehicle_type) }}</b>
                    <div>{{ strtoupper($vehicle->plate_number) }}</div>
                    <small>
                        {{ $vehicle->vehicle_brand ?: '-' }}
                        -
                        {{ $vehicle->vehicle_color ?: '-' }}
                    </small>
                </div>
            </div>

            <div class="vehicle-actions">

    @if($vehicle->is_active)

        <span class="active-badge">
            Aktif
        </span>

    @else

        <a href="{{ route('driver.vehicles.active', $vehicle->id) }}"
           class="activate-btn">
            Jadikan Aktif
        </a>

    @endif

    <a href="{{ route('driver.vehicles.delete', $vehicle->id) }}"
       class="delete-btn"
       onclick="return confirm('Yakin ingin menghapus kendaraan ini?')">
        Hapus
    </a>

</div>
        </div>
    @empty
        <div class="empty-box">
            Belum ada kendaraan. Silakan tambahkan kendaraan dulu.
        </div>
    @endforelse
    </div>
    <div class="vehicle-modal-overlay" id="vehicleModal">
    <div class="vehicle-modal">
        <div class="modal-header-custom">
            <h3>➕ Tambah Kendaraan</h3>
            <button type="button" onclick="closeVehicleModal()">×</button>
        </div>

        <form method="POST" action="{{ route('driver.vehicles.add') }}">
            @csrf

            <label>Jenis Kendaraan</label>
            <select name="vehicle_type" required>
                <option value="motor">Motor</option>
                <option value="mobil">Mobil</option>
            </select>

            <label>Nomor Plat</label>

            <div class="plate-row">
                <input type="text"
                name="plate_prefix"
                placeholder="K"
                maxlength="2"
                required
                style="text-transform:uppercase">

                <input type="text"
                name="plate_number_middle"
                placeholder="1234"
                maxlength="4"
                required>

                <input type="text"
                name="plate_suffix"
                placeholder="AA"
                maxlength="3"
                required
                style="text-transform:uppercase">
            </div>

            <label>Merk Kendaraan</label>
            <input type="text" name="vehicle_brand" placeholder="Contoh: Honda Beat / Toyota Avanza">

            <label>Warna Kendaraan</label>
            <input type="text" name="vehicle_color" placeholder="Contoh: Hitam">

            <button class="save-btn" type="submit">
                Simpan Kendaraan
            </button>
        </form>
    </div>
    </div>
</div>


<style>
.driver-setting-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}
.plate-row{
    display:grid;
    grid-template-columns: 70px 1fr 90px;
    gap:8px;
}

.plate-row input{
    text-align:center;
    text-transform:uppercase;
    font-weight:900;
}
.setting-hero{
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
    border-radius:28px;
    padding:22px;
    box-shadow:0 14px 34px rgba(249,115,22,.22);
}

.setting-hero h2{
    margin:0;
    font-size:28px;
    font-weight:900;
}

.setting-hero p{
    margin:6px 0 0;
    opacity:.95;
    font-weight:600;
}

.setting-form{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.setting-card{
    background:white;
    border:1px solid #fed7aa;
    border-radius:28px;
    padding:20px;
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.setting-card h3{
    margin:0 0 18px;
    color:#9a3412;
    font-size:21px;
    font-weight:900;
}
.card-title-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:18px;
}

.card-title-row h3{
    margin:0;
}

.add-vehicle-btn{
    border:none;
    background:#f97316;
    color:white;
    padding:10px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:900;
}

.vehicle-modal-overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.45);
    z-index:9999;
    align-items:center;
    justify-content:center;
    padding:18px;
}

.vehicle-modal{
    background:white;
    width:100%;
    max-width:430px;
    border-radius:28px;
    padding:20px;
    box-shadow:0 24px 60px rgba(15,23,42,.28);
}

.modal-header-custom{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:14px;
}

.modal-header-custom h3{
    margin:0;
    color:#9a3412;
    font-weight:900;
}
.delete-btn{
    background:#ef4444;
    color:white;
    text-decoration:none;
    padding:8px 13px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
    display:inline-block;
    margin-top:6px;
}
.vehicle-actions{
    display:flex;
    flex-direction:column;
    gap:6px;
    align-items:flex-end;
}
.modal-header-custom button{
    border:none;
    background:#fee2e2;
    color:#991b1b;
    width:38px;
    height:38px;
    border-radius:14px;
    font-size:24px;
    font-weight:900;
}

.vehicle-modal label{
    display:block;
    margin:12px 0 7px;
    color:#9a3412;
    font-size:13px;
    font-weight:900;
}

.vehicle-modal input,
.vehicle-modal select{
    width:100%;
    border:1px solid #fed7aa;
    border-radius:18px;
    padding:14px;
    outline:none;
    font-size:15px;
}
.setting-card label{
    display:block;
    margin:12px 0 7px;
    color:#9a3412;
    font-size:13px;
    font-weight:900;
}

.setting-card input,
.setting-card select{
    width:100%;
    border:1px solid #fed7aa;
    border-radius:18px;
    padding:14px;
    outline:none;
    font-size:15px;
    background:#fff;
}

.setting-card input:focus,
.setting-card select:focus{
    border-color:#f97316;
    box-shadow:0 0 0 4px rgba(249,115,22,.13);
}

.vehicle-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:14px;
    border:1px solid #fed7aa;
    border-radius:20px;
    margin-bottom:12px;
    background:#fff7ed;
}

.vehicle-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.vehicle-icon{
    width:44px;
    height:44px;
    border-radius:16px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    box-shadow:0 6px 16px rgba(15,23,42,.08);
}

.vehicle-item b{
    color:#9a3412;
    font-size:15px;
}

.vehicle-item small{
    color:#92400e;
    font-weight:700;
}

.active-badge{
    background:#22c55e;
    color:white;
    padding:8px 13px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.activate-btn{
    background:#3b82f6;
    color:white;
    text-decoration:none;
    padding:8px 13px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.activate-btn:hover{
    color:white;
}

.gps-box,
.empty-box{
    background:#fff7ed;
    color:#ea580c;
    padding:14px;
    border-radius:18px;
    font-weight:900;
}

.gps-box.success{
    background:#dcfce7;
    color:#166534;
}

.gps-box.error,
.error-box{
    background:#fee2e2;
    color:#991b1b;
    padding:14px;
    border-radius:18px;
    font-weight:900;
}

.success-box{
    background:#dcfce7;
    color:#166534;
    padding:14px;
    border-radius:18px;
    font-weight:900;
}

.save-btn{
    width:100%;
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:17px;
    border-radius:20px;
    font-size:16px;
    font-weight:900;
    box-shadow:0 12px 28px rgba(249,115,22,.25);
    margin-top:14px;
}
</style>


<script>
navigator.geolocation.getCurrentPosition(
    function(position){
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;

        let gps = document.getElementById('gpsStatus');
        gps.innerHTML = '✅ Lokasi GPS berhasil diperbarui';
        gps.classList.add('success');
    },
    function(){
        let gps = document.getElementById('gpsStatus');
        gps.innerHTML = '⚠️ GPS gagal. Aktifkan izin lokasi.';
        gps.classList.add('error');
    }
);

function openVehicleModal(){
    document.getElementById('vehicleModal').style.display = 'flex';
}

function closeVehicleModal(){
    document.getElementById('vehicleModal').style.display = 'none';
}

document.getElementById('vehicleModal')?.addEventListener('click', function(e){
    if(e.target === this){
        closeVehicleModal();
    }
});

document.addEventListener('input', function(e){

    if(e.target.name === 'plate_prefix'){
        e.target.value = e.target.value.toUpperCase();
    }

    if(e.target.name === 'plate_suffix'){
        e.target.value = e.target.value.toUpperCase();
    }

});
</script>

@endsection