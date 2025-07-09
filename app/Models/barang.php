<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'master_barangs';

    // app/Models/Barang.php
    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'jumlah', // <- harus ada ini
    ];
}
