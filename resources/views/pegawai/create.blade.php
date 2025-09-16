@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
        Tambah Pegawai
    </h2>

    <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-4" id="employeeForm">
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

        {{-- PASSWORD --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="flex gap-2 mb-2">
                <input type="text" name="password" id="password" readonly
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500"
                    placeholder="Klik 'Buat Password' untuk mengisi">
                <button type="button" id="generatePasswordBtn"
                    class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    Buat Password
                </button>
                <button type="button" id="copyPasswordBtn"
                    class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Salin
                </button>
            </div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="text" name="password_confirmation" id="password_confirmation" readonly
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500"
                placeholder="Klik 'Buat Password' untuk mengisi">
            <p class="text-xs text-gray-500 mt-1">Format: <code>PW-<em>4digitNIP</em>-<em>3char</em></code>. Rapi & mirip antar NIP yang berurutan.</p>
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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
                                        <li class="py-2 flex justify-between items-center" data-id="{{ $division->id }}">
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

<!-- Modal Error -->
<div id="errorModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-red-50 px-4 py-3 sm:px-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <h3 class="text-sm font-medium text-red-800">Peringatan</h3>
            </div>
            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <p class="text-sm text-gray-700" id="errorMessage"></p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeErrorModal()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal functions
    function openDivisiModal() {
        document.getElementById('divisiModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        document.getElementById('searchDivisi').focus();
    }

    function closeDivisiModal() {
        document.getElementById('divisiModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

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

    function confirmDeleteDivisi(id) {
        document.getElementById('deleteDivisiId').value = id;
        document.getElementById('deleteDivisiModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteDivisiModal() {
        document.getElementById('deleteDivisiModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Password generation
    const nipInput = document.getElementById('nip');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const generateBtn = document.getElementById('generatePasswordBtn');
    const copyBtn = document.getElementById('copyPasswordBtn');

    async function sha256Hex(text) {
        const enc = new TextEncoder();
        const data = enc.encode(text);
        const hashBuffer = await crypto.subtle.digest('SHA-256', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        const hex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        return hex;
    }

    function pickCharsFromHex(hex, count = 3) {
        const charset = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        let res = '';
        for (let i = 0; i < count; i++) {
            const idxHex = hex.slice(i * 2, i * 2 + 2);
            const val = parseInt(idxHex || '0', 16);
            res += charset[val % charset.length];
        }
        return res;
    }

    async function generatePasswordFromNip(nip) {
        if (!nip || nip.length !== 8) return '';
        const pepper = 'APP_PEPPER';
        const hex = await sha256Hex(nip + '|' + pepper);
        const nip4 = nip.slice(-4).padStart(4, '0');
        const suffix = pickCharsFromHex(hex, 3);
        return `PW-${nip4}-${suffix}`;
    }

    generateBtn.addEventListener('click', async function() {
        const nip = nipInput.value.trim();
        if (!nip || nip.length !== 8) {
            showErrorMessage('NIP harus 8 digit angka.');
            nipInput.focus();
            return;
        }
        const pwd = await generatePasswordFromNip(nip);
        passwordInput.value = pwd;
        passwordConfirmationInput.value = pwd; // Sinkronkan dengan konfirmasi
    });

    copyBtn.addEventListener('click', function() {
        const val = passwordInput.value.trim();
        if (!val) {
            showErrorMessage('Belum ada password untuk disalin. Tekan "Buat Password" terlebih dahulu.');
            return;
        }
        navigator.clipboard.writeText(val).then(() => {
            copyBtn.textContent = 'Tersalin';
            setTimeout(() => copyBtn.textContent = 'Salin', 1200);
        }).catch(() => {
            showErrorMessage('Gagal menyalin ke clipboard.');
        });
    });

    nipInput.addEventListener('input', function() {
        if (this.value.length > 8) this.value = this.value.slice(0, 8);
        if (passwordInput.value && this.value) {
            passwordInput.classList.add('border-yellow-400');
            passwordInput.setAttribute('title', 'Jika NIP diubah, tekan "Buat Password" lagi untuk menghasilkan password yang sesuai.');
        } else {
            passwordInput.classList.remove('border-yellow-400');
            passwordInput.removeAttribute('title');
        }
    });

    // Divisi Management
    document.getElementById('searchDivisi').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const listItems = document.querySelectorAll('#divisiList li');
        listItems.forEach(item => {
            const name = item.querySelector('span').textContent.toLowerCase();
            item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
        });
    });

    document.getElementById('newDivisiName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') addNewDivisi();
    });

    document.getElementById('editDivisiName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') updateDivisi();
    });

    function addNewDivisi() {
        const newDivisiName = document.getElementById('newDivisiName').value.trim();
        if (!newDivisiName) {
            showErrorMessage('Nama divisi tidak boleh kosong.');
            return;
        }

        fetch('{{ route("divisi.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: newDivisiName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const divisiList = document.getElementById('divisiList');
                const newLi = document.createElement('li');
                newLi.className = 'py-2 flex justify-between items-center';
                newLi.setAttribute('data-id', data.divisi.id);
                newLi.innerHTML = `
                    <span>${newDivisiName}</span>
                    <div class="flex gap-2">
                        <button type="button" onclick="editDivisi('${data.divisi.id}', '${newDivisiName}')"
                            class="text-blue-500 hover:text-blue-700">Edit</button>
                        <button type="button" onclick="confirmDeleteDivisi('${data.divisi.id}')"
                            class="text-red-500 hover:text-red-700">Hapus</button>
                    </div>
                `;
                divisiList.appendChild(newLi);

                const selectDivisi = document.getElementById('divisi');
                const newOption = new Option(newDivisiName, newDivisiName);
                selectDivisi.add(newOption);

                document.getElementById('newDivisiName').value = '';
                showErrorMessage('Divisi berhasil ditambahkan.');
            } else {
                showErrorMessage(data.message || 'Gagal menambahkan divisi.');
            }
        })
        .catch(() => showErrorMessage('Terjadi kesalahan saat menambahkan divisi.'));
    }

    function updateDivisi() {
        const divisiId = document.getElementById('editDivisiId').value;
        const newName = document.getElementById('editDivisiName').value.trim();
        if (!newName) {
            showErrorMessage('Nama divisi tidak boleh kosong.');
            return;
        }

        fetch(`{{ url('divisi') }}/${divisiId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: newName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const divisiList = document.getElementById('divisiList');
                const listItems = divisiList.queryAll('li');
                listItems.forEach(item => {
                    if (item.getAttribute('data-id') === divisiId) {
                        item.querySelector('span').textContent = newName;
                        item.querySelector('button[onclick*=editDivisi]').setAttribute('onclick', `editDivisi('${divisiId}', '${newName}')`);
                    }
                });

                const selectDivisi = document.getElementById('divisi');
                Array.from(selectDivisi.options).forEach(option => {
                    if (option.value === data.divisi.old_name) {
                        option.value = newName;
                        option.text = newName;
                    }
                });

                closeEditDivisiModal();
                showErrorMessage('Divisi berhasil diperbarui.');
            } else {
                showErrorMessage(data.message || 'Gagal memperbarui divisi.');
            }
        })
        .catch(() => showErrorMessage('Terjadi kesalahan saat memperbarui divisi.'));
    }

    function deleteDivisi() {
        const divisiId = document.getElementById('deleteDivisiId').value;

        fetch(`{{ url('divisi') }}/${divisiId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const divisiList = document.getElementById('divisiList');
                const listItems = divisiList.queryAll('li');
                listItems.forEach(item => {
                    if (item.getAttribute('data-id') === divisiId) item.remove();
                });

                const selectDivisi = document.getElementById('divisi');
                Array.from(selectDivisi.options).forEach(option => {
                    if (option.value === data.divisi_name) option.remove();
                });

                closeDeleteDivisiModal();
                showErrorMessage('Divisi berhasil dihapus.');
            } else {
                showErrorMessage(data.message || 'Gagal menghapus divisi.');
            }
        })
        .catch(() => showErrorMessage('Terjadi kesalahan saat menghapus divisi.'));
    }

    // Error Modal
    function showErrorMessage(message) {
        const modal = document.getElementById('errorModal');
        document.getElementById('errorMessage').textContent = message;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeErrorModal() {
        const modal = document.getElementById('errorModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>

<style>
    @keyframes slideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up {
        animation: slideUp 0.3s ease-out forwards;
    }
</style>
@endsection