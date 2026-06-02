@extends('layouts.driver-page')

@section('content')

<div class="rejected-wrapper">

    <div class="rejected-card">

        <div class="rejected-icon">
            ❌
        </div>

        <h2>Pengajuan Ditolak</h2>

        <p class="desc">
            Pengajuan Driver JavaJek Anda belum dapat disetujui oleh admin.
        </p>

        <div class="info-box">
            <strong>Yang bisa Anda lakukan:</strong>

            <ul>
                <li>Periksa kembali data kendaraan dan nomor plat.</li>
                <li>Pastikan foto diri dan foto SIM terlihat jelas.</li>
                <li>Ajukan ulang data driver dengan informasi yang benar.</li>
            </ul>
        </div>

        <div class="action-row">
            <a href="/apply-driver?retry=1" class="primary-btn">
            🔄 Daftar Ulang
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout-btn">
                    Logout
                </button>
            </form>
        </div>

    </div>

</div>

<style>
.rejected-wrapper{
    display:flex;
    justify-content:center;
    align-items:center;
}

.primary-btn,
.logout-btn{
    width:100%;
    height:54px;
    border:none;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    text-decoration:none;
    padding:0 15px;
    border-radius:18px;
    cursor:pointer;
    color:white;
    font-weight:900;
    font-size:15px;
    line-height:1;
}

.rejected-card{
    width:100%;
    max-width:650px;
    background:white;
    border-radius:30px;
    padding:30px;
    text-align:center;
    box-shadow:0 15px 35px rgba(15,23,42,.08);
}

.rejected-icon{
    width:90px;
    height:90px;
    margin:auto auto 20px;
    border-radius:24px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:42px;
    color:white;
    background:#dc2626;
}

.rejected-card h2{
    margin:0;
    color:#dc2626;
    font-size:30px;
    font-weight:900;
}

.desc{
    margin-top:12px;
    color:#6b7280;
    line-height:1.7;
}

.info-box{
    margin-top:24px;
    padding:18px;
    border-radius:20px;
    background:rgba(15,23,42,.04);
    text-align:left;
}

.info-box strong{
    display:block;
    color:var(--primary);
    margin-bottom:10px;
}

.info-box ul{
    margin:0;
    padding-left:20px;
    color:#6b7280;
    line-height:1.8;
}

.action-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
    margin-top:24px;
}

.primary-btn,
.logout-btn{
    width:100%;
    border:none;
    display:block;
    text-align:center;
    text-decoration:none;
    padding:15px;
    border-radius:18px;
    cursor:pointer;
    color:white;
    font-weight:900;
}

.primary-btn{
    background:linear-gradient(135deg,var(--primary),var(--secondary));
}

.logout-btn{
    background:#dc2626;
}

@media(max-width:640px){
    .rejected-card{
        padding:22px;
        border-radius:24px;
    }

    .rejected-card h2{
        font-size:24px;
    }

    .rejected-icon{
        width:75px;
        height:75px;
        font-size:34px;
    }

    .action-row{
        grid-template-columns:1fr;
    }
}
</style>

@endsection