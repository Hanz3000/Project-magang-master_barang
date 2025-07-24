<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'nip', 'divisi_id'];

    /**
     * Relasi: Pegawai memiliki banyak Pengeluaran
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function division()
{
    return $this->belongsTo(Division::class, 'divisi_id');
}

}
