<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Division;
use App\Models\User; 

class PegawaiController extends Controller
{
    public function index(Request $request)
{
    $query = Pegawai::query();

    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sub) use ($q) {
            $sub->where('nama', 'like', "%$q%")
                ->orWhere('nip', 'like', "%$q%");
        });
    }

    // Urutkan berdasarkan waktu update terbaru
    $pegawais = $query->orderBy('updated_at', 'desc')->get();

    return view('pegawai.index', compact('pegawais'));
}


    public function create()
{
    $divisions = \App\Models\Division::all(); // Kirim data divisi ke view
    return view('pegawai.create', compact('divisions'));
}   

public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'nip' => 'required|string|max:20|unique:pegawais,nip',
        'divisi' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Cari atau buat divisi baru
    $division = Division::firstOrCreate(
        ['name' => $validated['divisi']],
        ['created_at' => now(), 'updated_at' => now()]
    );

    // Simpan ke tabel pegawais dengan divisi_id yang benar
    $pegawai = Pegawai::create([
        'nama' => $validated['nama'],
        'nip' => $validated['nip'],
        'divisi_id' => $division->id, // Pastikan menggunakan divisi_id
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Simpan ke tabel users
    User::create([
        'name' => $validated['nama'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
}   


    public function edit(Pegawai $pegawai)
{
    $divisions = \App\Models\Division::all();
    return view('pegawai.edit', compact('pegawai', 'divisions'));
}


public function update(Request $request, Pegawai $pegawai)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'nip' => 'required|string|max:20|unique:pegawais,nip,' . $pegawai->id,
        'divisi' => 'required|string',
    ], [
        'nip.required' => 'NIP wajib diisi.',
        'nip.unique' => 'NIP sudah digunakan.',
    ]);

    $pegawai->update([
        'nama' => $request->nama,
        'nip' => $request->nip,
        'divisi' => $request->divisi,
    ]);

    return redirect()
        ->route('pegawai.index')
        ->with('updated', 'Data pegawai berhasil diperbarui.');
}

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()
            ->route('pegawai.index')
            ->with('deleted', 'Pegawai berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        if (!empty($ids)) {
            \App\Models\Pegawai::whereIn('id', $ids)->delete();
            return redirect()
                ->route('pegawai.index')
                ->with('deleted', 'Pegawai terpilih berhasil dihapus.');
        }
        return redirect()
            ->route('pegawai.index')
            ->with('created', 'Tidak ada pegawai yang dipilih.');
    }
}