<section class="javajek-profile">

    <div class="profile-hero">
        <div>
            <h2>👤 Profil Akun</h2>
            <p>Perbarui data akun, alamat, foto, dan lokasi GPS Anda.</p>
        </div>

        @if($user->photo)
            <img src="{{ asset('storage/' . $user->photo) }}" class="profile-avatar">
        @else
            <div class="profile-avatar empty">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post"
          action="{{ route('profile.update') }}"
          class="profile-form"
          enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="form-group">
            <label>Nama</label>
            <input id="name"
                   name="name"
                   type="text"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus
                   autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="form-group">
            <label>Email</label>
            <input id="email"
                   name="email"
                   type="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="form-group">
            <label>Nomor HP</label>
            <input id="phone"
                   name="phone"
                   type="text"
                   value="{{ old('phone', $user->phone) }}">
        </div>

        <div class="form-group">
            <label>Alamat Lengkap</label>
            <textarea id="address"
                      name="address"
                      rows="4">{{ old('address', $user->address) }}</textarea>
        </div>

        <div class="form-group">
            <label>Foto Profil</label>
            <label class="upload-box">
                <span>📷 Pilih Foto Profil</span>
                <input id="photo" name="photo" type="file">
            </label>
        </div>

        <input type="hidden" name="latitude" id="latitude" value="{{ $user->latitude }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ $user->longitude }}">

        <div id="gps-status" class="gps-box loading">
            📍 Mengambil lokasi GPS...
        </div>

        <div class="profile-actions">
            <button type="submit" class="save-btn">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="saved-text">
                    ✅ Tersimpan.
                </p>
            @endif
        </div>
    </form>

</section>

<style>
.javajek-profile{
    background:linear-gradient(135deg,#fff7ed,#ffffff);
    border:1px solid #fed7aa;
    border-radius:28px;
    padding:22px;
    box-shadow:0 14px 34px rgba(15,23,42,.08);
}

.profile-hero{
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
    border-radius:26px;
    padding:20px;
    margin-bottom:22px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
}

.profile-hero h2{
    margin:0;
    font-size:28px;
    font-weight:900;
}

.profile-hero p{
    margin:6px 0 0;
    opacity:.95;
    font-weight:600;
}

.profile-avatar{
    width:82px;
    height:82px;
    border-radius:24px;
    object-fit:cover;
    border:4px solid rgba(255,255,255,.7);
    background:white;
}

.profile-avatar.empty{
    display:flex;
    align-items:center;
    justify-content:center;
    color:#f97316;
    font-size:34px;
    font-weight:900;
}

.profile-form{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.form-group label{
    display:block;
    font-size:13px;
    font-weight:900;
    color:#9a3412;
    margin-bottom:7px;
}

.form-group input,
.form-group textarea{
    width:100%;
    border:1px solid #fed7aa;
    background:white;
    border-radius:18px;
    padding:14px;
    outline:none;
    color:#111827;
    font-size:15px;
    box-shadow:0 6px 16px rgba(15,23,42,.04);
}

.form-group input:focus,
.form-group textarea:focus{
    border-color:#f97316;
    box-shadow:0 0 0 4px rgba(249,115,22,.14);
}

.upload-box{
    background:#fff7ed;
    border:2px dashed #fdba74;
    border-radius:20px;
    padding:16px;
    cursor:pointer;
    text-align:center;
}

.upload-box input{
    display:none;
}

.upload-box span{
    color:#ea580c;
    font-weight:900;
}

.gps-box{
    padding:14px;
    border-radius:18px;
    font-weight:900;
}

.gps-box.loading{
    background:#fff7ed;
    color:#ea580c;
}

.gps-box.success{
    background:#dcfce7;
    color:#166534;
}

.gps-box.error{
    background:#fee2e2;
    color:#991b1b;
}

.profile-actions{
    display:flex;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
}

.save-btn{
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:15px 22px;
    border-radius:18px;
    font-weight:900;
    cursor:pointer;
    box-shadow:0 10px 24px rgba(249,115,22,.25);
}

.saved-text{
    margin:0;
    color:#166534;
    font-weight:900;
}

@media(max-width:640px){
    .profile-hero{
        align-items:flex-start;
    }

    .profile-hero h2{
        font-size:24px;
    }

    .profile-avatar{
        width:68px;
        height:68px;
        border-radius:20px;
    }

    .save-btn{
        width:100%;
    }
}
</style>

<script>
navigator.geolocation.getCurrentPosition(
    function(position) {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;

        let gps = document.getElementById('gps-status');
        gps.innerHTML = '✅ Lokasi GPS berhasil didapatkan';
        gps.classList.remove('loading', 'error');
        gps.classList.add('success');
    },
    function(error) {
        let gps = document.getElementById('gps-status');
        gps.innerHTML = '⚠️ GPS gagal. Aktifkan izin lokasi.';
        gps.classList.remove('loading', 'success');
        gps.classList.add('error');
    }
);
</script>