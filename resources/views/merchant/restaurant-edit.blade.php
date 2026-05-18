@extends('layouts.customer')

@section('content')

<div class="food-card">

    <h2 style="font-size:28px;font-weight:900;color:#ea580c;margin-bottom:6px;">
        Pengaturan Merchant
    </h2>

    <p style="color:#6b7280;margin-bottom:22px;">
        Atur profil restoran, jadwal buka, dan lokasi merchant.
    </p>

    <form method="POST"
          action="/merchant/restaurants/{{ $restaurant->id }}/update"
          enctype="multipart/form-data">

        @csrf

        <div style="margin-bottom:22px;">
            <label class="label-title">Foto Merchant</label>

            <br><br>

            @if($restaurant->photo)
                <img src="{{ asset('storage/'.$restaurant->photo) }}"
                     style="width:120px;height:120px;border-radius:24px;object-fit:cover;margin-bottom:12px;">
            @endif

            <input type="file" name="photo" class="form-control">
        </div>

        <div class="form-group">
            <label class="label-title">Nama Restoran</label>
            <input type="text" name="name" class="form-control" value="{{ $restaurant->name }}" required>
        </div>

        <div class="form-group">
            <label class="label-title">Kategori Restoran</label>

            <select name="category" class="form-control" required>
                @foreach(['Cafe','Makanan Indonesia','Fast Food','Minuman','Seafood','Ayam & Bebek','Bakso & Mie'] as $category)
                    <option value="{{ $category }}" {{ $restaurant->category == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="label-title">Alamat</label>
            <textarea name="address" class="form-control" rows="4" required>{{ $restaurant->address }}</textarea>
        </div>

        <div class="form-group">
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

        <input type="hidden" name="latitude" id="latitude" value="{{ $restaurant->latitude }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ $restaurant->longitude }}">

        <div class="form-group">
            <label class="label-title">Lokasi Merchant</label>

            <div id="mapMerchant"
                 style="height:320px;border-radius:24px;overflow:hidden;border:2px solid #fed7aa;margin-top:10px;">
            </div>
        </div>

        <button type="submit" class="btn-order">
            Simpan Pengaturan
        </button>

    </form>

</div>

<style>
    .form-group{
        margin-bottom:20px;
    }

    .label-title{
        font-weight:900;
        color:#1f2937;
    }

    .section-title{
        display:block;
        font-weight:900;
        color:#9a3412;
        font-size:18px;
        margin-bottom:14px;
    }

    .schedule-list{
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .schedule-row{
        display:grid;
        grid-template-columns:130px 1fr 1fr;
        gap:12px;
        align-items:center;
        background:#fff7ed;
        border:1px solid #fed7aa;
        border-radius:18px;
        padding:12px;
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
        margin-bottom:4px;
    }

    @media(max-width:640px){
        .schedule-row{
            grid-template-columns:1fr;
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