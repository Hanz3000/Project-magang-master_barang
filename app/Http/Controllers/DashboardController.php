<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Barang;
use Illuminate\Pagination\LengthAwarePaginator;



use App\Models\Pegawai;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalPegawai = Pegawai::count();
        $recentBarang = Barang::latest()->take(5)->get();
        $recentPegawai = Pegawai::latest()->take(5)->get();

        // Add these lines to get last update timestamps
        $lastBarangUpdate = Barang::latest()->first()->created_at ?? null;
        $lastPegawaiUpdate = Pegawai::latest()->first()->created_at ?? null;

        return view('dashboard', compact(
            'totalBarang',
            'totalPegawai',
            'recentBarang',
            'recentPegawai',
            'lastBarangUpdate',
            'lastPegawaiUpdate'
        ));
    }
}
