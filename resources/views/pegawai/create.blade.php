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

    {{-- NAMA --}}
    <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
            placeholder="Contoh: John Doe"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
        @error('nama') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- NIP --}}
    <div>
        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
        <input type="text" name="nip" id="nip" value="{{ old('nip') }}" maxlength="8" required
            placeholder="Contoh: 12345678"
            pattern="\d{8}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
        <p class="text-xs text-gray-500 mt-1">NIP harus 8 digit angka</p>
        @error('nip') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- DIVISI --}}
    <div class="relative">
        <label for="divisi" class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
        <div class="flex">
            <select name="divisi" id="divisi"
                class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring focus:ring-blue-500"
                onchange="checkDivisiSelection()" required>
                <option value="" disabled selected>Pilih Divisi</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->name }}" {{ old('divisi') == $division->name ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                @endforeach
                @if(old('divisi') && !in_array(old('divisi'), $divisions->pluck('name')->toArray()))
                    <option value="{{ old('divisi') }}" selected>{{ old('divisi') }}</option>
                @endif
            </select>
            <button type="button" onclick="showNewDivisiInput()"
                class="px-3 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:ring-2 focus:ring-offset-2">
                +
            </button>
        </div>
        @error('divisi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Tambah Divisi Baru --}}
    <div id="newDivisiContainer" class="hidden">
        <label for="new_divisi" class="block text-sm font-medium text-gray-700 mb-1">Divisi Baru</label>
        <input type="text" name="new_divisi" id="new_divisi"
            placeholder="Masukkan nama divisi baru"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-green-500">
    </div>

    {{-- EMAIL --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required
            placeholder="email@example.com"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- PASSWORD --}}
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" id="password" required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- KONFIRMASI PASSWORD --}}
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
    </div>

    {{-- AKSI --}}
    <div class="flex justify-end gap-3 pt-4">
        <a href="{{ route('pegawai.index') }}"
            class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">Batal</a>
        <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Simpan</button>
    </div>
</form>



</div>

@if ($errors->any())
    <div class="max-w-lg mx-auto mt-4">
        @foreach ($errors->all() as $error)
            <div class="text-red-600 text-sm mt-1">{{ $error }}</div>
        @endforeach
    </div>
@endif

@if(session('success') && request()->input('action') === 'save_and_continue')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ['nama', 'nip', 'divisi', 'new_divisi'].forEach(id => {
            const input = document.getElementById(id);
            if (input) input.value = '';
        });

        document.getElementById('newDivisiContainer').classList.add('hidden');
        document.getElementById('nama').focus();
        window.scrollTo(0, 0);
    });
</script>
@endif

<script>
    function tambahDivisi() {
        document.getElementById('newDivisiContainer').classList.remove('hidden');
        document.getElementById('divisi').removeAttribute('required');
        document.getElementById('new_divisi').setAttribute('required', 'required');
        document.getElementById('new_divisi').focus();
    }

    function simpanDivisiBaru() {
        const newDivisiInput = document.getElementById('new_divisi');
        const newDivisi = newDivisiInput.value.trim();
        if (newDivisi) {
            const select = document.getElementById('divisi');
            let exists = Array.from(select.options).some(opt => opt.value === newDivisi);
            if (!exists) {
                const opt = document.createElement('option');
                opt.value = newDivisi;
                opt.text = newDivisi;
                opt.selected = true;
                select.appendChild(opt);
            } else {
                Array.from(select.options).forEach(opt => {
                    if (opt.value === newDivisi) opt.selected = true;
                });
            }

            document.getElementById('newDivisiContainer').classList.add('hidden');
            newDivisiInput.value = '';
            document.getElementById('divisi').setAttribute('required', 'required');
            newDivisiInput.removeAttribute('required');
        } else {
            alert('Nama divisi baru tidak boleh kosong.');
        }
    }

    document.getElementById('new_divisi').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            simpanDivisiBaru();
        }
    });

    function checkDivisiSelection() {
        const divisi = document.getElementById('divisi');
        if (divisi.value !== '') {
            document.getElementById('newDivisiContainer').classList.add('hidden');
            document.getElementById('new_divisi').value = '';
            document.getElementById('new_divisi').removeAttribute('required');
        }
    }

    function showNewDivisiInput() {
        document.getElementById('newDivisiContainer').classList.remove('hidden');
        document.getElementById('new_divisi').required = true;
    }
</script>
@endsection