@extends('layouts.admin')

@section('content')

<div class="food-create-page">

    <div class="page-header">

        <div>
            <h2>🍔 Tambah Menu Makanan</h2>
            <p>Tambahkan menu baru ke restoran JavaJek Food.</p>
        </div>

        <a href="/foods" class="btn-back">
            ← Kembali
        </a>

    </div>

    <div class="form-card">

        <form method="POST"
              action="/foods/store"
              enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label>🏪 Restoran</label>

                <select name="restaurant_id" class="form-control" required>
                    <option value="">
                        Pilih Restoran
                    </option>

                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}">
                            {{ $restaurant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>🍔 Nama Menu</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Contoh: Ayam Geprek Sambal Ijo"
                       required>
            </div>

            <div class="form-group">
                <label>📝 Deskripsi</label>

                <textarea
                    name="description"
                    class="form-control textarea"
                    placeholder="Deskripsi menu makanan..."
                ></textarea>
            </div>

            <div class="form-group">
                <label>💰 Harga</label>

                <input type="number"
                       name="price"
                       class="form-control"
                       placeholder="15000"
                       required>
            </div>

            <div class="form-group">
                <label>📸 Foto Menu</label>

                <input type="file"
                       name="photo"
                       id="photoInput"
                       accept="image/*"
                       class="form-control">

                <div class="preview-box">
                    <img id="previewImage">
                </div>
            </div>

            <div class="form-group">
                <label>📦 Status</label>

                <select name="status" class="form-control">
                    <option value="available">
                        Aktif
                    </option>

                    <option value="unavailable">
                        Nonaktif
                    </option>
                </select>
            </div>

            <button type="submit" class="btn-save">
                💾 Simpan Menu
            </button>

        </form>

    </div>

</div>

<style>

.food-create-page{
    width:100%;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

.page-header h2{
    margin:0;
    font-size:30px;
    color:#ea580c;
    font-weight:900;
}

.page-header p{
    margin-top:6px;
    color:#6b7280;
}

.btn-back{
    text-decoration:none;
    background:#e5e7eb;
    color:#111827;
    padding:12px 18px;
    border-radius:14px;
    font-weight:800;
}

.form-card{
    background:white;
    border-radius:24px;
    padding:25px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#ea580c;
    font-weight:900;
}

.form-control{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:14px;
    outline:none;
    font-size:14px;
}

.form-control:focus{
    border-color:#fb923c;
}

.textarea{
    min-height:120px;
    resize:vertical;
}

.preview-box{
    margin-top:15px;
}

.preview-box img{
    width:220px;
    height:220px;
    object-fit:cover;
    border-radius:18px;
    display:none;
    border:3px solid #f3f4f6;
}

.btn-save{
    width:100%;
    border:none;
    cursor:pointer;
    padding:16px;
    border-radius:16px;
    color:white;
    font-size:15px;
    font-weight:900;

    background:linear-gradient(
        135deg,
        #f97316,
        #fb923c
    );
}

.btn-save:hover{
    opacity:.95;
}

@media(max-width:768px){

    .page-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .btn-back{
        width:100%;
        text-align:center;
    }

}

</style>

<script>

document.getElementById('photoInput')
.addEventListener('change', function(e){

    let file = e.target.files[0];

    if(!file) return;

    let reader = new FileReader();

    reader.onload = function(event){

        let img = document.getElementById('previewImage');

        img.src = event.target.result;
        img.style.display = 'block';
    }

    reader.readAsDataURL(file);

});

</script>

@endsection