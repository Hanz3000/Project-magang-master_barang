@extends('layouts.app')

@section('title', 'Master Barang - Sistem Master Data')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Master Barang</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola daftar barang yang tersedia</p>
            </div>
            <a href="{{ route('barang.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Barang
            </a>
        </div>

        <div class="px-6 py-4">
            <div id="bulk-actions" class="flex items-center justify-between mb-4 p-3 bg-red-50 border border-red-200 rounded-lg hidden">
                <span id="selected-count" class="text-sm text-red-700 font-medium">
                    <span id="count">0</span> item dipilih
                </span>
                <button type="button" id="bulk-delete-btn" onclick="openBulkDeleteModal()"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Terpilih
                </button>
            </div>

            <div class="mb-4 w-full flex justify-center">
                <form method="GET" action="{{ route('barang.index') }}" class="w-full max-w-xs flex flex-col sm:flex-row gap-2">
                    <input type="text" id="search-input" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama barang..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                            Cari
                        </button>
                        @if(request('q'))
                        <a href="{{ route('barang.index') }}" class="flex items-center px-3 py-2 bg-gray-200 rounded-md text-sm text-gray-500 hover:bg-gray-300">x</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="w-full">
                <!-- Table for large screens -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span>NO</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('barang.index', array_merge(request()->query(), ['sort' => 'nama_barang', 'direction' => ($sort === 'nama_barang' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                       class="flex items-center space-x-1 hover:underline">
                                        <span>Nama Barang</span>
                                        <svg class="w-3 h-3 {{ $sort === 'nama_barang' ? 'text-gray-800' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="{{ $sort === 'nama_barang' && $direction === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('barang.index', array_merge(request()->query(), ['sort' => 'kode_barang', 'direction' => ($sort === 'kode_barang' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                       class="flex items-center space-x-1 hover:underline">
                                        <span>Kode Barang</span>
                                        <svg class="w-3 h-3 {{ $sort === 'kode_barang' ? 'text-gray-800' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="{{ $sort === 'kode_barang' && $direction === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('barang.index', array_merge(request()->query(), ['sort' => 'jumlah', 'direction' => ($sort === 'jumlah' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                       class="flex items-center space-x-1 hover:underline">
                                        <span>Jumlah</span>
                                        <svg class="w-3 h-3 {{ $sort === 'jumlah' ? 'text-gray-800' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="{{ $sort === 'jumlah' && $direction === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($barangs as $barang)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" name="ids[]" value="{{ $barang->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded row-checkbox">
                                        <span>{{ $loop->iteration }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $barang->kode_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $barang->jumlah }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('barang.edit', $barang->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition-colors duration-150"
                                           title="Edit barang">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                                onclick="openDeleteModal('{{ route('barang.destroy', $barang->id) }}', '{{ $barang->nama_barang }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full transition-colors duration-150"
                                                title="Hapus barang {{ $barang->nama_barang }}">
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
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-gray-600 font-medium">Belum ada data barang</p>
                                        <p class="text-gray-400 text-xs mt-1">Klik "Tambah Barang" untuk menambahkan data pertama</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Card/grid for small screens -->
                <div class="sm:hidden flex flex-col items-center gap-4 w-full">
                    @forelse ($barangs as $barang)
                    <div class="bg-white rounded-lg shadow p-4 w-full max-w-[340px] flex flex-col gap-2 mx-auto">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="ids[]" value="{{ $barang->id }}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded row-checkbox">
                                <span class="text-xs font-semibold text-gray-500">No: {{ $loop->iteration }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('barang.edit', $barang->id) }}"
                                   class="text-blue-600 hover:text-blue-800" title="Edit barang">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button type="button"
                                        onclick="openDeleteModal('{{ route('barang.destroy', $barang->id) }}', '{{ $barang->nama_barang }}')"
                                        class="text-red-600 hover:text-red-800" title="Hapus barang">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</div>
                            <div class="text-xs text-gray-500">Kode: {{ $barang->kode_barang }}</div>
                            <div class="text-xs text-gray-500">Jumlah: {{ $barang->jumlah }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500 w-full max-w-[340px] mx-auto">
                        Belum ada data barang
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Konfirmasi Penghapusan</h3>
                        <div class="mt-2">
                            <p id="deleteItemText" class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus item ini?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
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
                            <p id="bulkDeleteItemText" class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus <span id="selectedItemsCount">0</span> item yang dipilih?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="bulkDeleteForm" action="{{ route('barang.bulk-delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
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

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const hasSelected = checkedBoxes.length > 0;

            if (hasSelected) {
                bulkActions.classList.remove('hidden');
                countSpan.textContent = checkedBoxes.length;
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

    function openDeleteModal(formAction, itemName) {
        const form = document.getElementById('deleteForm');
        const deleteText = document.getElementById('deleteItemText');
        
        deleteText.textContent = `Yakin ingin menghapus data barang "${itemName}" dari daftar?`;
        form.setAttribute('action', formAction);
        
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const action = form.getAttribute('action');
        
        fetch(action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            closeDeleteModal();
            if (data.success) location.reload();
            else showErrorMessage(data.message);
        })
        .catch(error => {
            closeDeleteModal();
            showErrorMessage("Terjadi kesalahan saat menghapus barang.");
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

        if (selectedCount === 0) return;

        document.getElementById('selectedItemsCount').textContent = selectedCount;

        const form = document.getElementById('bulkDeleteForm');
        form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

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