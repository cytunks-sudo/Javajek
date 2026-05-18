@extends('layouts.customer')

@section('content')

<div class="food-card merchant-hero">
    <div>
        <h2>Dashboard Merchant</h2>
        <p>Kelola restoran, menu makanan, dan pesanan customer.</p>
    </div>

    <a href="/merchant/foods/create" class="hero-btn">
        + Tambah Menu
    </a>
</div>

@forelse($restaurants as $restaurant)

    @php
        $dayMap = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu',
];

$todayEnglish = now()->format('l');
$today = $dayMap[$todayEnglish];
        $now = now()->format('H:i');

       $schedule = $restaurant->open_days[$today] ?? null;

$isScheduleOpen = false;

if ($schedule) {
    $open = $schedule['open'] ?? null;
    $close = $schedule['close'] ?? null;

    if ($open && $close) {
        $isScheduleOpen = $now >= $open && $now <= $close;
    }
}

       $isOpen = !$restaurant->manual_closed && $isScheduleOpen;

    @endphp

    <div class="food-card merchant-card">

        <div class="merchant-main">

            <div class="merchant-left">
                @if($restaurant->photo)
                    <img src="{{ asset('storage/'.$restaurant->photo) }}" class="merchant-img">
                @else
                    <div class="merchant-img empty">🏪</div>
                @endif

                <div>
                    <h3>{{ $restaurant->name }}</h3>
                    <p>{{ $restaurant->category ?? 'Tanpa kategori' }}</p>

                    @if($isOpen)
                        <span class="status-badge open">BUKA</span>
                    @else
                        <span class="status-badge close">TUTUP</span>
                    @endif
                </div>
            </div>

            <div class="merchant-actions">
                <button type="button"
                        class="btn-mini"
                        onclick="openMerchantDetail('merchantDetail{{ $restaurant->id }}')">
                    Detail
                </button>

                <a href="/merchant/restaurants/{{ $restaurant->id }}/edit"
                   class="btn-mini blue">
                    Pengaturan
                </a>

                <a href="/merchant/restaurants/{{ $restaurant->id }}/toggle-open"
                   class="btn-mini {{ $restaurant->manual_closed ? 'green' : 'red' }}">
                    {{ $restaurant->manual_closed ? 'Buka Manual' : 'Tutup Manual' }}
                </a>
            </div>

        </div>

        <div class="merchant-summary">
            <div>
                <b>Hari ini</b><br>
                {{ $today }}
            </div>

            <div>
                <b>Jadwal</b><br>
                @if($schedule)
                    {{ $schedule['open'] }} - {{ $schedule['close'] }}
                @else
                    {{ $restaurant->open_time ?? '--:--' }} - {{ $restaurant->close_time ?? '--:--' }}
                @endif
            </div>

            <div>
                <b>Status Admin</b><br>
                {{ strtoupper($restaurant->status) }}
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        <div id="merchantDetail{{ $restaurant->id }}" class="modal-detail">
            <div class="modal-box">

                <div class="modal-head">
                    <h3>Detail Merchant</h3>
                    <button onclick="closeMerchantDetail('merchantDetail{{ $restaurant->id }}')">×</button>
                </div>

                <div class="modal-body">

                    <p><b>Nama:</b> {{ $restaurant->name }}</p>
                    <p><b>Kategori:</b> {{ $restaurant->category ?? '-' }}</p>
                    <p><b>Alamat:</b><br>{{ $restaurant->address }}</p>
                    <p><b>Status Approval:</b> {{ strtoupper($restaurant->status) }}</p>
                    <p><b>Status Manual:</b> {{ $restaurant->manual_closed ? 'Ditutup Manual' : 'Normal Sesuai Jadwal' }}</p>

                    <h4>Jadwal Operasional</h4>

                    <div class="schedule-list">
                        @php
                            $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                        @endphp

                        @foreach($days as $day)
                            @php
                                $daySchedule = $restaurant->open_days[$day] ?? null;
                            @endphp

                            <div class="schedule-item">
                                <b>{{ $day }}</b>
                                <span>
                                    @if($daySchedule)
                                        {{ $daySchedule['open'] }} - {{ $daySchedule['close'] }}
                                    @else
                                        Tutup
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>
        </div>

        {{-- MENU --}}
        <div class="menu-section">
            <div class="section-head">
                <h4>Menu Makanan</h4>
                <a href="/merchant/foods/create">Tambah</a>
            </div>

            @forelse($restaurant->foods as $food)
                <div class="food-row">
                    <div class="food-info">
                        @if($food->photo)
                            <img src="{{ asset('storage/'.$food->photo) }}">
                        @else
                            <div class="food-empty">🍔</div>
                        @endif

                        <div>
                            <b>{{ $food->name }}</b><br>
                            <span>Rp {{ number_format($food->price) }}</span>
                        </div>
                    </div>

                    <small>{{ strtoupper($food->status ?? 'available') }}</small>
                </div>
            @empty
                <div class="empty-box">Belum ada menu makanan.</div>
            @endforelse
        </div>

    </div>

