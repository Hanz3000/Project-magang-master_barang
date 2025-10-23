@extends('layouts.app')

@section('content')
<style>
/* Hilangkan scroll horizontal halaman */
html, body {
    overflow-x: hidden;
}
</style>
<div class="p-4 sm:p-6 overflow-x-hidden">
    <div class="flex flex-col gap-4 mb-6 items-center">
        <h1 class="text-2xl font-bold text-gray-800 text-center break-words w-full max-w-md">
            Selamat Datang di Sistem Master Data
        </h1>
        <!-- Filter Waktu -->
        <div class="flex justify-center w-full">
            <form action="{{ route('dashboard.filter') }}" method="GET" class="flex gap-2 w-full max-w-xs">
                <select name="time" id="timeFilter" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="today" {{ (isset($time) && $time == 'today') ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ (isset($time) && $time == 'week') ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ (isset($time) && $time == 'month') ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="all" {{ (isset($time) && $time == 'all') ? 'selected' : '' }}>Semua Data</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Barang -->
        <div class="bg-white shadow rounded-xl p-5 w-full max-w-md mx-auto transition-all hover:shadow-lg">
            <div class="flex justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-700">Total Barang</h2>
                <a href="{{ route('barang.index') }}" class="text-blue-500 text-sm hover:text-blue-700">Lihat Detail</a>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $totalBarang }}</div>
                <div class="text-sm text-gray-500">
                    @if($lastBarangUpdate)
                        Diperbarui {{ $lastBarangUpdate->diffForHumans() }}
                    @else
                        Belum ada data
                    @endif
                </div>
            </div>
            <div class="mt-3 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-500" style="width: 100%"></div>
            </div>
        </div>

        <!-- Total Pegawai -->
        <div class="bg-white shadow rounded-xl p-5 w-full max-w-md mx-auto transition-all hover:shadow-lg">
            <div class="flex justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-700">Total Pegawai</h2>
                <a href="{{ route('pegawai.index') }}" class="text-blue-500 text-sm hover:text-blue-700">Lihat Detail</a>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $totalPegawai }}</div>
                <div class="text-sm text-gray-500">
                    @if($lastPegawaiUpdate)
                        Diperbarui {{ $lastPegawaiUpdate->diffForHumans() }}
                    @else
                        Belum ada data
                    @endif
                </div>
            </div>
            <div class="mt-3 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Barang Terbaru -->
        <div class="bg-white shadow rounded-xl p-4 w-full max-w-md mx-auto transition-all hover:shadow-lg">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-600">Barang Terbaru</h4>
                <span class="text-xs text-gray-500">Terakhir ditambahkan</span>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse ($recentBarang as $barang)
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $barang->nama_barang }}</p>
                        <p class="text-xs text-gray-500">Kode: {{ $barang->kode_barang }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($barang->status === 'masuk')
                                Ditambahkan {{ $barang->created_at->diffForHumans() }}
                            @elseif($barang->status === 'keluar')
                                Dikeluarkan {{ $barang->updated_at->diffForHumans() }}
                            @else
                                {{ $barang->created_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $barang->jumlah > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        Stok: {{ $barang->jumlah }}
                    </span>
                </li>
                @empty
                <li class="py-3 text-gray-500 text-sm">Tidak ada data barang terbaru.</li>
                @endforelse
            </ul>
        </div>

        <!-- Pegawai Terbaru -->
        <div class="bg-white shadow rounded-xl p-4 w-full max-w-md mx-auto transition-all hover:shadow-lg">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-600">Pegawai Terbaru</h4>
                <span class="text-xs text-gray-500">Terakhir ditambahkan</span>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse ($recentPegawai as $pegawai)
                <li class="py-3">
                    <p class="text-sm font-medium text-gray-800">{{ $pegawai->nama }}</p>
                    <p class="text-xs text-gray-500">NIP: {{ $pegawai->nip }}</p>
                    <p class="text-xs text-gray-500 mt-1">Bergabung {{ $pegawai->created_at->diffForHumans() }}</p>
                </li>
                @empty
                <li class="py-3 text-gray-500 text-sm">Tidak ada data pegawai terbaru.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
