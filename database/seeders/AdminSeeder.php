<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel admins.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Satria Sheva',
            'email' => 'satriasheva@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
