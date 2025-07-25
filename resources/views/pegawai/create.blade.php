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
        <div>
            <label for="divisi" class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
            <div class="flex gap-2">
                <select name="divisi" id="divisi" required
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                    <option value="" disabled selected>Pilih Divisi</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->name }}" {{ old('divisi') == $division->name ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" onclick="openDivisiModal()"
                    class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Kelola Divisi
                </button>
            </div>
            @error('divisi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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

<!-- Modal Kelola Divisi -->
<div id="divisiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Kelola Divisi</h3>
                        <div class="mt-4">
                            <!-- Search Divisi -->
                            <div class="relative mb-4">
                                <input type="text" id="searchDivisi" placeholder="Cari divisi..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                            </div>
                            
                            <!-- Daftar Divisi -->
                            <div class="max-h-60 overflow-y-auto mb-4">
                                <ul id="divisiList" class="divide-y divide-gray-200">
                                    @foreach($divisions as $division)
                                        <li class="py-2 flex justify-between items-center">
                                            <span>{{ $division->name }}</span>
                                            <div class="flex gap-2">
                                                <button type="button" onclick="editDivisi('{{ $division->id }}', '{{ $division->name }}')"
                                                    class="text-blue-500 hover:text-blue-700">
                                                    Edit
                                                </button>
                                                <button type="button" onclick="confirmDeleteDivisi('{{ $division->id }}')"
                                                    class="text-red-500 hover:text-red-700">
                                                    Hapus
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <!-- Tambah Divisi Baru -->
                            <div class="mt-4">
                                <label for="newDivisiName" class="block text-sm font-medium text-gray-700 mb-1">Divisi Baru</label>
                                <div class="flex gap-2">
                                    <input type="text" id="newDivisiName" placeholder="Nama divisi baru"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                                    <button type="button" onclick="addNewDivisi()"
                                        class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeDivisiModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Divisi -->
<div id="editDivisiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Divisi</h3>
                        <div class="mt-4">
                            <input type="hidden" id="editDivisiId">
                            <input type="text" id="editDivisiName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="updateDivisi()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan
                </button>
                <button type="button" onclick="closeEditDivisiModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Divisi -->
<div id="deleteDivisiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus Divisi</h3>
                        <div class="mt-4">
                            <p>Apakah Anda yakin ingin menghapus divisi ini?</p>
                            <input type="hidden" id="deleteDivisiId">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="deleteDivisi()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Hapus
                </button>
                <button type="button" onclick="closeDeleteDivisiModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk modal divisi
    function openDivisiModal() {
        document.getElementById('divisiModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        document.getElementById('searchDivisi').focus();
    }

    function closeDivisiModal() {
        document.getElementById('divisiModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Fungsi untuk modal edit divisi
    function editDivisi(id, name) {
        document.getElementById('editDivisiId').value = id;
        document.getElementById('editDivisiName').value = name;
        document.getElementById('editDivisiModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditDivisiModal() {
        document.getElementById('editDivisiModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Fungsi untuk modal hapus divisi
    function confirmDeleteDivisi(id) {
        document.getElementById('deleteDivisiId').value = id;
        document.getElementById('deleteDivisiModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteDivisiModal() {
        document.getElementById('deleteDivisiModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Fungsi CRUD divisi
    function addNewDivisi() {
        const name = document.getElementById('newDivisiName').value.trim();
        if (!name) return;

        fetch("{{ route('divisions.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tambahkan ke select box
                const select = document.getElementById('divisi');
                const option = document.createElement('option');
                option.value = data.divisi.name;
                option.text = data.divisi.name;
                select.appendChild(option);
                option.selected = true;

                // Tambahkan ke daftar
                const listItem = document.createElement('li');
                listItem.className = 'py-2 flex justify-between items-center';
                listItem.innerHTML = `
                    <span>${data.divisi.name}</span>
                    <div class="flex gap-2">
                        <button type="button" onclick="editDivisi('${data.divisi.id}', '${data.divisi.name}')"
                            class="text-blue-500 hover:text-blue-700">
                            Edit
                        </button>
                        <button type="button" onclick="confirmDeleteDivisi('${data.divisi.id}')"
                            class="text-red-500 hover:text-red-700">
                            Hapus
                        </button>
                    </div>
                `;
                document.getElementById('divisiList').appendChild(listItem);

                // Reset input
                document.getElementById('newDivisiName').value = '';
                
                // Refresh halaman setelah 1 detik
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateDivisi() {
        const id = document.getElementById('editDivisiId').value;
        const name = document.getElementById('editDivisiName').value.trim();
        if (!name) return;

        fetch(`/divisions/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify({ name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh halaman setelah update
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function deleteDivisi() {
        const id = document.getElementById('deleteDivisiId').value;

        fetch(`/divisions/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'DELETE'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh halaman setelah delete
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Pencarian divisi
    document.getElementById('searchDivisi').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const listItems = document.querySelectorAll('#divisiList li');
        
        listItems.forEach(item => {
            const name = item.querySelector('span').textContent.toLowerCase();
            if (name.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Handle enter key on new divisi input
    document.getElementById('newDivisiName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addNewDivisi();
        }
    });

    // Handle enter key on edit divisi input
    document.getElementById('editDivisiName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            updateDivisi();
        }
    });
</script>
@endsection