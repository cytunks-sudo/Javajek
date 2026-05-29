@extends('layouts.driver-page')

@section('content')

<div class="food-card">
    <h2 class="order-title">📜 Riwayat Order Driver</h2>

    @forelse($orders as $order)

        <div class="order-card">
            <div class="order-head">
                <div>
                    <b>
                        @if($order->order_type == 'ojek')
                            🏍️ Ojek #{{ $order->id }}
                        @else
                            🍔 Food #{{ $order->id }}
                        @endif
                    </b>

                    <p class="mini-text">
                        {{ $order->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                <span class="order-status {{ $order->status }}">
                    {{ strtoupper($order->status) }}
                </span>
            </div>

            <div class="simple-total">
                Rp {{ number_format($order->total) }}
            </div>

            @if($order->order_type == 'ojek')
                <div class="route-box">
                    <p><b>📍 Jemput:</b> {{ $order->pickup_address ?? '-' }}</p>
                    <p><b>🏁 Tujuan:</b> {{ $order->destination_address ?? '-' }}</p>
                </div>
            @else
                <div class="route-box">
                    <p><b>🏪 Merchant:</b> {{ $order->restaurant->name ?? '-' }}</p>
                    <p><b>📍 Antar:</b> {{ $order->address ?? '-' }}</p>
                </div>
            @endif
        </div>

    @empty
        <div class="empty-box">
            Belum ada riwayat order.
        </div>
    @endforelse
</div>

<style>
.order-title{font-size:22px;font-weight:900;color:#9a3412;margin-bottom:16px}
.order-card{border:1px solid #fed7aa;border-radius:22px;padding:16px;margin-bottom:16px;background:linear-gradient(135deg,#fff,#fff7ed)}
.order-head{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
.order-head b{font-size:18px;color:#9a3412}
.mini-text{margin:4px 0 0;color:#6b7280;font-size:13px}
.order-status{background:#dcfce7;color:#166534;padding:6px 11px;border-radius:999px;font-weight:900;font-size:12px}
.simple-total{color:#ea580c;font-size:18px;font-weight:900;margin:12px 0}
.route-box{background:white;border-radius:16px;padding:12px;margin-bottom:14px;border:1px dashed #fed7aa}
.route-box p{margin:0 0 8px;color:#374151;line-height:1.45}
.empty-box{background:#fff7ed;border-radius:16px;padding:14px;color:#9a3412;font-weight:800}
</style>

@endsection