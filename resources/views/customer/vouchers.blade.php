@extends('layouts.customer-page')

@section('content')

<div class="jv-page">

    <div class="jv-header">
        <div class="jv-header-icon">🎁</div>
        <div>
            <h2>Voucher Saya</h2>
            <p>Voucher aktif yang bisa digunakan saat checkout.</p>
        </div>
    </div>

    <div class="jv-list">

        @forelse($vouchers as $voucher)

            <div class="jv-ticket">

                <div class="jv-left">
                    @if($voucher->image)
                        <img src="{{ asset('storage/'.$voucher->image) }}" alt="{{ $voucher->name }}">
                    @else
                        <div class="jv-fallback">
                            @if($voucher->type == 'free_delivery')
                                🚚
                            @elseif($voucher->type == 'percent')
                                %
                            @else
                                Rp
                            @endif
                        </div>
                    @endif
                </div>

                <div class="jv-cut">
                    <span></span>
                </div>

                <div class="jv-body">

                    <div class="jv-top">
                        <div>
                            <h3>{{ $voucher->code }}</h3>
                            <p>{{ $voucher->name }}</p>
                        </div>

                        @if($voucher->is_new_user_only)
                            <span class="jv-badge">User Baru</span>
                        @endif
                    </div>

                    <div class="jv-value">
                        @if($voucher->type == 'fixed')
                            Potongan Rp {{ number_format($voucher->value) }}
                        @elseif($voucher->type == 'percent')
                            Diskon {{ number_format($voucher->value) }}%
                        @else
                            Gratis Ongkir
                        @endif
                    </div>

                    <div class="jv-info">
                        <span>Min. Rp {{ number_format($voucher->minimum_order ?? 0) }}</span>

                        @if(($voucher->maximum_discount ?? 0) > 0)
                            <span>Maks. Rp {{ number_format($voucher->maximum_discount) }}</span>
                        @endif
                    </div>

                    <div class="jv-bottom">
                        <small>
                            @if($voucher->end_date)
                                Berlaku sampai {{ \Carbon\Carbon::parse($voucher->end_date)->format('d M Y') }}
                            @else
                                Berlaku tanpa batas waktu
                            @endif
                        </small>

                        <a href="/cart" class="jv-btn">Pakai</a>
                    </div>

                </div>

            </div>

        @empty
            <div class="jv-empty">
                Belum ada voucher aktif.
            </div>
        @endforelse

    </div>

</div>

<style>
.jv-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.jv-header{
    background:white;
    border-radius:24px;
    padding:16px;
    display:flex;
    align-items:center;
    gap:13px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.jv-header-icon{
    width:44px;
    height:44px;
    border-radius:16px;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:23px;
    flex-shrink:0;
}

.jv-header h2{
    margin:0;
    color:#111827;
    font-size:21px;
    font-weight:900;
}

.jv-header p{
    margin:4px 0 0;
    color:#6b7280;
    font-size:13px;
    font-weight:700;
}

.jv-list{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.jv-ticket{
    display:grid;
    grid-template-columns:230px 34px 1fr;
    min-height:190px;
    background:white;
    border-radius:28px;
    overflow:hidden;
    border:1px solid rgba(15,23,42,.10);
    box-shadow:0 18px 40px rgba(15,23,42,.13);
}

.jv-left{
    background:linear-gradient(180deg,#9ca3af,#6b7280);
    position:relative;
    overflow:hidden;
}

.jv-left img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.jv-fallback{
    width:100%;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:54px;
    font-weight:900;
    background:linear-gradient(180deg,#9ca3af,#6b7280);
}

.jv-cut{
    position:relative;
    background:white;
}

.jv-cut::before,
.jv-cut::after{
    content:"";
    position:absolute;
    left:50%;
    transform:translateX(-50%);
    width:42px;
    height:42px;
    border-radius:50%;
    background:#fff3df;
    box-shadow:inset 0 2px 6px rgba(15,23,42,.10);
    z-index:3;
}

.jv-cut::before{
    top:-21px;
}

.jv-cut::after{
    bottom:-21px;
}

.jv-cut span{
    position:absolute;
    top:24px;
    bottom:24px;
    left:50%;
    transform:translateX(-50%);
    border-left:3px dashed #d1d5db;
}

.jv-body{
    padding:20px 18px 16px 4px;
    background:linear-gradient(135deg,#ffffff,#fffaf3);
}

.jv-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
}

.jv-top h3{
    margin:0;
    color:#111827;
    font-size:23px;
    font-weight:900;
    line-height:1.1;
}

.jv-top p{
    margin:5px 0 0;
    color:#6b7280;
    font-size:13px;
    font-weight:800;
}

.jv-badge{
    background:#dbeafe;
    color:#1d4ed8;
    border-radius:999px;
    padding:6px 10px;
    font-size:11px;
    font-weight:900;
    white-space:nowrap;
}

.jv-value{
    margin-top:14px;
    color:#16a34a;
    font-size:22px;
    line-height:1.1;
    font-weight:900;
}

.jv-info{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-top:11px;
}

.jv-info span{
    background:#ffedd5;
    color:#9a3412;
    border-radius:999px;
    padding:7px 10px;
    font-size:12px;
    font-weight:900;
}

.jv-bottom{
    margin-top:16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
}

.jv-bottom small{
    color:#6b7280;
    font-size:12px;
    font-weight:800;
}

.jv-btn{
    background:linear-gradient(180deg,#9ca3af,#6b7280);
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:14px;
    font-size:12px;
    font-weight:900;
    white-space:nowrap;
    box-shadow:0 8px 20px rgba(15,23,42,.22);
}

.jv-empty{
    background:white;
    border-radius:24px;
    padding:30px;
    text-align:center;
    color:var(--primary-color);
    font-weight:900;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

@media(max-width:640px){
    .jv-ticket{
        grid-template-columns:120px 26px 1fr;
        min-height:165px;
        border-radius:24px;
    }

    .jv-cut::before,
    .jv-cut::after{
        width:32px;
        height:32px;
    }

    .jv-cut::before{
        top:-16px;
    }

    .jv-cut::after{
        bottom:-16px;
    }

    .jv-cut span{
        top:18px;
        bottom:18px;
        border-left:2px dashed #d1d5db;
    }

    .jv-body{
        padding:15px 14px 14px 2px;
    }

    .jv-top h3{
        font-size:18px;
    }

    .jv-value{
        font-size:17px;
    }

    .jv-bottom{
        flex-direction:column;
        align-items:flex-start;
    }

    .jv-btn{
        width:100%;
        text-align:center;
    }
}
</style>

@endsection