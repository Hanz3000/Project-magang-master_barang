@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang di Sistem Master Data</h1>

        <!-- Time Filter -->
        <div class="flex space-x-2">
            <select id="timeFilter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="today">Hari Ini</option>
                <option value="week">Minggu Ini</option>
                <option value="month">Bulan Ini</option>
                <option value="all">Semua Data</option>
            </select>
            <button onclick="applyFilter()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Filter
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Barang -->
        <div class="bg-white shadow rounded-xl p-5 transition-all hover:shadow-lg">
            <div class="flex justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-700">Total Barang</h2>
                <a href="{{ route('barang.index') }}" class="text-blue-500 text-sm hover:text-blue-700">Lihat Detail</a>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $totalBarang }}</div>
                <div class="text-sm text-gray-500" id="barangUpdateInfo">
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
        <div class="bg-white shadow rounded-xl p-5 transition-all hover:shadow-lg">
            <div class="flex justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-700">Total Pegawai</h2>
                <a href="{{ route('pegawai.index') }}" class="text-blue-500 text-sm hover:text-blue-700">Lihat Detail</a>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $totalPegawai }}</div>
                <div class="text-sm text-gray-500" id="pegawaiUpdateInfo">
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
        <div class="bg-white shadow rounded-xl p-4 transition-all hover:shadow-lg">
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
        <div class="bg-white shadow rounded-xl p-4 transition-all hover:shadow-lg">
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

<script>
    function applyFilter() {
        const filterValue = document.getElementById('timeFilter').value;
        window.location.href = `/dashboard/filter/${filterValue}`;
    }
</script>
@endsection
