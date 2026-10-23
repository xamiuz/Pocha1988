<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Master Admin Password
    |--------------------------------------------------------------------------
    |
    | Password rahasia untuk membuka akses ke seluruh dashboard admin,
    | arsip transaksi pelanggan, manajemen menu, dan menu promo.
    | Anda dapat mengubah nilai ini sewaktu-waktu di file .env melalui ADMIN_PASSWORD.
    |
    */
    'password' => env('ADMIN_PASSWORD', 'admin1988'),
];
