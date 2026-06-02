@extends('layouts.admin')

@section('content')

<div class="restaurant-page">

    <div class="page-head">
        <div>
            <h2>🏪 Data Restoran</h2>
            <p>Kelola restoran partner JavaJek Food.</p>
        </div>

        <a href="/restaurants/create" class="btn-add">
            + Tambah Restoran
        </a>
    </div>

    <div class="search-card">
        <input type="text"
               id="searchRestaurant"
               class="search-input"
               placeholder="Cari nama restoran, alamat, telepon, kategori...">
    </div>

    <div class="table-card">
        <table class="restaurant-table">
            <thead>
                <tr>
                    <th>Restoran</th>
                    <th>Kategori</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($restaurants as $restaurant)
                <tr class="restaurant-row">
                    <td>
                        <div class="restaurant-cell">
                            <div class="restaurant-photo">
                                @if(!empty($restaurant->photo))
                                    <img src="{{ asset('storage/'.$restaurant->photo) }}"
                                         onclick="openImage(this.src)">
                                @else
                                    🍔
                                @endif
                            </div>

                            <div>
                                <b>{{ $restaurant->name ?? '-' }}</b>
                                <small>{{ $restaurant->created_at }}</small>
                            </div>
                        </div>
                    </td>

                    <td>
                        <span class="category-badge">
                            {{ $restaurant->category ?? 'Restoran' }}
                        </span>
                    </td>

                    <td>
                        <div class="address-text">
                            {{ $restaurant->address ?? '-' }}
                        </div>
                    </td>

                    <td>{{ $restaurant->phone ?? '-' }}</td>

                    <td>
                        @if($restaurant->status == 'open')
                            <span class="status-badge open">OPEN</span>
                        @else
                            <span class="status-badge closed">CLOSED</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-row">
                            <button type="button"
                                    class="btn-detail"
                                    onclick="openRestaurantModal({{ $restaurant->id }})">
                                Detail
                            </button>

                            <a href="/restaurants/{{ $restaurant->id }}/edit"
                               class="btn-edit">
                                Edit
                            </a>

                            <a href="/restaurants/{{ $restaurant->id }}/delete"
                               class="btn-delete"
                               onclick="return confirm('Yakin hapus restoran ini?')">
                                Hapus
                            </a>
                        </div>
                    </td>
                </tr>

                <div id="restaurantModal{{ $restaurant->id }}" class="restaurant-modal">
                    <div class="restaurant-modal-box">

                        <div class="modal-head">
                            <div>
                                <h3>Detail Restoran</h3>
                                <p>{{ $restaurant->name ?? '-' }}</p>
                            </div>

                            <button type="button"
                                    onclick="closeRestaurantModal({{ $restaurant->id }})">
                                ×
                            </button>
                        </div>

                        <div class="modal-profile">
                            <div class="modal-photo">
                                @if(!empty($restaurant->photo))
                                    <img src="{{ asset('storage/'.$restaurant->photo) }}"
                                         onclick="openImage(this.src)">
                                @else
                                    🍔
                                @endif
                            </div>

                            <div>
                                <h4>{{ $restaurant->name ?? '-' }}</h4>
                                <p>{{ $restaurant->category ?? 'Restoran' }}</p>

                                @if($restaurant->status == 'open')
                                    <span class="status-badge open">OPEN</span>
                                @else
                                    <span class="status-badge closed">CLOSED</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="info-item">
                                <span>No HP</span>
                                <b>{{ $restaurant->phone ?? '-' }}</b>
                            </div>

                            <div class="info-item">
                                <span>Kategori</span>
                                <b>{{ $restaurant->category ?? '-' }}</b>
                            </div>

                            <div class="info-item">
                                <span>Tanggal Dibuat</span>
                                <b>{{ $restaurant->created_at }}</b>
                            </div>

                            <div class="info-item">
                                <span>Status</span>
                                <b>{{ strtoupper($restaurant->status ?? '-') }}</b>
                            </div>

                            <div class="info-item full">
                                <span>Alamat Lengkap</span>
                                <b>{{ $restaurant->address ?? '-' }}</b>
                            </div>

                            <div class="info-item full">
                                <span>Jadwal Operasional</span>

                                @php
                                    $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                                    $savedSchedules = $restaurant->open_days ?? [];
                                @endphp

                                <div class="schedule-list">
                                    @foreach($days as $day)
                                        @if(isset($savedSchedules[$day]))
                                            <div class="schedule-mini">
                                                <b>{{ $day }}</b>
                                                <span>
                                                    {{ $savedSchedules[$day]['open'] ?? '-' }}
                                                    -
                                                    {{ $savedSchedules[$day]['close'] ?? '-' }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="schedule-mini off">
                                                <b>{{ $day }}</b>
                                                <span>Tutup</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="info-item full">
                                <span>Lokasi Restoran</span>

                                @if($restaurant->latitude && $restaurant->longitude)
                                    <div id="mapRestaurant{{ $restaurant->id }}"
                                         class="map-box"
                                         data-lat="{{ $restaurant->latitude }}"
                                         data-lng="{{ $restaurant->longitude }}">
                                    </div>
                                @else
                                    <b>Lokasi belum tersedia</b>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-box">
                            Belum ada data restoran.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

<div id="imageViewer" class="image-viewer" onclick="closeImage()">
    <span class="image-close">×</span>
    <img id="viewerImage" src="">
</div>

<style>
.restaurant-page{width:100%}

.page-head{
    background:white;
    border-radius:26px;
    padding:22px;
    margin-bottom:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}

.page-head h2{
    margin:0;
    color:var(--primary-color);
    font-size:28px;
    font-weight:900;
}

.page-head p{
    margin:6px 0 0;
    color:#6b7280;
}

.btn-add{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
}

.search-card{
    background:white;
    border-radius:22px;
    padding:14px;
    margin-bottom:18px;
    box-shadow:0 10px 24px rgba(15,23,42,.06);
}

.search-input{
    width:100%;
    border:none;
    outline:none;
    background:rgba(15,23,42,.05);
    border-radius:16px;
    padding:14px 16px;
    font-weight:800;
}

.table-card{
    background:white;
    border-radius:26px;
    padding:20px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
    overflow-x:auto;
}

.restaurant-table{
    width:100%;
    border-collapse:collapse;
}

.restaurant-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    text-align:left;
    padding:14px;
    font-size:13px;
    font-weight:900;
    text-transform:uppercase;
}

