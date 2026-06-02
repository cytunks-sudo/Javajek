@extends('layouts.admin')

@section('content')

<div class="user-page">

    <div class="page-head">
        <div>
            <h2>👥 Kelola User</h2>
            <p>Kelola akun pengguna, akses role, alamat, dan lokasi user.</p>
        </div>

        <div class="count-badge">
            {{ $users->count() }} User
        </div>
    </div>

    <form method="GET" action="/admin/users" class="search-card">
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           placeholder="Cari nama, email, username, nomor HP..."
           class="search-input">

    <button class="btn-detail">
        Cari
    </button>

    @if(request('search'))
        <a href="/admin/users" class="btn-delete">
            Reset
        </a>
    @endif
</form>

    <div class="table-card">
        <table class="user-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Akses</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($users as $user)
                <tr class="user-row">
                    <td>
                        <div class="user-cell">
                            <div class="avatar">
                                @if($user->photo)
                                    <img src="{{ asset('storage/'.$user->photo) }}">
                                @else
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                @endif
                            </div>

                            <div>
                                <b>{{ $user->name ?? '-' }}</b>
                                <small>{{ $user->username ?? '-' }}</small>
                            </div>
                        </div>
                    </td>

                    <td>{{ $user->email ?? '-' }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>

                    <td>
                        <div class="role-list">
                            @forelse($user->roles as $role)
                                <span class="role-badge {{ $role->status }}">
                                    {{ strtoupper($role->role) }} - {{ strtoupper($role->status) }}
                                </span>
                            @empty
                                <span class="role-badge empty">
                                    {{ strtoupper($user->role ?? 'CUSTOMER') }}
                                </span>
                            @endforelse
                        </div>
                    </td>

                    <td>
                        <div class="action-row">
                            <button type="button"
                                    class="btn-detail"
                                    onclick="openUserModal({{ $user->id }})">
                                Detail
                            </button>

                            <a href="/admin/users/{{ $user->id }}/delete"
                               class="btn-delete"
                               onclick="return confirm('Yakin hapus user ini?')">
                                Hapus
                            </a>
                        </div>
                    </td>
                </tr>

                <div id="userModal{{ $user->id }}" class="user-modal">
                    <div class="user-modal-box">

                        <div class="modal-head">
                            <div>
                                <h3>Detail User</h3>
                                <p>Informasi akun JavaJek</p>
                            </div>

                            <button type="button"
                                    onclick="closeUserModal({{ $user->id }})">
                                ×
                            </button>
                        </div>

                        <div class="modal-user-head">
                            <div class="modal-avatar">
                                @if($user->photo)
                                    <img src="{{ asset('storage/'.$user->photo) }}">
                                @else
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                @endif
                            </div>

                            <div>
                                <h4>{{ $user->name ?? '-' }}</h4>
                                <p>{{ $user->email ?? '-' }}</p>
                                <p>{{ $user->phone ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="info-item">
                                <span>Username</span>
                                <b>{{ $user->username ?? '-' }}</b>
                            </div>

                            <div class="info-item">
                                <span>Role Utama</span>
                                <b>{{ strtoupper($user->role ?? '-') }}</b>
                            </div>

                            <div class="info-item">
                                <span>Tanggal Daftar</span>
                                <b>{{ $user->created_at }}</b>
                            </div>

                            <div class="info-item">
                                <span>Akses Role</span>
                                <div class="role-list">
                                    @forelse($user->roles as $role)
                                        <span class="role-badge {{ $role->status }}">
                                            {{ strtoupper($role->role) }} - {{ strtoupper($role->status) }}
                                        </span>
                                    @empty
                                        <span class="role-badge empty">-</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="info-item full">
                                <span>Alamat Lengkap</span>
                                <b>{{ $user->address ?? '-' }}</b>
                            </div>

                            <div class="info-item full">
                                <span>Lokasi User</span>

                                @if($user->latitude && $user->longitude)
                                    <div id="mapUser{{ $user->id }}"
                                         data-lat="{{ $user->latitude }}"
                                         data-lng="{{ $user->longitude }}"
                                         class="map-box"></div>
                                @else
                                    <b>Lokasi belum tersedia</b>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-box">
                            Belum ada user.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
.user-page{width:100%}

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

.user-table{
    width:100%;
    border-collapse:collapse;
}

.user-table th{
    background:rgba(15,23,42,.04);
    color:var(--primary-color);
    text-align:left;
    padding:14px;
    font-size:13px;
    font-weight:900;
    text-transform:uppercase;
}

.user-table td{
    padding:15px 14px;
    border-bottom:1px solid rgba(15,23,42,.06);
    vertical-align:middle;
}

.user-cell{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:190px;
}

.avatar,
.modal-avatar{
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

.avatar img,
.modal-avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.user-cell b{
    display:block;
    color:#111827;
    font-weight:900;
}

.user-cell small{
    color:#6b7280;
    font-size:12px;
}

.role-list{
    display:flex;
    flex-wrap:wrap;
    gap:6px;
}

.role-badge{
    display:inline-block;
    padding:7px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    background:#fef3c7;
    color:#92400e;
}

.role-badge.approved{
    background:#dcfce7;
    color:#166534;
}

.role-badge.rejected{
    background:#fee2e2;
    color:#991b1b;
}

.role-badge.empty{
    background:#e5e7eb;
    color:#374151;
}

.action-row{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-detail,
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

.user-modal{
    display:none;
    position:fixed;
    inset:0;
    z-index:99999;
    background:rgba(15,23,42,.62);
    padding:24px;
    overflow-y:auto;
}

.user-modal-box{
    background:white;
    width:100%;
    max-width:760px;
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

.modal-user-head{
    display:flex;
    gap:16px;
    align-items:center;
    margin-bottom:18px;
}

.modal-avatar{
    width:86px;
    height:86px;
    border-radius:24px;
    font-size:34px;
}

.modal-user-head h4{
    margin:0;
    color:#111827;
    font-size:23px;
    font-weight:900;
}

.modal-user-head p{
    margin:4px 0 0;
    color:#6b7280;
    font-weight:700;
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

.map-box{
    height:260px;
    border-radius:18px;
    margin-top:12px;
    overflow:hidden;
    background:#e5e7eb;
}

@media(max-width:900px){
    .user-table{
        min-width:820px;
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

    .info-grid{
        grid-template-columns:1fr;
    }

    .modal-user-head{
        flex-direction:column;
        text-align:center;
    }
}
</style>

<script>
let userMaps = {};

function openUserModal(id){
    const modal = document.getElementById('userModal' + id);

    if(modal){
        modal.style.display = 'block';
    }

    setTimeout(function(){
        const mapEl = document.getElementById('mapUser' + id);

        if(mapEl){
            const lat = parseFloat(mapEl.dataset.lat);
            const lng = parseFloat(mapEl.dataset.lng);

            if(!userMaps[id]){
                userMaps[id] = L.map('mapUser' + id).setView([lat, lng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(userMaps[id]);

                L.marker([lat, lng]).addTo(userMaps[id]);
            }

            setTimeout(function(){
                userMaps[id].invalidateSize();
            }, 300);
        }
    }, 300);
}

function closeUserModal(id){
    const modal = document.getElementById('userModal' + id);

    if(modal){
        modal.style.display = 'none';
    }
}

document.addEventListener('click', function(e){
    if(e.target.classList.contains('user-modal')){
        e.target.style.display = 'none';
    }
});

document.getElementById('searchUser').addEventListener('keyup', function(){
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');

    rows.forEach(function(row){
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
});
</script>

@endsection