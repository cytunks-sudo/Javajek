@extends('layouts.admin')

@section('content')

<div class="admin-order-page">

    <div class="page-head">
        <div>
            <h2>📜 History Order</h2>
            <p>Daftar order yang sudah selesai atau dibatalkan.</p>
        </div>

        <div class="count-badge">
            {{ $orders->count() }} History
        </div>
    </div>

    @forelse($orders as $order)

        @php
            $orderType = $order->order_type ?? 'food';

            $orderIcon = match($orderType) {
                'ojek' => '🏍️',
                'car' => '🚗',
                default => '🍔',
            };
        @endphp

        <div class="order-card">

            <div class="order-top">
                <div class="order-title">
                    <div class="order-icon">{{ $orderIcon }}</div>

                    <div>
                        <h3>
                            {{ ucfirst($orderType) }} #{{ $order->order_number ?? $order->id }}
                        </h3>

                        <p>
                            Customer:
                            <b>{{ $order->user->name ?? '-' }}</b>
                        </p>
                    </div>
                </div>

                <span class="status-badge status-{{ $order->status }}">
                    {{ strtoupper(str_replace('_',' ', $order->status)) }}
                </span>
            </div>

            <div class="order-info-grid">
                <div>
                    <span>Restoran / Layanan</span>
                    <b>{{ $order->restaurant->name ?? ucfirst($orderType) }}</b>
                </div>

                <div>
                    <span>Driver</span>
                    <b>{{ $order->driver->user->name ?? 'Belum ada' }}</b>
                </div>

                <div>
                    <span>Total</span>
                    <b>Rp {{ number_format($order->grand_total ?? $order->total) }}</b>
                </div>

                <div>
                    <span>Status Driver</span>
                    <b>{{ strtoupper($order->driver_status ?? '-') }}</b>
                </div>
            </div>

        </div>

    @empty

        <div class="empty-box">
            Belum ada history order.
        </div>

    @endforelse

</div>

<style>
.admin-order-page{width:100%}
.page-head{
    background:white;border-radius:26px;padding:22px;margin-bottom:18px;
    display:flex;justify-content:space-between;align-items:center;gap:16px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}
.page-head h2{margin:0;color:var(--primary-color);font-size:28px;font-weight:900}
.page-head p{margin:6px 0 0;color:#6b7280}
.count-badge{
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;padding:12px 18px;border-radius:16px;font-weight:900;white-space:nowrap;
}
.order-card{
    background:white;border-radius:26px;padding:20px;margin-bottom:16px;
    box-shadow:0 12px 28px rgba(15,23,42,.07);
}
.order-top{
    display:flex;justify-content:space-between;align-items:flex-start;gap:16px;
    border-bottom:1px solid rgba(15,23,42,.07);padding-bottom:16px;margin-bottom:16px;
}
.order-title{display:flex;gap:14px;align-items:center}
.order-icon{
    width:52px;height:52px;border-radius:16px;
    background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));
    color:white;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;
}
.order-title h3{margin:0;color:#111827;font-size:20px;font-weight:900}
.order-title p{margin:4px 0 0;color:#6b7280}
.order-info-grid{
    display:grid;grid-template-columns:repeat(4,1fr);gap:12px;
}
.order-info-grid div{
    background:rgba(15,23,42,.04);border-radius:16px;padding:13px;
}
.order-info-grid span{
    display:block;color:#6b7280;font-size:12px;font-weight:800;margin-bottom:5px;
}
.order-info-grid b{color:#111827;font-weight:900}
.status-badge{
    display:inline-block;padding:8px 12px;border-radius:999px;font-size:12px;
    font-weight:900;white-space:nowrap;background:#fef3c7;color:#92400e;
}
.status-cancelled{background:#fee2e2;color:#991b1b}
.status-completed{background:#dcfce7;color:#166534}
.empty-box{
    background:white;color:var(--primary-color);border-radius:20px;padding:20px;
    font-weight:900;box-shadow:0 12px 28px rgba(15,23,42,.07);
}
@media(max-width:1000px){
    .order-info-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:700px){
    .page-head,.order-top{flex-direction:column;align-items:flex-start}
    .count-badge{width:100%;text-align:center}
    .order-info-grid{grid-template-columns:1fr}
}
</style>

@endsection