@extends('layouts.admin')

@section('content')

<div class="ride-setting-page">

    <form method="POST" action="/admin/ride-setting">
        @csrf

        <div class="ride-grid">

            <div class="ride-box">
                <div class="ride-header ride">
                    🏍️ J-Ride
                </div>

                <div class="form-group">
                    <label>Biaya Awal</label>
                    <input type="text" name="base_fee" class="ride-input"
                          value="{{ old('base_fee', (int)($setting->base_fee ?? 5000)) }}">
                </div>

                <div class="form-group">
                    <label>Biaya Per KM</label>
                    <input type="text" name="per_km_fee" class="ride-input"
                           value="{{ old('per_km_fee', (int)($setting->per_km_fee ?? 2500)) }}">
                </div>

                <div class="form-group">
                    <label>Minimal Tarif</label>
                    <input type="text" name="minimum_fee" class="ride-input"
                           value="{{ old('minimum_fee', (int)($setting->minimum_fee ?? 8000)) }}">
                </div>
            </div>

            <div class="ride-box">
                <div class="ride-header car">
                    🚗 J-Car
                </div>

                <div class="form-group">
                    <label>Biaya Awal</label>
                    <input type="text" name="car_base_fee" class="ride-input"
                           value="{{ old('car_base_fee', (int)($setting->car_base_fee ?? 10000)) }}">
                </div>

                <div class="form-group">
                    <label>Biaya Per KM</label>
                    <input type="text" name="car_per_km_fee" class="ride-input"
                           value="{{ old('car_per_km_fee', (int)($setting->car_per_km_fee ?? 4000)) }}">
                </div>

                <div class="form-group">
                    <label>Minimal Tarif</label>
                    <input type="text" name="car_minimum_fee" class="ride-input"
                           value="{{ old('car_minimum_fee', (int)($setting->car_minimum_fee ?? 15000)) }}">
                </div>
            </div>

        </div>

        <button class="save-btn">
            💾 Simpan Setting
        </button>

    </form>

</div>

<style>
.ride-setting-page{
    padding:10px 0;
}

.alert-success{
    background:#dcfce7;
    color:#166534;
    padding:14px 18px;
    border-radius:16px;
    font-weight:900;
    margin-bottom:18px;
}

.ride-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:25px;
}

.ride-box{
    background:white;
    border-radius:22px;
    padding:20px;
    border:1px solid #fed7aa;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.ride-header{
    color:white;
    font-size:22px;
    font-weight:900;
    padding:16px;
    border-radius:16px;
    margin-bottom:20px;
}

.ride-header.ride{
    background:linear-gradient(135deg,#f97316,#fb923c);
}

.ride-header.car{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:900;
    color:#9a3412;
}

.ride-input{
    width:100%;
    height:54px;
    border:none;
    background:#fff7ed;
    border-radius:14px;
    padding:0 16px;
    font-size:16px;
    font-weight:900;
    outline:none;
}

.ride-input:focus{
    box-shadow:0 0 0 3px rgba(249,115,22,.18);
}

.save-btn{
    margin-top:25px;
    border:none;
    background:linear-gradient(135deg,#f97316,#fb923c);
    color:white;
    padding:16px 32px;
    border-radius:16px;
    font-size:16px;
    font-weight:900;
    box-shadow:0 12px 24px rgba(249,115,22,.28);
    cursor:pointer;
}

.save-btn:hover{
    transform:translateY(-2px);
}

@media(max-width:640px){
    .save-btn{
        width:100%;
    }
}

.toast-success{
    position:fixed;
    top:25px;
    right:25px;
    z-index:99999;

    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;

    padding:16px 22px;
    border-radius:16px;

    font-weight:900;
    font-size:14px;

    box-shadow:0 15px 35px rgba(34,197,94,.35);

    animation:slideIn .4s ease;
}

@keyframes slideIn{
    from{
        opacity:0;
        transform:translateY(-20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>

<script>

document.addEventListener('DOMContentLoaded', function(){

    let toast = document.getElementById('toast-success');

    if(toast){
        setTimeout(function(){
            toast.style.transition = 'all .4s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';

            setTimeout(function(){
                toast.remove();
            },400);

        },3000);
    }

});


</script>

@endsection