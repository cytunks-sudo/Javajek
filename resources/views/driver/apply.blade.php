@extends('layouts.driver-page')

@section('content')

<div class="apply-page">
    <div class="apply-card">

        <div class="apply-header">
            <h2>🛵 Daftar Sebagai Driver</h2>
            <p>Lengkapi data berikut untuk bergabung menjadi Driver JavaJek.</p>
        </div>

        <form method="POST" action="/apply-driver" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>📱 Nomor HP</label>
                <input type="text" name="phone" class="form-input" placeholder="08xxxxxxxxxx" required>
            </div>

            <div class="form-group">
                <label>🚘 Jenis Kendaraan</label>
                <select name="vehicle_type" class="form-input" required>
                    <option value="">Pilih Kendaraan</option>
                    <option value="motor">🛵 Motor</option>
                    <option value="mobil">🚗 Mobil</option>
                </select>
            </div>

            <div class="form-group">
                <label>🔖 Nomor Plat</label>
                <input type="text" name="plate_number" class="form-input" placeholder="K 1234 XX" required>
            </div>

            <div class="form-group">
                <label>🏠 Alamat Lengkap</label>
                <textarea name="address" class="form-input textarea" placeholder="Masukkan alamat lengkap..." required></textarea>
            </div>

            <div class="form-group">
                <label>📸 Foto Diri</label>
                <input type="file" name="photo" class="form-input" accept="image/*" required>
            </div>

            <div class="form-group">
                <label>🪪 Foto SIM</label>
                <input type="file" name="sim_photo" class="form-input" accept="image/*" required>
            </div>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div class="gps-box">
                <div class="gps-icon">📍</div>
                <div>
                    <b>Status GPS</b>
                    <div id="gps-status">Mengambil lokasi GPS...</div>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                🚀 Kirim Pengajuan Driver
            </button>
        </form>

    </div>
</div>

<style>
.apply-page{display:flex;flex-direction:column;gap:16px}
.apply-card{background:white;border-radius:28px;padding:22px;border:1px solid rgba(15,23,42,.06);box-shadow:0 12px 30px rgba(15,23,42,.08)}
.apply-header{margin-bottom:20px}
.apply-header h2{margin:0;color:var(--primary);font-size:28px;font-weight:900}
.apply-header p{margin:8px 0 0;color:#6b7280;line-height:1.6}
.form-group{margin-bottom:16px}
.form-group label{display:block;margin-bottom:8px;color:var(--primary);font-size:14px;font-weight:900}
.form-input{width:100%;border:none;outline:none;background:rgba(15,23,42,.05);border-radius:18px;padding:14px;font-size:14px;color:#111827}
.form-input:focus{box-shadow:0 0 0 4px rgba(15,23,42,.06)}
.textarea{min-height:110px;resize:vertical}
.gps-box{display:flex;align-items:center;gap:14px;padding:16px;border-radius:20px;background:rgba(15,23,42,.05);margin:18px 0}
.gps-icon{width:52px;height:52px;border-radius:16px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--primary),var(--secondary));color:white;font-size:22px}
#gps-status{margin-top:4px;color:#6b7280;font-size:14px;font-weight:700}
.submit-btn{width:100%;border:none;cursor:pointer;padding:17px;border-radius:20px;color:white;font-size:15px;font-weight:900;background:linear-gradient(135deg,var(--primary),var(--secondary));box-shadow:0 12px 24px rgba(15,23,42,.14)}
.submit-btn:hover{transform:translateY(-1px)}
@media(max-width:640px){.apply-card{padding:18px;border-radius:24px}.apply-header h2{font-size:24px}}
</style>

<script>
navigator.geolocation.getCurrentPosition(
    function(position){
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
        document.getElementById('gps-status').innerHTML = '✅ Lokasi GPS berhasil didapatkan';
    },
    function(){
        document.getElementById('gps-status').innerHTML = '❌ GPS gagal didapatkan. Aktifkan lokasi perangkat.';
    }
);
</script>

@endsection