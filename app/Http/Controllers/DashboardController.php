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

        $lastBarangUpdate = Barang::latest('updated_at')->first()->updated_at ?? null;
        $lastPegawaiUpdate = Pegawai::latest('updated_at')->first()->updated_at ?? null;

        $time = 'all'; // default saat pertama buka dashboard

        return view('dashboard', compact(
            'totalBarang',
            'totalPegawai',
            'recentBarang',
            'recentPegawai',
            'lastBarangUpdate',
            'lastPegawaiUpdate',
            'time'
        ));
    }

    public function filter(Request $request)
    {
        $time = $request->query('time', 'all'); // default ke 'all' jika tidak ada

        $queryBarang = Barang::query();
        $queryPegawai = Pegawai::query();

        if ($time == 'today') {
            $queryBarang->whereDate('updated_at', Carbon::today());
            $queryPegawai->whereDate('updated_at', Carbon::today());
        } elseif ($time == 'week') {
            $queryBarang->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $queryPegawai->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($time == 'month') {
            $queryBarang->whereMonth('updated_at', Carbon::now()->month);
            $queryPegawai->whereMonth('updated_at', Carbon::now()->month);
        }
        // Jika 'all' tidak ada filter tambahan

        $totalBarang = $queryBarang->count();
        $totalPegawai = $queryPegawai->count();

        $recentBarang = $queryBarang->latest()->take(5)->get();
        $recentPegawai = $queryPegawai->latest()->take(5)->get();

        $lastBarangUpdate = $queryBarang->latest('updated_at')->first()->updated_at ?? null;
        $lastPegawaiUpdate = $queryPegawai->latest('updated_at')->first()->updated_at ?? null;

        return view('dashboard', compact(
            'totalBarang',
            'totalPegawai',
            'recentBarang',
            'recentPegawai',
            'lastBarangUpdate',
            'lastPegawaiUpdate',
            'time'
        ));
    }

    public function filterMonth()
    {
        $time = 'month';

        $queryBarang = Barang::query();
        $queryPegawai = Pegawai::query();

        $queryBarang->whereMonth('updated_at', Carbon::now()->month);
        $queryPegawai->whereMonth('updated_at', Carbon::now()->month);

        $totalBarang = $queryBarang->count();
        $totalPegawai = $queryPegawai->count();

        $recentBarang = $queryBarang->latest()->take(5)->get();
        $recentPegawai = $queryPegawai->latest()->take(5)->get();

        $lastBarangUpdate = $queryBarang->latest('updated_at')->first()->updated_at ?? null;
        $lastPegawaiUpdate = $queryPegawai->latest('updated_at')->first()->updated_at ?? null;

        return view('dashboard', compact(
            'totalBarang',
            'totalPegawai',
            'recentBarang',
            'recentPegawai',
            'lastBarangUpdate',
            'lastPegawaiUpdate',
            'time'
        ));
    }
}
