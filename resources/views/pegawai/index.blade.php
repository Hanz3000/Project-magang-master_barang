@extends('layouts.app')

@section('title', 'Master Pegawai - Sistem Master Data')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Master Pegawai</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola daftar pegawai yang tersedia</p>
            </div>
            <a href="{{ route('pegawai.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Pegawai
            </a>
        </div>

        <div class="px-6 py-4">
            <!-- Search bar -->
            <div class="flex justify-end mb-4">
                <form method="GET" action="{{ route('pegawai.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari nama pegawai atau NIP..."
                            class="pl-3 pr-10 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        @if(request('q'))
                        <a href="{{ route('pegawai.index') }}" class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-red-500" title="Reset pencarian">
                            &times;
                        </a>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-200">
                        Cari
                    </button>
                </form>
            </div>


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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAMA PEGAWAI</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pegawais as $pegawai)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="ids[]" value="{{ $pegawai->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded row-checkbox">
                                    <span>{{ $loop->iteration }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pegawai->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pegawai->nip }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition-colors duration-150"
                                        title="Edit pegawai">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('pegawai.destroy', $pegawai->id) }}', '{{ $pegawai->nama }}')"
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
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
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
                            <p id="deleteItemText" class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus pegawai ini?</p>
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
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const countSpan = document.getElementById('count');
        const bulkIdsInput = document.getElementById('bulk-ids');

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
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        updateBulkActions();
    });

    // Delete modal functions
    function openDeleteModal(formAction, namaPegawai = '') {
        const form = document.getElementById('deleteForm');
        const textElement = document.getElementById('deleteItemText');
        const modal = document.getElementById('deleteModal');

        form.action = formAction;
        textElement.textContent = namaPegawai ?
            `Apakah Anda yakin ingin menghapus pegawai "${namaPegawai}" dari daftar?` :
            'Apakah Anda yakin ingin menghapus pegawai ini dari daftar?';

        modal.classList.remove('hidden');
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

        document.getElementById('selectedItemsCount').textContent = selectedCount;
        document.getElementById('bulk-ids-input').value = selectedIds.join(',');

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