.restaurant-table td{
    padding:15px 14px;
    border-bottom:1px solid rgba(15,23,42,.06);
    vertical-align:middle;
}

.restaurant-cell{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:210px;
}

.restaurant-cell b{
    display:block;
    color:#111827;
    font-weight:900;
}

.restaurant-cell small{
    color:#6b7280;
    font-size:12px;
}

.restaurant-photo,
.modal-photo{
    width:56px;
    height:56px;
    border-radius:16px;
    overflow:hidden;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:900;
    flex-shrink:0;
}

.restaurant-photo img,
.modal-photo img{
    width:100%;
    height:100%;
    object-fit:cover;
    cursor:pointer;
}

.category-badge,
.status-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.category-badge{
    background:rgba(15,23,42,.05);
    color:var(--primary-color);
}

.status-badge.open{
    background:#dcfce7;
    color:#166534;
}

.status-badge.closed{
    background:#fee2e2;
    color:#991b1b;
}

.address-text{
    max-width:280px;
    color:#374151;
    line-height:1.5;
}

.action-row{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-detail,
.btn-edit,
.btn-delete{
    border:none;
    display:inline-block;
    padding:10px 14px;
    border-radius:14px;
    text-decoration:none;
    font-weight:900;
    font-size:13px;
    cursor:pointer;
}

.btn-detail{
    background:#0ea5e9;
    color:white;
}

.btn-edit{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
}

.btn-delete{
    background:#fee2e2;
    color:#b91c1c;
}

.empty-box{
    text-align:center;
    color:var(--primary-color);
    padding:24px;
    font-weight:900;
}

.restaurant-modal{
    display:none;
    position:fixed;
    inset:0;
    z-index:99999;
    background:rgba(15,23,42,.62);
    padding:24px;
    overflow-y:auto;
}

.restaurant-modal-box{
    background:white;
    width:100%;
    max-width:880px;
    margin:36px auto;
    border-radius:28px;
    padding:22px;
    box-shadow:0 24px 60px rgba(15,23,42,.28);
}

.modal-head{
    display:flex;
    justify-content:space-between;
    gap:16px;
    border-bottom:1px solid rgba(15,23,42,.08);
    padding-bottom:14px;
    margin-bottom:18px;
}

.modal-head h3{
    margin:0;
    color:var(--primary-color);
    font-size:24px;
    font-weight:900;
}

.modal-head p{
    margin:5px 0 0;
    color:#6b7280;
    font-weight:800;
}

.modal-head button{
    width:42px;
    height:42px;
    border:none;
    border-radius:50%;
    background:#fee2e2;
    color:#991b1b;
    font-size:26px;
    font-weight:900;
    cursor:pointer;
}

.modal-profile{
    display:flex;
    gap:16px;
    align-items:center;
    margin-bottom:18px;
}

.modal-photo{
    width:96px;
    height:96px;
    border-radius:26px;
    font-size:38px;
}

.modal-profile h4{
    margin:0;
    color:#111827;
    font-size:24px;
    font-weight:900;
}

.modal-profile p{
    margin:5px 0 8px;
    color:#6b7280;
    font-weight:800;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:12px;
}

.info-item{
    background:rgba(15,23,42,.04);
    border-radius:16px;
    padding:13px;
}

.info-item.full{
    grid-column:1 / -1;
}

.info-item span{
    display:block;
    color:#6b7280;
    font-size:12px;
    font-weight:800;
    margin-bottom:5px;
}

.info-item b{
    color:#111827;
    font-weight:900;
    word-break:break-word;
}

.schedule-list{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:8px;
    margin-top:8px;
}

.schedule-mini{
    background:white;
    border-radius:12px;
    padding:10px;
    display:flex;
    justify-content:space-between;
    gap:10px;
    font-size:13px;
}

.schedule-mini.off{
    opacity:.6;
}

.schedule-mini span{
    margin:0;
    font-size:13px;
}

.map-box{
    height:300px;
    border-radius:18px;
    margin-top:12px;
    overflow:hidden;
    background:#e5e7eb;
}

.image-viewer{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.88);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:999999;
    padding:20px;
}

