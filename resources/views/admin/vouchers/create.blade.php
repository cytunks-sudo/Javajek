@extends('layouts.admin')

@section('content')

<div class="voucher-page">

    <div class="voucher-card">

        <h2>🎁 Tambah Voucher</h2>

        <form method="POST" action="/admin/vouchers/store" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Kode Voucher</label>
                <input type="text" name="code" placeholder="Contoh: FOOD10" required>
            </div>

            <div class="form-group">
                <label>Nama Voucher</label>
                <input type="text" name="name" placeholder="Contoh: Diskon Makanan" required>
            </div>

            <div class="form-group">
                <label>Gambar Voucher</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Jenis Voucher</label>
                    <select name="type" required>
                        <option value="fixed">Potongan Nominal</option>
                        <option value="percent">Diskon Persentase</option>
                        <option value="free_delivery">Gratis Ongkir</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Berlaku Untuk</label>
                    <select name="service_type" required>
                        <option value="all">Semua Layanan</option>
                        <option value="food">Food / Makanan</option>
                        <option value="ojek">Ojek</option>
                        <option value="car">Mobil / J-Car</option>
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Nilai Voucher</label>
                    <input type="number" name="value" value="0" required>
                </div>

                <div class="form-group">
                    <label>Maksimum Diskon</label>
                    <input type="number" name="maximum_discount" value="0">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Minimum Order</label>
                    <input type="number" name="minimum_order" value="0">
                </div>

                <div class="form-group">
                    <label>Kuota</label>
                    <input type="number" name="quota" value="100">
                </div>
            </div>

            <div class="checkbox-group">
                <label class="checkbox-item">
                    <input type="checkbox" name="is_new_user_only" value="1">
                    <span>Khusus Pengguna Baru</span>
                </label>

                <label class="checkbox-item">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span>Voucher Aktif</span>
                </label>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date">
                </div>

                <div class="form-group">
                    <label>Tanggal Berakhir</label>
                    <input type="date" name="end_date">
                </div>
            </div>

            <button type="submit" class="btn-save">
                💾 Simpan Voucher
            </button>

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

.btn-save{
    width:100%;
    border:none;
    background:linear-gradient(135deg,var(--primary, #f97316),var(--secondary, #fb923c));
    color:white;
    padding:14px;
    border-radius:14px;
    font-weight:900;
    cursor:pointer;
}

@media(max-width:700px){
    .grid-2{
        grid-template-columns:1fr;
    }

    .checkbox-group{
        flex-direction:column;
    }
}
</style>

@endsection