@empty
    <div class="food-card">
        Anda belum memiliki restoran.
    </div>
@endforelse


<div class="food-card">

    <h3 class="order-title">Pesanan Masuk</h3>

    @forelse($orders as $order)

        <div class="order-card">

            <div class="order-head">
                <b>Order #{{ $order->id }}</b>
                <span>{{ $order->status }}</span>
            </div>

            <p><b>Customer:</b> {{ $order->user->name ?? '-' }}</p>
            <p><b>Driver:</b> {{ $order->driver_status }}</p>
            <p><b>Merchant:</b> {{ $order->merchant_status }}</p>

            <p class="order-total">
                Rp {{ number_format($order->total) }}
            </p>

            <div class="order-items">
                <b>Item Pesanan</b>

                @foreach($order->items as $item)
                    <div>• {{ $item->food->name ?? '-' }} x {{ $item->qty }}</div>
                @endforeach
            </div>

            @if($order->merchant_status == 'pending')
                <div class="order-actions">
                    <a href="/merchant/orders/{{ $order->id }}/accept" class="btn-mini green">
                        Terima
                    </a>

                    <a href="/merchant/orders/{{ $order->id }}/reject" class="btn-mini red">
                        Tolak
                    </a>
                </div>
            @elseif($order->merchant_status == 'accepted')
                <div class="text-success">Merchant menerima pesanan</div>
            @elseif($order->merchant_status == 'rejected')
                <div class="text-danger">Merchant menolak pesanan</div>
            @endif

        </div>

    @empty
        <div class="empty-box">Belum ada pesanan.</div>
    @endforelse

</div>


