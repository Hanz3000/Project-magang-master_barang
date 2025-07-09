@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 shadow-md rounded-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Barang</h2>

    @if (session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('barang.store') }}" method="POST" class="space-y-4" autocomplete="off">
        @csrf
        <div>
            <label for="nama_barang" class="block font-semibold mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang"
                class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:border-blue-400"
                placeholder="Contoh: Buku Tulis"
                value="{{ old('nama_barang') }}" required
                oncopy="return false" oncut="return false" onpaste="return false">
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('barang.index') }}"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
                Batal
            </a>
            <button type="submit" name="action" value="save_and_continue"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Tambah dan Lanjut
            </button>
            <button type="submit" name="action" value="save"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Simpan
            </button>
        </div>

    </form>
</div>
@endsection