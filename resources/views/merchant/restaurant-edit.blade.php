@extends('layouts.merchant-page')

@section('content')
<div class="food-card merchant-setting-head">
    <div>
        <h2>Pengaturan Merchant</h2>
        <p>Atur profil restoran, jadwal buka, dan lokasi merchant.</p>
    </div>

</div>

<div class="food-card">

    <form method="POST"
          action="/merchant/restaurants/{{ $restaurant->id }}/update"
          enctype="multipart/form-data">

        @csrf

        <div class="photo-box">

            <label class="label-title">
                Foto Merchant
            </label>

            <div class="photo-preview">

                @if($restaurant->photo)

                    <img src="{{ asset('storage/'.$restaurant->photo) }}">

                @else

                    <div class="photo-empty">
                        🍔
                    </div>

                @endif

            </div>

            <input type="file"
                   name="photo"
                   class="form-control">

        </div>

        <div class="form-group">
            <label class="label-title">
                Nama Restoran
            </label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ $restaurant->name }}"
                   required>
        </div>

        <div class="form-group">

            <label class="label-title">
                Kategori Restoran
            </label>

            <select name="category"
                    class="form-control"
                    required>

                @foreach(['Cafe','Makanan Indonesia','Fast Food','Minuman','Seafood','Ayam & Bebek','Bakso & Mie'] as $category)

                    <option value="{{ $category }}"
                        {{ $restaurant->category == $category ? 'selected' : '' }}>

                        {{ $category }}

                    </option>

                @endforeach

            </select>

        </div>

        <div class="form-group">

            <label class="label-title">
                Alamat
            </label>

            <textarea name="address"
                      class="form-control"
                      rows="4"
                      required>{{ $restaurant->address }}</textarea>

        </div>

        <div class="schedule-card">

            <label class="section-title">
                Jadwal Operasional
            </label>

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
                                   value="{{ $openValue }}">

                        </div>

                        <div>

                            <small>Tutup</small>

                            <input type="time"
                                   name="schedule[{{ $day }}][close]"
                                   class="form-control"
                                   value="{{ $closeValue }}">

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

        <input type="hidden"
               name="latitude"
               id="latitude"
               value="{{ $restaurant->latitude }}">

        <input type="hidden"
               name="longitude"
               id="longitude"
               value="{{ $restaurant->longitude }}">

        <div class="form-group">

            <label class="section-title">
                Lokasi Merchant
            </label>

            <div id="mapMerchant"></div>

        </div>

        <div class="save-bottom">

            <button type="submit"
                    class="save-btn">

                Simpan Pengaturan

            </button>

        </div>

    </form>

</div>

<style>

.merchant-setting-head{
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
    border-radius:24px;
    padding:22px;
}

.merchant-setting-head h2{
    margin:0;
    font-size:28px;
    font-weight:900;
}

.merchant-setting-head p{
    margin-top:6px;
    opacity:.95;
}
.photo-box{
    margin-bottom:24px;
}

.photo-preview{
    margin:18px 0;
}

.photo-preview img,
.photo-empty{
    width:120px;
    height:120px;
    border-radius:26px;
    object-fit:cover;
    background:#fff7ed;
    border:3px solid #fed7aa;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:42px;
}

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
    color:#9a3412;
    font-size:20px;
    margin-bottom:16px;
}

.schedule-card{
    background:#fff7ed;
    border:1px solid #fed7aa;
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
    border-radius:18px;
    padding:14px;
}

.day-check{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:900;
    color:#111827;
}

.schedule-row small{
    display:block;
    font-weight:900;
    color:#9a3412;
    margin-bottom:5px;
}

#mapMerchant{
    height:340px;
    border-radius:26px;
    overflow:hidden;
    border:3px solid #fed7aa;
    margin-top:10px;
}

.save-bottom{
    position:sticky;
    bottom:15px;
    margin-top:30px;
}

.save-btn{
    width:100%;
    border:none;
    background:linear-gradient(135deg,#ff6b00,#ea580c);
    color:white;
    padding:16px;
    border-radius:20px;
    font-size:17px;
    font-weight:900;
    box-shadow:0 14px 30px rgba(234,88,12,.25);
}
.food-card{
    max-width:900px;
    margin-left:auto;
    margin-right:auto;
    border-radius:28px !important;
}

.form-control{
    width:100% !important;
    max-width:100% !important;
    border:1px solid #fed7aa !important;
    border-radius:18px !important;
    padding:14px 16px !important;
    font-size:15px !important;
    background:#fff !important;
    outline:none !important;
}

.form-control:focus{
    border-color:#f97316 !important;
    box-shadow:0 0 0 4px rgba(249,115,22,.14) !important;
}

textarea.form-control{
    min-height:110px;
    resize:vertical;
}

select.form-control{
    height:52px;
}

input[type="file"].form-control{
    padding:12px !important;
}

.photo-preview img,
.photo-empty{
    width:140px;
    height:140px;
    border-radius:30px;
}

.schedule-row{
    border-radius:22px !important;
}

#mapMerchant{
    height:380px !important;
    border-radius:30px !important;
}

@media(max-width:640px){
    .food-card{
        max-width:100%;
        border-radius:24px !important;
    }

    .merchant-setting-head h2{
        font-size:24px;
    }

    .form-control{
        font-size:16px !important;
    }

    #mapMerchant{
        height:320px !important;
    }
}
@media(max-width:640px){

    .schedule-row{
        grid-template-columns:1fr;
    }

    .merchant-setting-head{
        align-items:flex-start;
    }

}

</style>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const lat = {{ $restaurant->latitude ?? -7.7956 }};
    const lng = {{ $restaurant->longitude ?? 110.3695 }};

    const map = L.map('mapMerchant').setView([lat, lng], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    const marker = L.marker([lat, lng], {
        draggable:true
    }).addTo(map);

    marker.on('dragend', function(){

        const pos = marker.getLatLng();

        document.getElementById('latitude').value = pos.lat;

        document.getElementById('longitude').value = pos.lng;

    });

    setTimeout(function(){

        map.invalidateSize();

    }, 400);

});

</script>

@endsection