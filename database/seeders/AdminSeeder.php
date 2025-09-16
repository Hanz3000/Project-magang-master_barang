<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@123.com',
            'password' => Hash::make('admin123'), // ubah sesuai kebutuhan
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
