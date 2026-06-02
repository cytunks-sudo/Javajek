<?php

return [

    'required' => ':attribute wajib diisi.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'unique' => ':attribute sudah digunakan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'min' => [
        'string' => ':attribute minimal :min karakter.',
    ],
    'max' => [
        'string' => ':attribute maksimal :max karakter.',
    ],

    'attributes' => [
        'name' => 'Nama Lengkap',
        'username' => 'Username',
        'email' => 'Email',
        'phone' => 'Nomor HP',
        'address' => 'Alamat',
        'password' => 'Password',
    ],

];