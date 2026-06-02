@extends('layouts.admin')

@section('content')

<div class="driver-app-page">

    <div class="page-head">
        <div>
            <h2>📝 Pengajuan Driver</h2>
            <p>Kelola pendaftaran driver baru JavaJek.</p>
        </div>

        <div class="count-badge">
            {{ $applications->count() }} Pengajuan
        </div>
    </div>

    <div class="table-card">
        <table class="driver-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>HP</th>
                    <th>Kendaraan</th>
                    <th>Plat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>
                            <div class="driver-cell">
                                <div class="driver-photo">
                                    @if(!empty($app->photo))
                                        <img src="{{ asset('storage/'.$app->photo) }}">
                                    @else
                                        {{ strtoupper(substr($app->user->name ?? 'D', 0, 1)) }}
                                    @endif
                                </div>

                                <div>
                                    <b>{{ $app->user->name ?? '-' }}</b>
                                    <small>{{ $app->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </td>

                        <td>{{ $app->phone ?? '-' }}</td>

                        <td>
                            <span class="soft-badge">
                                {{ $app->vehicle_type == 'mobil' ? '🚗 Mobil' : '🛵 Motor' }}
                            </span>
                        </td>

                        <td>
                            <span class="plate-badge">
                                {{ strtoupper($app->plate_number ?? '-') }}
                            </span>
                        </td>

                        <td>
                            <span class="status-badge {{ $app->status }}">
                                {{ strtoupper($app->status) }}
                            </span>
                        </td>

                        <td>
                            <div class="action-row">
                                <button type="button"
                                        class="btn-detail"
                                        onclick="openDriverModal('driverModal{{ $app->id }}')">
                                    Detail
                                </button>

                                <a href="/admin/driver-applications/{{ $app->id }}/approve"
                                   class="btn-approve"
                                   onclick="return confirm('Terima pengajuan driver ini?')">
                                    Approve
                                </a>

                                <a href="/admin/driver-applications/{{ $app->id }}/reject"
                                   class="btn-reject"
                                   onclick="return confirm('Tolak pengajuan driver ini?')">
                                    Reject
                                </a>
                            </div>
                        </td>
                    </tr>

                    <div id="driverModal{{ $app->id }}" class="driver-modal">
                        <div class="driver-modal-box">

                            <div class="modal-head">
                                <div>
                                    <h3>Detail Pengajuan Driver</h3>
                                    <p>{{ $app->user->name ?? '-' }}</p>
                                </div>

                                <button type="button"
                                        onclick="closeDriverModal('driverModal{{ $app->id }}')">
                                    ×
                                </button>
                            </div>

                            <div class="modal-grid">
                                <div class="doc-card">
                                    <h4>📸 Foto Diri</h4>

                                    @if(!empty($app->photo))
                                        <img src="{{ asset('storage/'.$app->photo) }}"
                                             class="zoomable-image"
                                             onclick="openImage(this.src)"
                                             alt="Foto Diri">
                                    @else
                                        <div class="no-image">Belum ada foto</div>
                                    @endif
                                </div>

                                <div class="doc-card">
                                    <h4>🪪 Foto SIM</h4>

                                    @if(!empty($app->sim_photo))
                                        <img src="{{ asset('storage/'.$app->sim_photo) }}"
                                             class="zoomable-image"
                                             onclick="openImage(this.src)"
                                             alt="Foto SIM">
                                    @else
                                        <div class="no-image">Belum ada foto SIM</div>
                                    @endif
                                </div>
                            </div>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span>Nama</span>
                                    <b>{{ $app->user->name ?? '-' }}</b>
                                </div>

                                <div class="info-item">
                                    <span>Email</span>
                                    <b>{{ $app->user->email ?? '-' }}</b>
                                </div>

                                <div class="info-item">
                                    <span>No HP</span>
                                    <b>{{ $app->phone ?? '-' }}</b>
                                </div>

                                <div class="info-item">
                                    <span>Kendaraan</span>
                                    <b>{{ $app->vehicle_type == 'mobil' ? 'Mobil' : 'Motor' }}</b>
                                </div>

                                <div class="info-item">
                                    <span>Plat</span>
                                    <b>{{ strtoupper($app->plate_number ?? '-') }}</b>
                                </div>

                                <div class="info-item">
                                    <span>Status</span>
                                    <b>{{ strtoupper($app->status) }}</b>
                                </div>

                                <div class="info-item full">
                                    <span>Alamat</span>
                                    <b>{{ $app->address ?? '-' }}</b>
                                </div>
                            </div>

                            <div class="modal-actions">
                                <a href="/admin/driver-applications/{{ $app->id }}/approve"
                                   class="btn-approve"
                                   onclick="return confirm('Terima pengajuan driver ini?')">
                                    ✅ Approve
                                </a>

                                <a href="/admin/driver-applications/{{ $app->id }}/reject"
                                   class="btn-reject"
                                   onclick="return confirm('Tolak pengajuan driver ini?')">
                                    ❌ Reject
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-box">
                                Belum ada pengajuan driver.
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
.driver-app-page{
    width:100%;
}

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

.count-badge{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    padding:12px 18px;
    border-radius:16px;
    font-weight:900;
    white-space:nowrap;
}

.table-card{
    background:white;
    border-radius:26px;
    padding:20px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
    overflow-x:auto;
}

.driver-table{
    width:100%;
    border-collapse:collapse;
}

.driver-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    text-align:left;
    padding:14px;
    font-size:13px;
    font-weight:900;
    text-transform:uppercase;
}

.driver-table td{
    padding:15px 14px;
    border-bottom:1px solid rgba(15,23,42,.06);
    vertical-align:middle;
}

.driver-cell{
    display:flex;
    align-items:center;
    gap:12px;
}

.driver-cell b{
    display:block;
    color:#111827;
    font-weight:900;
}

.driver-cell small{
    color:#6b7280;
    font-size:12px;
}

.driver-photo{
    width:50px;
    height:50px;
    border-radius:14px;
    overflow:hidden;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    flex-shrink:0;
}

.driver-photo img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.soft-badge,
.plate-badge,
.status-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
}

