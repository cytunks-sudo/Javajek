@extends('layouts.admin')

@section('content')

<div class="card-box">
    <h2 class="text-2xl font-bold text-orange-600 mb-5">
        Kelola User
    </h2>
<div style="margin-bottom:20px;display:flex;gap:10px;align-items:center;">

    <input type="text"
           id="searchUser"
           placeholder="Cari nama, email, nomor HP..."
           style="
                width:100%;
                padding:14px;
                border:none;
                border-radius:14px;
                background:#fff7f2;
                outline:none;
                font-size:15px;
           ">

</div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Akses</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @foreach($users as $user)
              <tr class="user-row">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                <td>
                    @foreach($user->roles as $role)
                        <div>
                            <b>{{ $role->role }}</b> - {{ $role->status }}
                        </div>
                    @endforeach
                </td>

                <td>
                    <button class="btn-primary"
                            onclick="openUserModal({{ $user->id }})">
                        Detail
                    </button>

                    <a href="/admin/users/{{ $user->id }}/delete"
                       onclick="return confirm('Yakin hapus user ini?')"
                       style="color:red;font-weight:bold;margin-left:10px;">
                        Hapus
                    </a>
                </td>
            </tr>

            <div id="userModal{{ $user->id }}"
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.55);z-index:9999;overflow-y:auto;padding:30px 0;">

    <div style="
    background:white;
    width:620px;
    max-width:92%;
    height:90vh;
    margin:20px auto;
    padding:0;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 25px 60px rgba(0,0,0,.25);
">

        <div style="
            background:linear-gradient(135deg,#ff5a00,#ff7b00,#ffb36b);
            color:white;
            padding:24px;
        ">
            <h2 style="font-size:26px;font-weight:800;margin:0;">
                Detail User
            </h2>
            <p style="margin:5px 0 0;">
                Informasi akun JavaJek
            </p>
        </div>

        <div style="padding:24px; height:calc(90vh - 115px); overflow-y:auto;">

            <div style="display:flex;gap:18px;align-items:center;margin-bottom:20px;">

                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}"
                         style="width:86px;height:86px;border-radius:50%;object-fit:cover;">
                @else
                    <div style="
                        width:86px;
                        height:86px;
                        border-radius:50%;
                        background:#fff3eb;
                        color:#ff5a00;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:32px;
                        font-weight:800;
                    ">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <h3 style="font-size:22px;font-weight:800;margin:0;">
                        {{ $user->name }}
                    </h3>
                    <p style="color:#666;margin:4px 0;">
                        {{ $user->email }}
                    </p>
                    <p style="color:#666;margin:4px 0;">
                        {{ $user->phone ?? '-' }}
                    </p>
                </div>
            </div>

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                gap:14px;
                margin-bottom:20px;
            ">

                <div style="background:#fff7f2;padding:14px;border-radius:16px;">
                    <b>Role / Akses</b>
                    <br>
                    @forelse($user->roles as $role)
                        <span style="
                            display:inline-block;
                            margin-top:8px;
                            background:#ffedd5;
                            color:#c2410c;
                            padding:6px 10px;
                            border-radius:999px;
                            font-size:12px;
                            font-weight:700;
                        ">
                            {{ strtoupper($role->role) }} - {{ strtoupper($role->status) }}
                        </span>
                    @empty
                        <p>-</p>
                    @endforelse
                </div>

                <div style="background:#fff7f2;padding:14px;border-radius:16px;">
                    <b>Tanggal Daftar</b>
                    <p style="margin:8px 0 0;">
                        {{ $user->created_at }}
                    </p>
                </div>
 <div style="background:#fff7f2;padding:14px;border-radius:16px;margin-bottom:20px;">
                <b>Alamat Lengkap</b>
                <p style="margin:8px 0 0;">
                    {{ $user->address ?? '-' }}
                </p>
            </div>
                <div style="background:#fff7f2;padding:14px;border-radius:16px;margin-bottom:20px;">
    <b>Lokasi User</b>

    @if($user->latitude && $user->longitude)
        <div id="mapUser{{ $user->id }}"
     data-lat="{{ $user->latitude }}"
     data-lng="{{ $user->longitude }}"
     style="height:260px;border-radius:16px;margin-top:12px;overflow:hidden;">
</div>
    @else
        <p style="margin:8px 0 0;">Lokasi belum tersedia</p>
    @endif
</div>

            </div>

           

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button onclick="closeUserModal({{ $user->id }})"
                        class="btn-primary"
                        style="background:#6b7280;">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

        @endforeach
        </tbody>
    </table>
</div>

<script>
let userMaps = {};

function openUserModal(id){
    document.getElementById('userModal' + id).style.display = 'block';

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
    document.getElementById('userModal' + id).style.display = 'none';
}
const searchInput = document.getElementById('searchUser');

searchInput.addEventListener('keyup', function(){

    const keyword = this.value.toLowerCase();

    const rows = document.querySelectorAll('.user-row');

    rows.forEach(function(row){

        const text = row.innerText.toLowerCase();

        if(text.includes(keyword)){
            row.style.display = '';
        }else{
            row.style.display = 'none';
        }

    });

});
</script>


@endsection