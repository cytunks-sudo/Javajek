@extends('layouts.admin')

@section('content')

<div class="voucher-page">

    <div class="voucher-card">

        <h2>✏️ Edit Voucher</h2>

        <form method="POST" action="/admin/vouchers/{{ $voucher->id }}/update" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Kode Voucher</label>
                <input type="text" name="code" value="{{ $voucher->code }}" required>
            </div>

            <div class="form-group">
                <label>Nama Voucher</label>
                <input type="text" name="name" value="{{ $voucher->name }}" required>
            </div>

            @if($voucher->image)
                <div class="form-group">
                    <label>Gambar Saat Ini</label>
                    <img src="{{ asset('storage/'.$voucher->image) }}"
                         class="voucher-preview">
                </div>
            @endif

            <div class="form-group">
                <label>Ganti Gambar Voucher</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Jenis Voucher</label>
                    <select name="type" required>
                        <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>
                            Potongan Nominal
                        </option>

                        <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>
                            Diskon Persentase
                        </option>

                        <option value="free_delivery" {{ $voucher->type == 'free_delivery' ? 'selected' : '' }}>
                            Gratis Ongkir
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Berlaku Untuk</label>
                    <select name="service_type" required>
                        <option value="all" {{ ($voucher->service_type ?? 'all') == 'all' ? 'selected' : '' }}>
                            Semua Layanan
                        </option>

                        <option value="food" {{ ($voucher->service_type ?? '') == 'food' ? 'selected' : '' }}>
                            Food / Makanan
                        </option>

                        <option value="ojek" {{ ($voucher->service_type ?? '') == 'ojek' ? 'selected' : '' }}>
                            Ojek
                        </option>

                        <option value="car" {{ ($voucher->service_type ?? '') == 'car' ? 'selected' : '' }}>
                            Mobil / J-Car
                        </option>
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Nilai Voucher</label>
                    <input type="number" name="value" value="{{ $voucher->value ?? 0 }}" required>
                </div>

                <div class="form-group">
                    <label>Maksimum Diskon</label>
                    <input type="number" name="maximum_discount" value="{{ $voucher->maximum_discount ?? 0 }}">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Minimum Order</label>
                    <input type="number" name="minimum_order" value="{{ $voucher->minimum_order ?? 0 }}">
                </div>

                <div class="form-group">
                    <label>Kuota</label>
                    <input type="number" name="quota" value="{{ $voucher->quota ?? 0 }}">
                </div>
            </div>

            <div class="checkbox-group">
                <label class="checkbox-item">
                    <input type="checkbox"
                           name="is_new_user_only"
                           value="1"
                           {{ $voucher->is_new_user_only ? 'checked' : '' }}>
                    <span>Khusus Pengguna Baru</span>
                </label>

                <label class="checkbox-item">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ $voucher->is_active ? 'checked' : '' }}>
                    <span>Voucher Aktif</span>
                </label>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $voucher->start_date }}">
                </div>

                <div class="form-group">
                    <label>Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ $voucher->end_date }}">
                </div>
            </div>

            <div class="button-row">
                <button type="submit" class="btn-save">
                    💾 Update Voucher
                </button>

                <a href="/admin/vouchers" class="btn-back">
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>

<style>
.voucher-page{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.voucher-card{
    background:white;
    border-radius:24px;
    padding:20px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.voucher-card h2{
    margin:0 0 20px;
    color:var(--primary, #f97316);
    font-weight:900;
}

.form-group{
    margin-bottom:16px;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    color:#374151;
    font-weight:800;
}

.form-group input,
.form-group select{
    width:100%;
    padding:12px;
    border:1px solid #e5e7eb;
    border-radius:14px;
}

.voucher-preview{
    width:140px;
    height:90px;
    object-fit:cover;
    border-radius:16px;
    box-shadow:0 8px 20px rgba(15,23,42,.15);
}

.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.checkbox-group{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-bottom:16px;
}

.checkbox-item{
    display:flex;
    align-items:center;
    gap:10px;
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:12px 14px;
    cursor:pointer;
    font-weight:700;
}

.checkbox-item input{
    width:18px !important;
    height:18px !important;
    margin:0;
    flex-shrink:0;
}

.checkbox-item span{
    color:#374151;
    font-size:14px;
}

.button-row{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn-save,
.btn-back{
    border:none;
    padding:14px 18px;
    border-radius:14px;
    font-weight:900;
    cursor:pointer;
    text-decoration:none;
}

.btn-save{
    background:linear-gradient(135deg,var(--primary, #f97316),var(--secondary, #fb923c));
    color:white;
}

.btn-back{
    background:#e5e7eb;
    color:#374151;
}

@media(max-width:700px){
    .grid-2{
        grid-template-columns:1fr;
    }

    .checkbox-group{
        flex-direction:column;
    }

    .button-row a,
    .button-row button{
        width:100%;
        text-align:center;
    }
}
</style>

@endsection