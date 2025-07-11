<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalPegawai = Pegawai::count();

        $recentBarang = Barang::latest()->take(5)->get();
        $recentPegawai = Pegawai::latest()->take(5)->get();

        $lastBarang = Barang::latest('updated_at')->first();
        $lastPegawai = Pegawai::latest('updated_at')->first();

        $lastBarangUpdate = $lastBarang ? $lastBarang->updated_at : null;
        $lastPegawaiUpdate = $lastPegawai ? $lastPegawai->updated_at : null;

        return view('dashboard', compact(
            'totalBarang',
            'totalPegawai',
            'recentBarang',
            'recentPegawai',
            'lastBarangUpdate',
            'lastPegawaiUpdate'
        ));
    }

    public function filter($time)
    {
        $queryBarang = Barang::query();
        $queryPegawai = Pegawai::query();

        // Filter berdasarkan waktu
        if ($time === 'today') {
            $queryBarang->whereDate('updated_at', Carbon::today());
            $queryPegawai->whereDate('updated_at', Carbon::today());
        } elseif ($time === 'week') {
            $queryBarang->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $queryPegawai->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($time === 'month') {
            $queryBarang->whereMonth('updated_at', Carbon::now()->month);
            $queryPegawai->whereMonth('updated_at', Carbon::now()->month);
        }
        // Jika 'all', tidak ada filter tambahan

        // Filter jenis transaksi jika request memiliki parameter 'jenis'
        if (request()->has('jenis') && request()->jenis != '') {
            $queryBarang->where('status', request()->jenis);
        }

        $totalBarang = $queryBarang->count();
        $totalPegawai = $queryPegawai->count();

        $recentBarang = $queryBarang->latest()->take(5)->get();
        $recentPegawai = $queryPegawai->latest()->take(5)->get();

        $lastBarang = $queryBarang->latest('updated_at')->first();
        $lastPegawai = $queryPegawai->latest('updated_at')->first();

        $lastBarangUpdate = $lastBarang ? $lastBarang->updated_at : null;
        $lastPegawaiUpdate = $lastPegawai ? $lastPegawai->updated_at : null;

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
