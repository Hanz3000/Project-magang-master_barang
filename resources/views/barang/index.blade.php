@extends('layouts.app')

@section('title', 'Master Barang - Sistem Master Data')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Master Barang</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola daftar barang yang tersedia</p>
            </div>
            <a href="{{ route('barang.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Barang
            </a>
        </div>

        <div class="px-6 py-4">
            <!-- Kontrol untuk bulk actions - hanya muncul jika ada item terpilih -->
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

            <div class="flex justify-end mb-4">
                <form method="GET" action="{{ route('barang.index') }}" class="flex items-center space-x-2">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama barang..."
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                        Cari
                    </button>
                    @if(request('q'))
                    <a href="{{ route('barang.index') }}" class="ml-2 text-sm text-gray-500 hover:underline">x</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span>NO</span>
                                </div>
                            </th>
                            {{-- NAMA BARANG --}}
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

                            {{-- KODE BARANG --}}
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

                            {{-- JUMLAH --}}
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


                            <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JUMLAH</th> -->

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
                    <form id="bulkDeleteForm" action="{{ route('barang.bulk-delete') }}" method="POST">

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
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const countSpan = document.getElementById('count');
        const bulkIdsInput = document.getElementById('bulk-ids');

        // Function to update UI based on selected checkboxes
        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const hasSelected = checkedBoxes.length > 0;

            // Show/hide bulk actions bar
            if (hasSelected) {
                bulkActions.classList.remove('hidden');
                countSpan.textContent = checkedBoxes.length;
                // Update hidden input with selected IDs
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
                bulkIdsInput.value = selectedIds.join(',');
            } else {
                bulkActions.classList.add('hidden');
            }

            // Update select all checkbox state
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

        // Select all functionality
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        // Individual checkbox functionality
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        // Initial state
        updateBulkActions();
    });

    // Delete modal functions
    function openDeleteModal(formAction, itemName) {
        document.getElementById('deleteForm').action = formAction;

        const deleteText = document.getElementById('deleteItemText');
        if (itemName) {
            deleteText.textContent = `Yakin ingin menghapus data barang "${itemName}" dari daftar?`;
        } else {
            deleteText.textContent = "Yakin ingin menghapus data ini dari daftar?";
        }

        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Bulk delete modal functions
    function openBulkDeleteModal() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const selectedCount = checkedBoxes.length;
        const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);

        if (selectedCount === 0) {
            showToast('Tidak ada item yang dipilih', 'error');
            return;
        }

        document.getElementById('selectedItemsCount').textContent = selectedCount;

        // Bersihkan input lama
        const form = document.getElementById('bulkDeleteForm');
        form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

        // Tambahkan input tersembunyi baru untuk setiap ID
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

    // Close modals when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    document.getElementById('bulkDeleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBulkDeleteModal();
        }
    });

    // Close modals with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
            if (!document.getElementById('bulkDeleteModal').classList.contains('hidden')) {
                closeBulkDeleteModal();
            }
        }
    });
</script>
@endsection