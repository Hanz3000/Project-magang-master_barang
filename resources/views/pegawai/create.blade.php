@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
        Tambah Pegawai
    </h2>

    <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
            <input type="text" name="nama" id="nama"
                value="{{ old('nama') }}"
                placeholder="Contoh: John Doe"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                required>
        </div>

        <div>
            <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
            <input type="text" name="nip" id="nip"
                value="{{ old('nip') }}"
                placeholder="Contoh: 12345678"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                required>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('pegawai.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Batal
            </a>
            <button type="submit" name="action" value="save_and_continue"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Tambah dan Lanjut
            </button>
            <button type="submit" name="action" value="save"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan
            </button>
        </div>
    </form>
</div>
@if ($errors->has('nip'))
    <div class="text-red-600 text-sm mt-1">
        {{ $errors->first('nip') }}
    </div>
@endif

@if(session('success') && request()->has('action') && request()->input('action') === 'save_and_continue')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Clear form fields
        document.getElementById('nama').value = '';
        document.getElementById('nip').value = '';

        // Focus on first field
        document.getElementById('nama').focus();

        // Scroll to top to see success message
        window.scrollTo(0, 0);
    });
</script>
@endif
@endsection