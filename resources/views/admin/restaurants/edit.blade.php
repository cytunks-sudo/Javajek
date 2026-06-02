@extends('layouts.admin')

@section('content')

<div class="restaurant-edit-page">

    <div class="page-head">
        <div>
            <h2>🏪 Edit Restoran</h2>
            <p>Atur profil restoran, kategori, jadwal buka, dan lokasi merchant.</p>
        </div>

        <a href="/restaurants" class="btn-back">
            ← Kembali
        </a>
    </div>

    <div class="form-card">

        <form method="POST"
              action="/restaurants/{{ $restaurant->id }}/update"
              enctype="multipart/form-data">
            @csrf

            <div class="photo-box">
                <label class="label-title">Foto Restoran</label>

                <div class="photo-preview">
                    @if($restaurant->photo)
                        <img src="{{ asset('storage/'.$restaurant->photo) }}" id="photoPreview">
                    @else
                        <div class="photo-empty" id="photoEmpty">🍔</div>
                        <img src="" id="photoPreview" style="display:none;">
                    @endif
                </div>

                <input type="file"
                       name="photo"
                       id="photoInput"
                       class="form-control"
                       accept="image/*">
            </div>

            <div class="form-group">
                <label class="label-title">Nama Restoran</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $restaurant->name) }}"
                       required>
            </div>

            <div class="form-group">
                <label class="label-title">Kategori Restoran</label>

                <select name="category" class="form-control" required>
                    @foreach(['Cafe','Makanan Indonesia','Fast Food','Minuman','Seafood','Ayam & Bebek','Bakso & Mie'] as $category)
                        <option value="{{ $category }}"
                            {{ old('category', $restaurant->category) == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="label-title">Alamat</label>

                <textarea name="address"
                          class="form-control textarea"
                          required>{{ old('address', $restaurant->address) }}</textarea>
            </div>

            <div class="form-group">
                <label class="label-title">No HP</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone', $restaurant->phone) }}"
                       required>
            </div>

            <div class="schedule-card">
                <label class="section-title">Jadwal Operasional</label>

                <div class="schedule-list">
                    @php
                        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                        $savedSchedules = $restaurant->open_days ?? [];
                    @endphp

                    @foreach($days as $day)
                        @php
                            $isActive = isset($savedSchedules[$day]);
                            $openValue = $savedSchedules[$day]['open'] ?? '09:00';
                            $closeValue = $savedSchedules[$day]['close'] ?? '21:00';
                        @endphp

                        <div class="schedule-row">
                            <label class="day-check">
                                <input type="checkbox"
                                       name="schedule[{{ $day }}][active]"
                                       value="1"
                                       {{ $isActive ? 'checked' : '' }}>

                                <span>{{ $day }}</span>
                            </label>

                            <div>
                                <small>Buka</small>
                                <input type="time"
                                       name="schedule[{{ $day }}][open]"
                                       class="form-control"
                                       value="{{ old('schedule.'.$day.'.open', $openValue) }}">
                            </div>

                            <div>
                                <small>Tutup</small>
                                <input type="time"
                                       name="schedule[{{ $day }}][close]"
                                       class="form-control"
                                       value="{{ old('schedule.'.$day.'.close', $closeValue) }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <input type="hidden"
                   name="latitude"
                   id="latitude"
                   value="{{ old('latitude', $restaurant->latitude ?? -7.7956) }}">

            <input type="hidden"
                   name="longitude"
                   id="longitude"
                   value="{{ old('longitude', $restaurant->longitude ?? 110.3695) }}">

            <div class="form-group">
                <label class="section-title">Lokasi Restoran</label>

                <div id="mapRestaurant"></div>

                <div class="location-info">
                    📍 Klik peta atau geser marker untuk menentukan lokasi restoran.
                </div>
            </div>

            <div class="save-bottom">
                <button type="submit" class="save-btn">
                    💾 Update Restoran
                </button>
            </div>

        </form>

    </div>

</div>

<style>
.restaurant-edit-page{
    width:100%;
}

.page-head{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    border-radius:26px;
    padding:22px;
    margin-bottom:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    box-shadow:0 12px 28px rgba(15,23,42,.12);
}

.page-head h2{
    margin:0;
    font-size:28px;
    font-weight:900;
}

.page-head p{
    margin:6px 0 0;
    opacity:.95;
}

.btn-back{
    background:rgba(255,255,255,.22);
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
}

.form-card{
    background:white;
    border-radius:28px;
    padding:24px;
    max-width:900px;
    margin:auto;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.photo-box,
.form-group{
    margin-bottom:22px;
}

.label-title{
    display:block;
    font-weight:900;
    color:#111827;
    margin-bottom:10px;
}

.section-title{
    display:block;
    font-weight:900;
    color:var(--primary-color);
    font-size:20px;
    margin-bottom:16px;
}

.photo-preview{
    margin:16px 0;
}

.photo-preview img,
.photo-empty{
    width:140px;
    height:140px;
    border-radius:30px;
    object-fit:cover;
    background:rgba(15,23,42,.04);
    border:3px solid rgba(15,23,42,.08);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:42px;
}

.form-control{
    width:100%;
    border:1px solid rgba(15,23,42,.12);
    border-radius:18px;
    padding:14px 16px;
    font-size:15px;
    background:white;
    outline:none;
}

.form-control:focus{
    border-color:var(--primary-color);
    box-shadow:0 0 0 4px rgba(15,23,42,.08);
}

.textarea{
    min-height:120px;
    resize:vertical;
}

select.form-control{
    height:52px;
}

.schedule-card{
    background:rgba(15,23,42,.04);
    border:1px solid rgba(15,23,42,.08);
    border-radius:24px;
    padding:18px;
    margin-bottom:24px;
}

.schedule-list{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.schedule-row{
    display:grid;
    grid-template-columns:140px 1fr 1fr;
    gap:12px;
    align-items:center;
    background:white;
    border-radius:22px;
    padding:14px;
}

.day-check{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:900;
    color:#111827;
}

.day-check input{
    width:18px;
    height:18px;
    accent-color:var(--primary-color);
}

.schedule-row small{
    display:block;
    font-weight:900;
    color:var(--primary-color);
    margin-bottom:5px;
}

#mapRestaurant{
    height:380px;
    border-radius:30px;
    overflow:hidden;
    border:3px solid rgba(15,23,42,.08);
    margin-top:10px;
}

.location-info{
    margin-top:10px;
    padding:14px;
    border-radius:16px;
    background:rgba(15,23,42,.04);
    color:#6b7280;
    font-size:14px;
    font-weight:800;
}

.save-bottom{
    position:sticky;
    bottom:15px;
    margin-top:30px;
}

.save-btn{
    width:100%;
    border:none;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:16px;
    border-radius:20px;
    font-size:17px;
    font-weight:900;
    cursor:pointer;
    box-shadow:0 14px 30px rgba(15,23,42,.16);
}

@media(max-width:640px){
    .page-head{
        flex-direction:column;
        align-items:flex-start;
        border-radius:24px;
    }

    .btn-back{
        width:100%;
        text-align:center;
    }

    .form-card{
        max-width:100%;
        padding:18px;
        border-radius:24px;
    }

    .page-head h2{
        font-size:24px;
    }

    .schedule-row{
        grid-template-columns:1fr;
    }

    #mapRestaurant{
        height:320px;
        border-radius:24px;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const photoInput = document.getElementById('photoInput');

    if(photoInput){
        photoInput.addEventListener('change', function(e){
            const file = e.target.files[0];

            if(!file){
                return;
            }

            const reader = new FileReader();

            reader.onload = function(event){
                const preview = document.getElementById('photoPreview');
                const empty = document.getElementById('photoEmpty');

                preview.src = event.target.result;
                preview.style.display = 'flex';

                if(empty){
                    empty.style.display = 'none';
                }
            };

            reader.readAsDataURL(file);
        });
    }

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    const lat = parseFloat(latInput.value) || -7.7956;
    const lng = parseFloat(lngInput.value) || 110.3695;

    const map = L.map('mapRestaurant').setView([lat, lng], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    const marker = L.marker([lat, lng], {
        draggable:true
    }).addTo(map);

    function setRestaurantLocation(lat, lng){
        latInput.value = lat;
        lngInput.value = lng;
        marker.setLatLng([lat, lng]);
    }

    map.on('click', function(e){
        setRestaurantLocation(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function(){
        const pos = marker.getLatLng();
        setRestaurantLocation(pos.lat, pos.lng);
    });

    setTimeout(function(){
        map.invalidateSize();
    }, 400);
});
</script>

@endsection