.image-viewer.active{
    display:flex;
}

.image-viewer img{
    max-width:95%;
    max-height:90vh;
    border-radius:20px;
    box-shadow:0 20px 50px rgba(0,0,0,.4);
}

.image-close{
    position:absolute;
    top:22px;
    right:26px;
    width:50px;
    height:50px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:white;
    color:#ef4444;
    border-radius:50%;
    font-size:30px;
    font-weight:900;
    cursor:pointer;
}

@media(max-width:900px){
    .restaurant-table{
        min-width:900px;
    }
}

@media(max-width:700px){
    .page-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .btn-add{
        width:100%;
        text-align:center;
    }

    .info-grid,
    .schedule-list{
        grid-template-columns:1fr;
    }

    .modal-profile{
        flex-direction:column;
        text-align:center;
    }
}
</style>

<script>
let restaurantMaps = {};

function openRestaurantModal(id){
    const modal = document.getElementById('restaurantModal' + id);

    if(modal){
        modal.style.display = 'block';
    }

    setTimeout(function(){
        const mapEl = document.getElementById('mapRestaurant' + id);

        if(mapEl){
            const lat = parseFloat(mapEl.dataset.lat);
            const lng = parseFloat(mapEl.dataset.lng);

            if(!restaurantMaps[id]){
                restaurantMaps[id] = L.map('mapRestaurant' + id).setView([lat, lng], 16);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(restaurantMaps[id]);

                L.marker([lat, lng]).addTo(restaurantMaps[id]);
            }

            setTimeout(function(){
                restaurantMaps[id].invalidateSize();
            }, 300);
        }
    }, 300);
}

function closeRestaurantModal(id){
    const modal = document.getElementById('restaurantModal' + id);

    if(modal){
        modal.style.display = 'none';
    }
}

function openImage(src){
    const viewer = document.getElementById('imageViewer');
    const img = document.getElementById('viewerImage');

    img.src = src;
    viewer.classList.add('active');
}

function closeImage(){
    document.getElementById('imageViewer').classList.remove('active');
}

document.addEventListener('click', function(e){
    if(e.target.classList.contains('restaurant-modal')){
        e.target.style.display = 'none';
    }
});

document.getElementById('searchRestaurant').addEventListener('keyup', function(){
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('.restaurant-row');

    rows.forEach(function(row){
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
});
</script>

@endsection