.soft-badge{
    background:rgba(15,23,42,.05);
    color:var(--primary-color);
}

.plate-badge{
    background:#f8fafc;
    color:#111827;
    border:1px solid #e5e7eb;
}

.status-badge{
    background:#fef3c7;
    color:#92400e;
}

.status-badge.approved{
    background:#dcfce7;
    color:#166534;
}

.status-badge.rejected{
    background:#fee2e2;
    color:#991b1b;
}

.action-row{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-detail,
.btn-approve,
.btn-reject{
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
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
}

.btn-approve{
    background:#16a34a;
    color:white;
}

.btn-reject{
    background:#fee2e2;
    color:#b91c1c;
}

.empty-box{
    text-align:center;
    color:var(--primary-color);
    padding:24px;
    font-weight:900;
}

.driver-modal{
    display:none;
    position:fixed;
    inset:0;
    z-index:99999;
    background:rgba(15,23,42,.62);
    padding:24px;
    overflow-y:auto;
}

.driver-modal-box{
    background:white;
    width:100%;
    max-width:850px;
    margin:40px auto;
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

.modal-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
    margin-bottom:18px;
}

.doc-card{
    background:rgba(15,23,42,.04);
    border-radius:22px;
    padding:14px;
}

.doc-card h4{
    margin:0 0 12px;
    color:var(--primary-color);
    font-size:16px;
    font-weight:900;
}

.doc-card img{
    width:100%;
    height:260px;
    object-fit:cover;
    border-radius:18px;
    background:white;
}

.zoomable-image{
    cursor:pointer;
    transition:.2s;
}

.zoomable-image:hover{
    opacity:.88;
    transform:scale(.99);
}

.no-image{
    height:260px;
    border-radius:18px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#9ca3af;
    font-weight:900;
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

.modal-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:18px;
    flex-wrap:wrap;
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
    .driver-table{
        min-width:780px;
    }
}

@media(max-width:700px){
    .page-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .count-badge{
        width:100%;
        text-align:center;
    }

    .modal-grid,
    .info-grid{
        grid-template-columns:1fr;
    }

    .doc-card img,
    .no-image{
        height:220px;
    }
}
</style>

<script>
function openDriverModal(id){
    const modal = document.getElementById(id);

    if(modal){
        modal.style.display = 'block';
    }
}

function closeDriverModal(id){
    const modal = document.getElementById(id);

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
    if(e.target.classList.contains('driver-modal')){
        e.target.style.display = 'none';
    }
});
</script>

@endsection