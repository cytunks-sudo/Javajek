@extends('layouts.customer-page')
@section('content')

<div class="profile-page">

    <div class="profile-header-card">
        <div>
            <h2>👤 Profil Saya</h2>
            <p>Kelola data akun, password, dan keamanan akun.</p>
        </div>
    </div>

    <div class="profile-section-card">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="profile-section-card">
        @include('profile.partials.update-password-form')
    </div>

    <div class="profile-section-card danger-card">
        @include('profile.partials.delete-user-form')
    </div>

</div>

<style>
.profile-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.profile-header-card{
    background:linear-gradient(135deg,#ff6b00,#ff8a1f,#ffc078);
    color:white;
    border-radius:28px;
    padding:22px;
    box-shadow:0 14px 34px rgba(249,115,22,.22);
}

.profile-header-card h2{
    margin:0;
    font-size:28px;
    font-weight:900;
}

.profile-header-card p{
    margin:6px 0 0;
    opacity:.95;
    font-weight:600;
}

.profile-section-card{
    background:white;
    border:1px solid #fed7aa;
    border-radius:28px;
    padding:20px;
    box-shadow:0 12px 30px rgba(15,23,42,.08);
}

.danger-card{
    border-color:#fecaca;
    background:linear-gradient(135deg,#fff,#fff7f7);
}

@media(max-width:640px){
    .profile-header-card h2{
        font-size:24px;
    }

    .profile-section-card{
        padding:16px;
    }
}
</style>

@endsection