<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins'; // pastikan pakai tabel admins

    protected $fillable = [
        'name',
        'nip',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
