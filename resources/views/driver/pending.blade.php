@extends('layouts.driver-page')

@section('content')

<div class="pending-wrapper">

    <div class="pending-card">

        <div class="pending-icon">
            ⏳
        </div>

        <h2>
            Pengajuan Sedang Ditinjau
        </h2>

        <p class="desc">
            Pengajuan Driver JavaJek Anda telah berhasil dikirim dan saat ini sedang diperiksa oleh tim admin.
        </p>

        <div class="status-box">

            <div class="status-item">
                <span>📋 Status</span>
                <b>Menunggu Persetujuan</b>
            </div>

            <div class="status-item">
                <span>🛡️ Verifikasi</span>
                <b>Data Driver & Dokumen</b>
            </div>

            <div class="status-item">
                <span>🔔 Notifikasi</span>
                <b>Akan muncul otomatis setelah disetujui</b>
            </div>

        </div>

        <div class="info-box">
            <strong>Informasi</strong>
            <p>
                Mohon menunggu proses verifikasi. Setelah pengajuan disetujui,
                Anda dapat langsung menerima order dan menggunakan fitur Driver JavaJek.
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>

    </div>

</div>

<style>

.pending-wrapper{
    display:flex;
    justify-content:center;
    align-items:center;
}

.pending-card{
    width:100%;
    max-width:650px;

    background:white;

    border-radius:30px;
    padding:30px;

    text-align:center;

    box-shadow:0 15px 35px rgba(15,23,42,.08);
}

.pending-icon{
    width:90px;
    height:90px;

    margin:auto auto 20px;

    border-radius:24px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:42px;

    color:white;

    background:linear-gradient(
        135deg,
        var(--primary),
        var(--secondary)
    );
}

.pending-card h2{
    margin:0;
    color:var(--primary);
    font-size:30px;
    font-weight:900;
}

.desc{
    margin-top:12px;
    color:#6b7280;
    line-height:1.7;
}

.status-box{
    margin-top:25px;

    display:grid;
    gap:12px;
}

.status-item{
    background:rgba(15,23,42,.04);

    border-radius:18px;

    padding:14px;
    text-align:left;
}

.status-item span{
    display:block;
    color:#6b7280;
    font-size:13px;
}

.status-item b{
    color:#111827;
    font-size:15px;
}

.info-box{
    margin-top:22px;

    padding:18px;

    border-radius:20px;

    background:rgba(15,23,42,.04);

    text-align:left;
}

.info-box strong{
    display:block;
    color:var(--primary);
    margin-bottom:8px;
}

.info-box p{
    margin:0;
    color:#6b7280;
    line-height:1.7;
}

.logout-btn{
    width:100%;
    margin-top:22px;

    border:none;

    padding:15px;

    border-radius:18px;

    cursor:pointer;

    color:white;
    font-weight:900;

    background:linear-gradient(
        135deg,
        var(--primary),
        var(--secondary)
    );
}

@media(max-width:640px){

    .pending-card{
        padding:22px;
        border-radius:24px;
    }

    .pending-card h2{
        font-size:24px;
    }

    .pending-icon{
        width:75px;
        height:75px;
        font-size:34px;
    }
}

</style>

@endsection