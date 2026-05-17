<h1>Tambah Restoran</h1>

<form method="POST" action="/restaurants/store">
    @csrf

    <p>Nama Restoran</p>
    <input type="text" name="name">

    <p>Alamat</p>
    <textarea name="address"></textarea>

    <p>No HP</p>
    <input type="text" name="phone">

    <br><br>

    <button type="submit">Simpan</button>
</form>