<style>
    .merchant-hero{
        background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
        color:white;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:15px;
        flex-wrap:wrap;
    }

    .merchant-hero h2{
        font-size:28px;
        font-weight:900;
        margin:0;
    }

    .merchant-hero p{
        margin-top:6px;
        opacity:.95;
    }

    .hero-btn{
        background:white;
        color:#f97316;
        padding:11px 16px;
        border-radius:16px;
        font-weight:900;
        text-decoration:none;
    }

    .merchant-card{
        border:1px solid #fed7aa;
    }

    .merchant-main{
        display:flex;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
        align-items:center;
    }

    .merchant-left{
        display:flex;
        align-items:center;
        gap:14px;
    }

    .merchant-img{
        width:74px;
        height:74px;
        border-radius:20px;
        object-fit:cover;
        background:#ffedd5;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:32px;
    }

    .merchant-left h3{
        font-size:21px;
        font-weight:900;
        margin:0;
        color:#9a3412;
    }

    .merchant-left p{
        margin:3px 0 8px;
        color:#6b7280;
    }

    .status-badge{
        padding:6px 11px;
        border-radius:999px;
        font-weight:900;
        font-size:12px;
    }

    .status-badge.open{
        background:#dcfce7;
        color:#166534;
    }

    .status-badge.close{
        background:#fee2e2;
        color:#991b1b;
    }

    .merchant-actions{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .btn-mini{
        border:none;
        background:#f97316;
        color:white;
        padding:9px 13px;
        border-radius:14px;
        font-weight:900;
        text-decoration:none;
        cursor:pointer;
        display:inline-block;
    }

    .btn-mini.blue{background:#0ea5e9;}
    .btn-mini.green{background:#16a34a;}
    .btn-mini.red{background:#dc2626;}

    .merchant-summary{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:10px;
        margin-top:16px;
    }

    .merchant-summary div{
        background:#fff7ed;
        border-radius:16px;
        padding:12px;
        color:#9a3412;
    }

    .menu-section{
        border-top:1px solid #fed7aa;
        margin-top:16px;
        padding-top:15px;
    }

    .section-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:12px;
    }

    .section-head h4{
        font-size:18px;
        font-weight:900;
        color:#9a3412;
    }

    .section-head a{
        color:#ea580c;
        font-weight:900;
        text-decoration:none;
    }

    .food-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        padding:12px;
        background:#fff7ed;
        border-radius:16px;
        margin-bottom:10px;
    }

    .food-info{
        display:flex;
        align-items:center;
        gap:12px;
    }

    .food-info img,
    .food-empty{
        width:54px;
        height:54px;
        border-radius:14px;
        object-fit:cover;
        background:#fed7aa;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .food-info span{
        color:#ea580c;
        font-weight:900;
    }

    .empty-box{
        background:#fff7ed;
        border-radius:16px;
        padding:14px;
        color:#9a3412;
        font-weight:800;
    }

    .order-title{
        font-size:22px;
        font-weight:900;
        color:#9a3412;
        margin-bottom:16px;
    }

    .order-card{
        border:1px solid #fed7aa;
        border-radius:22px;
        padding:16px;
        margin-bottom:16px;
        background:linear-gradient(135deg,#fff,#fff7ed);
    }

    .order-head{
        display:flex;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:10px;
    }

    .order-head b{
        font-size:18px;
        color:#9a3412;
    }

    .order-head span{
        background:#ffedd5;
        color:#9a3412;
        padding:6px 11px;
        border-radius:999px;
        font-weight:900;
        font-size:12px;
    }

    .order-total{
        font-size:18px;
        color:#ea580c;
        font-weight:900;
    }

    .order-items{
        background:white;
        border-radius:16px;
        padding:12px;
        margin:12px 0;
    }

    .order-actions{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .text-success{
        color:#16a34a;
        font-weight:900;
    }

    .text-danger{
        color:#dc2626;
        font-weight:900;
    }

    .modal-detail{
        display:none;
        position:fixed;
        inset:0;
        background:rgba(15,23,42,.55);
        z-index:9999;
        padding:24px;
        overflow-y:auto;
    }

    .modal-box{
        background:white;
        max-width:620px;
        margin:auto;
        border-radius:24px;
        padding:20px;
    }

    .modal-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        border-bottom:1px solid #fed7aa;
        padding-bottom:12px;
        margin-bottom:14px;
    }

    .modal-head h3{
        font-size:22px;
        font-weight:900;
        color:#9a3412;
    }

    .modal-head button{
        width:38px;
        height:38px;
        border:none;
        border-radius:50%;
        background:#fee2e2;
        color:#991b1b;
        font-size:24px;
        cursor:pointer;
    }

    .schedule-list{
        display:flex;
        flex-direction:column;
        gap:8px;
    }

    .schedule-item{
        display:flex;
        justify-content:space-between;
        background:#fff7ed;
        padding:10px 12px;
        border-radius:14px;
    }

    @media(max-width:640px){
        .merchant-summary{
            grid-template-columns:1fr;
        }

        .merchant-left{
            width:100%;
        }

        .merchant-actions{
            width:100%;
        }

        .btn-mini{
            flex:1;
            text-align:center;
        }
    }
</style>

<script>
function openMerchantDetail(id)
{
    document.getElementById(id).style.display = 'block';
}

function closeMerchantDetail(id)
{
    document.getElementById(id).style.display = 'none';
}

window.addEventListener('click', function(e){
    document.querySelectorAll('.modal-detail').forEach(function(modal){
        if(e.target === modal){
            modal.style.display = 'none';
        }
    });
});
</script>

@endsection