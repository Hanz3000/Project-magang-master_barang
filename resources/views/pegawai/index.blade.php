@extends('layouts.app')

@section('title', 'Master Pegawai - Sistem Master Data')

@section('content')
<style>
/* Hilangkan scroll horizontal halaman */
html, body {
    overflow-x: hidden;
}
</style>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 leading-tight">Master Pegawai</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola daftar pegawai yang tersedia</p>
            </div>
            <a href="{{ route('pegawai.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-xs sm:text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Pegawai
            </a>
        </div>

        <div class="px-6 py-4">
            <!-- Search bar -->
            <div class="mb-4 flex justify-center">
                <form method="GET" action="{{ route('pegawai.index') }}" class="w-full max-w-xs flex flex-col sm:flex-row gap-2">
                    <input type="text" id="search-input" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama pegawai atau NIP..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                            Cari
                        </button>
                        @if(request('q'))
                        <a href="{{ route('pegawai.index') }}" class="flex items-center px-2 py-2 bg-gray-200 rounded-lg text-xs text-gray-500 hover:bg-gray-300">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Notification -->
            @if(session('success') || session('deleted') || session('error'))
            <div id="notification" class="mb-6 p-4 rounded-md shadow-sm transition-all duration-300 ease-in-out">
                @if(session('success'))
                <div class="flex items-center text-green-700 bg-green-100 border border-green-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                @elseif(session('deleted'))
                <div class="flex items-center text-green-700 bg-green-100 border border-green-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </div>
                    <span>{{ session('deleted') }}</span>
                </div>
                @elseif(session('error'))
                <div class="flex items-center text-red-700 bg-red-100 border border-red-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                @endif
            </div>
            <script>
                setTimeout(() => {
                    const notif = document.getElementById('notification');
                    if (notif) {
                        notif.style.opacity = '0';
                        notif.style.transition = 'opacity 0.5s ease-out';
                        setTimeout(() => notif.remove(), 500);
                    }
                }, 5000);
            </script>
            @endif

            <!-- Bulk actions bar -->
            <div id="bulk-actions" class="flex items-center justify-between mb-4 p-3 bg-red-50 border border-red-200 rounded-lg hidden">
                <span id="selected-count" class="text-sm text-red-700 font-medium">
                    <span id="count">0</span> pegawai dipilih
                </span>
                <button type="button" id="bulk-delete-btn" onclick="openBulkDeleteModal()"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Terpilih
                </button>
            </div>

            <!-- Responsive Table/Card -->
            <div class="w-full">
                <!-- Table for large screens -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span>No</span>
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAMA PEGAWAI</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DIVISI</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pegawais as $pegawai)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" name="ids[]" value="{{ $pegawai->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded row-checkbox">
                                        <span>{{ $loop->iteration }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pegawai->nama }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $pegawai->nip }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $pegawai->division->name ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition-colors duration-150"
                                           title="Edit pegawai">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                                onclick="openDeleteModal('{{ route('pegawai.destroy', $pegawai->id) }}', '{{ $pegawai->nama }}', '{{ $pegawai->id }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full transition-colors duration-150"
                                                title="Hapus pegawai {{ $pegawai->nama }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-600 font-medium">Belum ada pegawai yang ditambahkan.</p>
                                        <p class="text-gray-400 text-xs mt-1">Klik "Tambah Pegawai" untuk menambahkan data pertama</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Card/grid for small screens -->
                <div class="sm:hidden flex flex-col items-center gap-4 w-full">
                    @forelse ($pegawais as $pegawai)
                    <div class="bg-white rounded-lg shadow p-4 w-full max-w-[340px] flex flex-col gap-2 mx-auto">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="ids[]" value="{{ $pegawai->id }}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded row-checkbox">
                                <span class="text-xs font-semibold text-gray-500">No: {{ $loop->iteration }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                                   class="text-blue-600 hover:text-blue-800" title="Edit pegawai">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button type="button"
                                        onclick="openDeleteModal('{{ route('pegawai.destroy', $pegawai->id) }}', '{{ $pegawai->nama }}', '{{ $pegawai->id }}')"
                                        class="text-red-600 hover:text-red-800" title="Hapus pegawai">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $pegawai->nama }}</div>
                            <div class="text-xs text-gray-500">NIP: {{ $pegawai->nip }}</div>
                            <div class="text-xs text-gray-500">Divisi: {{ $pegawai->division->name ?? '-' }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500 w-full max-w-[340px] mx-auto">
                        Belum ada pegawai yang ditambahkan.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Penghapusan</h3>
                        <div class="mt-2">
                            <p id="deleteItemText" class="text-sm text-gray-500"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="deleteIdInput">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Penghapusan Massal</h3>
                        <div class="mt-2">
                            <p id="bulkDeleteItemText" class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus <span id="selectedItemsCount">0</span> pegawai yang dipilih?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="bulkDeleteForm" action="{{ route('pegawai.bulk-delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="bulk-ids-input" name="ids" value="">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeBulkDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = searchInput.value.trim();
                const url = new URL(window.location.href);

                if (query) {
                    url.searchParams.set('q', query);
                } else {
                    url.searchParams.delete('q');
                }

                history.replaceState(null, '', url.toString());

                fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('table');
                    const currentTable = document.querySelector('table');

                    if (newTable && currentTable) {
                        currentTable.innerHTML = newTable.innerHTML;
                    }
                })
                .catch(error => console.error('Error:', error));
            }, 500);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const countSpan = document.getElementById('count');
        const bulkIdsInput = document.getElementById('bulk-ids-input');

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const hasSelected = checkedBoxes.length > 0;

            if (hasSelected) {
                bulkActions.classList.remove('hidden');
                countSpan.textContent = checkedBoxes.length;
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
                if (bulkIdsInput) {
                    bulkIdsInput.value = selectedIds.join(',');
                }
            } else {
                bulkActions.classList.add('hidden');
            }

            if (checkedBoxes.length === rowCheckboxes.length && rowCheckboxes.length > 0) {
                selectAll.checked = true;
                selectAll.indeterminate = false;
            } else if (checkedBoxes.length > 0) {
                selectAll.checked = false;
                selectAll.indeterminate = true;
            } else {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
        }

        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateBulkActions();
        });

        rowCheckboxes.forEach(checkbox => checkbox.addEventListener('change', updateBulkActions));

        updateBulkActions();
    });

    function openDeleteModal(url, namaPegawai, id) {
        const form = document.getElementById('deleteForm');
        const textElement = document.getElementById('deleteItemText');
        const idInput = document.getElementById('deleteIdInput');
        const modal = document.getElementById('deleteModal');

        form.action = url;
        idInput.value = id;
        textElement.textContent = `Apakah Anda yakin ingin menghapus pegawai "${namaPegawai}"?`;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const action = form.getAttribute('action');

        fetch(action, {
            method: 'POST',
            body: new URLSearchParams(formData),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            closeDeleteModal();
            if (data.success) {
                location.reload();
            } else {
                showErrorMessage(data.message);
            }
        })
        .catch(error => {
            closeDeleteModal();
            showErrorMessage("Terjadi kesalahan saat menghapus pegawai.");
        });
    });

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openBulkDeleteModal() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const selectedCount = checkedBoxes.length;
        const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);

        document.getElementById('selectedItemsCount').textContent = selectedCount;
        document.getElementById('bulk-ids-input').value = selectedIds.join(',');

        document.getElementById('bulkDeleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    document.getElementById('bulkDeleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeBulkDeleteModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('deleteModal').classList.contains('hidden')) closeDeleteModal();
            if (!document.getElementById('bulkDeleteModal').classList.contains('hidden')) closeBulkDeleteModal();
        }
    });

    function showErrorMessage(message) {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full bg-white">
                        <div class="bg-red-50 px-4 py-3 sm:px-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <h3 class="text-sm font-medium text-red-800">Peringatan</h3>
                        </div>
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <p class="text-sm text-gray-700">${message}</p>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="closeErrorModal(this)" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal.firstElementChild);
        document.body.classList.add('overflow-hidden');
    }

    function closeErrorModal(button) {
        const modal = button.closest('.fixed.inset-0');
        if (modal) modal.remove();
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endsection