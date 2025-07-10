@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
        </svg>
        Edit Barang
    </h2>

    {{-- Error Validasi --}}
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded mb-6">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang"
                value="{{ old('nama_barang', $barang->nama_barang) }}"
                placeholder="Contoh: Bolpoin Hitam"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang (otomatis diubah)</label>
            <input type="text" id="kode_barang" value="{{ $barang->kode_barang }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600" readonly>
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('barang.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
