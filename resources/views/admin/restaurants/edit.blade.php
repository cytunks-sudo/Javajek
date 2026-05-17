<h1>Edit Restoran</h1>

<form method="POST" action="/restaurants/{{ $restaurant->id }}/update">
    @csrf

    <p>Nama Restoran</p>
    <input type="text" name="name" value="{{ $restaurant->name }}">

    <p>Alamat</p>
    <textarea name="address">{{ $restaurant->address }}</textarea>

    <p>No HP</p>
    <input type="text" name="phone" value="{{ $restaurant->phone }}">

    <br><br>

    <button type="submit">Update</button>
</form>