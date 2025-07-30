<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

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
            'nama_barang' => 'required|string|max:255|unique:master_barangs,nama_barang',
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

        $timestamp = now()->format('ymdHis');
        $randomStr = strtoupper(substr(bin2hex(random_bytes(2)), 0, 3));
        $kodeBarang = $inisial . '-' . $timestamp . '-' . $randomStr;

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $kodeBarang,
            'jumlah' => 0, // default jumlah = 0 jika tidak ada di form
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
            'nama_barang' => 'required|string|max:255|unique:master_barangs,nama_barang,' . $id,
            
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

        $timestamp = now()->format('ymdHis');
        $randomStr = strtoupper(substr(bin2hex(random_bytes(2)), 0, 3));
        $kodeBarang = $inisial . '-' . $timestamp . '-' . $randomStr;

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $kodeBarang,
           
        ]);

        return redirect()->route('barang.index')->with('updated', 'Barang berhasil diperbarui dengan kode baru.');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('deleted', 'Barang berhasil dihapus.');
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
