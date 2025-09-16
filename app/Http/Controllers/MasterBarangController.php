<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class MasterBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('q')) {
            $search = strtolower($request->q);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_barang) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(kode_barang) LIKE ?', ['%' . $search . '%']);
            });
        }

        // Tambahan logika sortir
        $sortable = ['nama_barang', 'kode_barang', 'jumlah'];
        $sort = in_array($request->get('sort'), $sortable) ? $request->get('sort') : 'nama_barang';
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        $barangs = $query->orderBy($sort, $direction)->get();

        return view('barang.index', compact('barangs', 'sort', 'direction'));
    }



    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_barang' => 'required|string|max:255|unique:master_barang,nama_barang',
    ]);

    // Generate kode unik
    $namaBarang = strtoupper($request->nama_barang);
    $words = preg_split('/[\s\-_,.]+/', $namaBarang);
    $inisial = '';

    foreach ($words as $word) {
        if (!is_numeric($word)) {
            $inisial .= substr($word, 0, 1);
        }
    }

    date_default_timezone_set('Asia/Jakarta');
    $datePart = now()->format('ymd');    // Format: YYMMDD (250801 untuk 2025-08-01)
    $timePart = now()->format('Hi');      // Format: HHMM (0241 untuk 02:41)
    $randomStr = strtoupper(substr(bin2hex(random_bytes(1)), 0, 2)); // 2 karakter random

    $kodeBarang = $inisial . '-' . $datePart . $timePart . '-' . $randomStr;

    Barang::create([
        'nama_barang' => $request->nama_barang,
        'kode_barang' => $kodeBarang,
        'jumlah' => 0,
    ]);

    return $request->action === 'save_and_continue'
        ? redirect()->back()->with('created', 'Barang berhasil ditambahkan. Silakan tambah lagi.')
        : redirect()->route('barang.index')->with('created', 'Barang berhasil ditambahkan.');
}

    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id)
{
    $request->validate([
        'nama_barang' => 'required|string|max:255|unique:master_barang,nama_barang,' . $id,
    ]);

    $barang = Barang::findOrFail($id);

    $namaBarang = strtoupper($request->nama_barang);
    $words = preg_split('/[\s\-_,.]+/', $namaBarang);
    $inisial = '';

    foreach ($words as $word) {
        if (!is_numeric($word)) {
            $inisial .= substr($word, 0, 1);
        }
    }

    date_default_timezone_set('Asia/Jakarta');
    $datePart = now()->format('ymd');    // Format: YYMMDD
    $timePart = now()->format('Hi');      // Format: HHMM
   $randomStr = strtoupper(substr(bin2hex(random_bytes(1)), 0, 2)); // 2 karakter random

    $kodeBarang = $inisial . '-' . $datePart . $timePart . '-' . $randomStr;

    $barang->update([
        'nama_barang' => $request->nama_barang,
        'kode_barang' => $kodeBarang,
    ]);

    return redirect()->route('barang.index')->with('updated', 'Barang berhasil diperbarui dengan kode baru.');
}

            public function destroy(string $id)
{
    $barang = Barang::findOrFail($id);

    try {
        $usedInStruk = false;
        $usedInPengeluaran = false;

        // ✅ Cek di struks (kolom 'items' ada)
        if (\Schema::hasColumn('struks', 'items')) {
            $usedInStruk = DB::table('struks')
                ->whereRaw("items::text LIKE ?", ['%"' . $barang->kode_barang . '"%'])
                ->exists();
        }

        // ✅ Cek di pengeluarans — hanya jika kolom 'items' ada
        if (\Schema::hasColumn('pengeluarans', 'items')) {
            $usedInPengeluaran = DB::table('pengeluarans')
                ->whereRaw("items::text LIKE ?", ['%"' . $barang->kode_barang . '"%'])
                ->exists();
        }
        // Atau cek jika kolomnya bernama 'daftar_barang'
        elseif (\Schema::hasColumn('pengeluarans', 'daftar_barang')) {
            $usedInPengeluaran = DB::table('pengeluarans')
                ->whereRaw("daftar_barang::text LIKE ?", ['%"' . $barang->kode_barang . '"%'])
                ->exists();
        }

        if ($usedInPengeluaran || $usedInStruk) {
            return response()->json([
                'success' => false,
                'message' => "Barang '{$barang->nama_barang}' tidak dapat dihapus karena sudah digunakan di transaksi."
            ], 422);
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'nama' => $barang->nama_barang
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => "Terjadi kesalahan: " . $e->getMessage()
        ], 500);
    }
}

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if ($ids) {
            Barang::whereIn('id', $ids)->delete();
            return redirect()->route('barang.index')->with('success', 'Barang terpilih berhasil dihapus.');
        }

        return redirect()->route('barang.index')->with('success', 'Tidak ada barang yang dipilih.');
    }
}