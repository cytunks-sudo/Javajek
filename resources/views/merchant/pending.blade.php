@extends('layouts.merchant-page')

@section('content')

<div class="merchant-pending-page">

    <div class="pending-hero">
        <div class="hero-icon">⏳</div>

        <div>
            <h2>Merchant Menunggu Approval</h2>
            <p>Pengajuan merchant Anda sudah berhasil masuk dan sedang dicek oleh admin JavaJek.</p>
        </div>
    </div>

    <div class="info-card">
        <div class="info-icon">📌</div>
        <div>
            <b>Proses Verifikasi</b>
            <p>Admin akan memeriksa data restoran, lokasi, dan kelengkapan informasi sebelum merchant diaktifkan.</p>
        </div>
    </div>

    <div class="timeline-card">
        <h3>Alur Pengajuan</h3>

        <div class="timeline">
            <div class="timeline-item active">
                <span>1</span>
                <div>
                    <b>Pengajuan Dikirim</b>
                    <p>Data merchant sudah masuk ke sistem.</p>
                </div>
            </div>

            <div class="timeline-item active">
                <span>2</span>
                <div>
                    <b>Menunggu Review Admin</b>
                    <p>Admin sedang mengecek data merchant Anda.</p>
                </div>
            </div>

            <div class="timeline-item">
                <span>3</span>
                <div>
                    <b>Merchant Aktif</b>
                    <p>Setelah disetujui, Anda bisa mulai mengelola menu dan menerima order.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="restaurant-list">
        <h3>Data Pengajuan Merchant</h3>

        @forelse($restaurants as $restaurant)
            <div class="restaurant-card">
                <div class="restaurant-top">
                    <div class="restaurant-avatar">
                        @if(!empty($restaurant->photo))
                            <img src="{{ asset('storage/'.$restaurant->photo) }}" alt="{{ $restaurant->name }}">
                        @else
                            🏪
                        @endif
                    </div>

                    <div class="restaurant-info">
                        <b>{{ $restaurant->name }}</b>
                        <p>{{ $restaurant->category ?? 'Kategori belum diisi' }}</p>
                    </div>

                    <span class="status-badge {{ $restaurant->status }}">
                        {{ strtoupper($restaurant->status) }}
                    </span>
                </div>

                <div class="restaurant-detail">
                    <div>
                        <small>📍 Alamat</small>
                        <p>{{ $restaurant->address ?? '-' }}</p>
                    </div>

                    <div>
                        <small>🕒 Jam Operasional</small>
                        <p>
                            {{ $restaurant->open_time ?? '--:--' }}
                            -
                            {{ $restaurant->close_time ?? '--:--' }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-card">
                <div>📭</div>
                <b>Belum ada pengajuan merchant.</b>
                <p>Silakan daftar merchant terlebih dahulu.</p>
            </div>
        @endforelse
    </div>

    <div class="action-card">
        <a href="/merchant" class="primary-btn">🔄 Refresh Status</a>
    </div>

</div>

<style>
.merchant-pending-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.pending-hero{
    position:relative;
    overflow:hidden;
    background:linear-gradient(135deg,var(--primary-color, #f97316),var(--secondary-color, #fb923c));
    color:white;
    border-radius:28px;
    padding:22px;
    display:flex;
    gap:16px;
    align-items:center;
    box-shadow:0 16px 35px rgba(15,23,42,.18);
}

.pending-hero::after{
    content:"";
    position:absolute;
    width:160px;
    height:160px;
    border-radius:50%;
    background:rgba(255,255,255,.14);
    right:-50px;
    top:-60px;
}

.hero-icon{
    width:68px;
    height:68px;
    border-radius:24px;
    background:rgba(255,255,255,.22);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:34px;
    flex-shrink:0;
}

.pending-hero h2{
    margin:0;
    font-size:25px;
    font-weight:900;
}

.pending-hero p{
    margin:6px 0 0;
    font-size:14px;
    line-height:1.5;
    opacity:.95;
}

.info-card,
.timeline-card,
.restaurant-list,
.action-card{
    background:white;
    border-radius:24px;
    padding:18px;
    box-shadow:0 10px 28px rgba(15,23,42,.08);
    border:1px solid rgba(15,23,42,.05);
}

.info-card{
    display:flex;
    gap:13px;
    align-items:flex-start;
    background:#fff7ed;
    border:1px solid #fed7aa;
}

.info-icon{
    width:44px;
    height:44px;
    border-radius:16px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:23px;
    flex-shrink:0;
}

.info-card b{
    color:var(--primary-color, #f97316);
    font-weight:900;
}

.info-card p{
    margin:4px 0 0;
    color:#6b7280;
    line-height:1.5;
    font-size:13px;
}

.timeline-card h3,
.restaurant-list h3{
    margin:0 0 14px;
    color:var(--primary-color, #f97316);
    font-size:19px;
    font-weight:900;
}

.timeline{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.timeline-item{
    display:flex;
    gap:12px;
    align-items:flex-start;
    opacity:.55;
}

.timeline-item.active{
    opacity:1;
}

.timeline-item span{
    width:34px;
    height:34px;
    border-radius:50%;
    background:#e5e7eb;
    color:#6b7280;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    flex-shrink:0;
}

.timeline-item.active span{
    background:linear-gradient(135deg,var(--primary-color, #f97316),var(--secondary-color, #fb923c));
    color:white;
}

.timeline-item b{
    color:#111827;
    font-weight:900;
}

.timeline-item p{
    margin:3px 0 0;
    color:#6b7280;
    font-size:12px;
    line-height:1.4;
}

.restaurant-list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.restaurant-card{
    border:1px solid #fed7aa;
    background:#fff7ed;
    border-radius:22px;
    padding:14px;
}

.restaurant-top{
    display:grid;
    grid-template-columns:54px 1fr auto;
    gap:12px;
    align-items:center;
}

.restaurant-avatar{
    width:54px;
    height:54px;
    border-radius:18px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    font-size:27px;
    flex-shrink:0;
}

.restaurant-avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.restaurant-info{
    min-width:0;
}

.restaurant-info b{
    display:block;
    color:#111827;
    font-size:15px;
    font-weight:900;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.restaurant-info p{
    margin:3px 0 0;
    color:#6b7280;
    font-size:12px;
}

.status-badge{
    padding:7px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
    background:#fef3c7;
    color:#92400e;
}

.status-badge.active{
    background:#dcfce7;
    color:#166534;
}

.status-badge.rejected{
    background:#fee2e2;
    color:#991b1b;
}

.restaurant-detail{
    margin-top:12px;
    display:grid;
    grid-template-columns:1.4fr 1fr;
    gap:10px;
}

.restaurant-detail div{
    background:white;
    border-radius:16px;
    padding:11px;
}

.restaurant-detail small{
    display:block;
    color:var(--primary-color, #f97316);
    font-size:11px;
    font-weight:900;
}

.restaurant-detail p{
    margin:4px 0 0;
    color:#374151;
    font-size:12px;
    line-height:1.4;
}

.empty-card{
    text-align:center;
    background:#fff7ed;
    border:1px dashed #fdba74;
    border-radius:22px;
    padding:24px 14px;
}

.empty-card div{
    font-size:42px;
    margin-bottom:8px;
}

.empty-card b{
    color:#111827;
    font-weight:900;
}

.empty-card p{
    color:#6b7280;
    margin:5px 0 0;
    font-size:13px;
}

.action-card{
    display:flex;
    justify-content:center;
    align-items:center;
}

.primary-btn,
.primary-btn{
    background:linear-gradient(135deg,var(--primary-color, #f97316),var(--secondary-color, #fb923c));
    color:white;
    min-width:220px;
    max-width:280px;
}



@media(max-width:640px){
    .pending-hero{
        align-items:flex-start;
        border-radius:24px;
        padding:18px;
    }

    .hero-icon{
        width:56px;
        height:56px;
        border-radius:20px;
        font-size:28px;
    }

    .pending-hero h2{
        font-size:21px;
    }

    .pending-hero p{
        font-size:13px;
    }

    .restaurant-top{
        grid-template-columns:48px 1fr;
    }

    .status-badge{
        grid-column:1 / -1;
        text-align:center;
    }

    .restaurant-detail{
        grid-template-columns:1fr;
    }

}
</style>

@endsection