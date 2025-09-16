<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'name',
        'nip',
        'password',
    ];

    /**
     * Kolom yang disembunyikan
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * ✅ Relasi: User memiliki satu Pegawai
     */
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class);
    }

    /**
     * ✅ Override untuk autentikasi pakai NIP
     */
    public function getAuthIdentifierName()
    {
        return 'nip';
    }
}
