<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Di sini kita set default guard ke "admin", karena sistem login hanya
    | menggunakan tabel "admins". Jadi Auth::attempt() otomatis pakai admin.
    |
    */

    'defaults' => [
        'guard' => 'admin',
        'passwords' => 'admins',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Kita hanya butuh dua guard: "web" (bawaan Laravel) dan "admin".
    | Guard admin ini yang akan dipakai untuk proses login.
    |
    */

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Provider menentukan model yang dipakai untuk autentikasi.
    | Karena login kamu pakai tabel "admins", maka modelnya Admin::class.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk reset password. Jika hanya pakai admin,
    | maka provider default-nya diarahkan ke "admins".
    |
    */

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout untuk konfirmasi ulang password (dalam detik).
    | Default: 3 jam (10800 detik).
    |
    */

    'password_timeout' => 10800,

];
