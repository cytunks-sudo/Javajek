@extends('layouts.admin')

@section('content')

<div class="card-box">

    <h2 class="text-2xl font-bold mb-6">
        Pengajuan Merchant
    </h2>

    <table>
        <thead>
            <tr>
                <th>Merchant</th>
                <th>Owner</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($restaurants as $restaurant)

            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        @if($restaurant->photo)
                            <img src="{{ asset('storage/'.$restaurant->photo) }}"
                                 onclick="openImageViewer('{{ asset('storage/'.$restaurant->photo) }}')"
                                 style="width:48px;height:48px;border-radius:14px;object-fit:cover;cursor:pointer;">
                        @else
                            <div style="width:48px;height:48px;border-radius:14px;background:#fed7aa;display:flex;align-items:center;justify-content:center;">
                                🏪
                            </div>
                        @endif

                        <b>{{ $restaurant->name }}</b>
                    </div>
                </td>

                <td>{{ $restaurant->owner->name ?? '-' }}</td>
                <td>{{ $restaurant->category ?? '-' }}</td>

                <td>
                    @if($restaurant->status == 'active')
                        <span class="badge-open">ACTIVE</span>
                    @elseif($restaurant->status == 'rejected')
                        <span class="badge-close">REJECTED</span>
                    @else
                        <span class="badge-pending">PENDING</span>
                    @endif
                </td>

                <td>
                    <button type="button"
                            class="btn-primary"
                            onclick="openMerchantModal('merchantModal{{ $restaurant->id }}')">
                        Detail
                    </button>

                    @if($restaurant->status != 'active')
                        <a href="/admin/merchant-applications/{{ $restaurant->id }}/approve"
                           class="btn-primary">
                            Approve
                        </a>
                    @endif

                    @if($restaurant->status != 'rejected')
                        <a href="/admin/merchant-applications/{{ $restaurant->id }}/reject"
                           class="btn-danger">
                            Reject
                        </a>
                    @endif
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="5">Belum ada pengajuan merchant.</td>
            </tr>

        @endforelse
        </tbody>
    </table>

</div>


@foreach($restaurants as $restaurant)

<div id="merchantModal{{ $restaurant->id }}" class="merchant-modal">

    <div class="merchant-modal-content">

        <div class="merchant-modal-header">
            <h3>Detail Merchant</h3>

            <button type="button"
                    onclick="closeMerchantModal('merchantModal{{ $restaurant->id }}')">
                ×
            </button>
        </div>

        <div class="merchant-modal-body">

            <div style="display:flex;gap:18px;flex-wrap:wrap;margin-bottom:16px;">

                @if($restaurant->photo)
                    <img src="{{ asset('storage/'.$restaurant->photo) }}"
                         onclick="openImageViewer('{{ asset('storage/'.$restaurant->photo) }}')"
                         style="width:110px;height:110px;border-radius:22px;object-fit:cover;cursor:pointer;">
                @else
                    <div style="width:110px;height:110px;border-radius:22px;background:#fed7aa;display:flex;align-items:center;justify-content:center;font-size:36px;">
                        🏪
                    </div>
                @endif

                <div>
                    <h2 style="font-size:24px;font-weight:900;margin:0 0 8px;">
                        {{ $restaurant->name }}
                    </h2>

                    <p><b>Owner:</b> {{ $restaurant->owner->name ?? '-' }}</p>
                    <p><b>Kategori:</b> {{ $restaurant->category ?? '-' }}</p>
                    <p><b>Jam:</b> {{ $restaurant->open_time ?? '--:--' }} - {{ $restaurant->close_time ?? '--:--' }}</p>
                    <p><b>Status:</b> {{ strtoupper($restaurant->status) }}</p>
                </div>

            </div>

            <p>
                <b>Alamat:</b><br>
                {{ $restaurant->address }}
            </p>

            @if($restaurant->latitude && $restaurant->longitude)
                <div id="map{{ $restaurant->id }}"
                     class="merchant-map"
                     data-lat="{{ $restaurant->latitude }}"
                     data-lng="{{ $restaurant->longitude }}">
                </div>
            @else
                <p>Lokasi belum tersedia.</p>
            @endif

        </div>

    </div>

</div>

@endforeach


<div id="imageViewer" class="image-viewer">

    <button type="button"
            onclick="closeImageViewer()"
            class="image-viewer-close">
        ×
    </button>

    <img id="viewerImage" src="">

</div>


<style>
    .merchant-modal{
        display:none;
        position:fixed;
        inset:0;
        background:rgba(15,23,42,.55);
        z-index:9999;
        padding:30px;
        overflow-y:auto;
    }

    .merchant-modal-content{
        background:white;
        max-width:760px;
        margin:auto;
        border-radius:26px;
        padding:22px;
        box-shadow:0 20px 50px rgba(0,0,0,.25);
    }

    .merchant-modal-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        border-bottom:1px solid #eee;
        padding-bottom:12px;
        margin-bottom:18px;
    }

    .merchant-modal-header h3{
        margin:0;
        font-size:22px;
        font-weight:900;
        color:#c2410c;
    }

    .merchant-modal-header button{
        width:38px;
        height:38px;
        border:none;
        border-radius:50%;
        background:#fee2e2;
        color:#991b1b;
        font-size:24px;
        font-weight:bold;
        cursor:pointer;
    }

    .merchant-modal-body{
        max-height:72vh;
        overflow-y:auto;
        padding-right:5px;
    }

    .merchant-map{
        height:300px;
        border-radius:20px;
        overflow:hidden;
        border:1px solid #fed7aa;
        margin-top:12px;
    }

    .image-viewer{
        display:none;
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.88);
        z-index:99999;
        justify-content:center;
        align-items:center;
        padding:20px;
    }

    .image-viewer img{
        max-width:95%;
        max-height:92vh;
        border-radius:20px;
        box-shadow:0 20px 60px rgba(0,0,0,.5);
    }

    .image-viewer-close{
        position:absolute;
        top:20px;
        right:20px;
        width:44px;
        height:44px;
        border:none;
        border-radius:50%;
        background:#fff;
        color:#111;
        font-size:24px;
        font-weight:bold;
        cursor:pointer;
    }
</style>


<script>
let merchantMaps = {};

function openMerchantModal(id)
{
    document.getElementById(id).style.display = 'block';

    setTimeout(function(){

        const modal = document.getElementById(id);
        const mapBox = modal.querySelector('.merchant-map');

        if(!mapBox) return;

        const mapId = mapBox.id;
        const lat = mapBox.dataset.lat;
        const lng = mapBox.dataset.lng;

        if(!lat || !lng) return;

        if(!merchantMaps[mapId]){
            const map = L.map(mapId).setView([lat, lng], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);

            merchantMaps[mapId] = map;
        }

        merchantMaps[mapId].invalidateSize();

    }, 300);
}

function closeMerchantModal(id)
{
    document.getElementById(id).style.display = 'none';
}

function openImageViewer(src)
{
    document.getElementById('viewerImage').src = src;
    document.getElementById('imageViewer').style.display = 'flex';
}

function closeImageViewer()
{
    document.getElementById('imageViewer').style.display = 'none';
}

window.addEventListener('click', function(event){

    document.querySelectorAll('.merchant-modal').forEach(function(modal){
        if(event.target === modal){
            modal.style.display = 'none';
        }
    });

    const viewer = document.getElementById('imageViewer');

    if(event.target === viewer){
        closeImageViewer();
    }

});
</script>

@endsection