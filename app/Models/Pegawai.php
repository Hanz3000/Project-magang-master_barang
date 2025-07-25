<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // ✅ Tambahkan 'user_id' di sini agar bisa disimpan
    protected $fillable = ['nama', 'nip', 'divisi_id', 'user_id'];

    /**
     * Relasi: Pegawai milik satu divisi
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'divisi_id');
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
