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
    $query = $request->input('q');
    $pegawais = Pegawai::query();

    if ($query) {
        $pegawais->where(function ($q) use ($query) {
            $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($query) . '%'])
              ->orWhereRaw('LOWER(nip) LIKE ?', ['%' . strtolower($query) . '%']);
        });
    }

    return view('pegawai.index', [
        'pegawais' => $pegawais->paginate(10),
    ]);
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

    // Buat user terlebih dahulu
    $user = User::create([
        'name' => $validated['nama'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    // Cari atau buat divisi
    $division = Division::firstOrCreate(
        ['name' => $validated['divisi']],
        ['created_at' => now(), 'updated_at' => now()]
    );

    // Simpan pegawai dengan user_id
    Pegawai::create([
        'nama' => $validated['nama'],
        'nip' => $validated['nip'],
        'divisi_id' => $division->id,
        'user_id' => $user->id,
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
        'divisi' => 'required|string|max:255',
    ]);

    $division = Division::firstOrCreate(
        ['name' => $request->divisi],
        ['created_at' => now(), 'updated_at' => now()]
    );

    $pegawai->update([
        'nama' => $request->nama,
        'nip' => $request->nip,
        'divisi_id' => $division->id,
    ]);

    return redirect()->route('pegawai.index')->with('updated', 'Data pegawai berhasil diperbarui.');
}

public function destroy(Pegawai $pegawai)
{
    // Hapus user yang terkait (jika ada)
    if ($pegawai->user) {
        $pegawai->user->delete();
    }

    // Hapus pegawai
    $pegawai->delete();

    return redirect()
        ->route('pegawai.index')
        ->with('deleted', 'Pegawai berhasil dihapus.');
}
    public function bulkDelete(Request $request)
{
    $ids = explode(',', $request->ids);

    if (!empty($ids)) {
        // Ambil semua pegawai yang dipilih
        $pegawais = Pegawai::whereIn('id', $ids)->get();

        foreach ($pegawais as $pegawai) {
            // Hapus user yang terkait jika ada
            if ($pegawai->user) {
                $pegawai->user->delete();
            }

            // Hapus pegawai
            $pegawai->delete();
        }

        return redirect()
            ->route('pegawai.index')
            ->with('deleted', 'Pegawai terpilih berhasil dihapus.');
    }

    return redirect()
        ->route('pegawai.index')
        ->with('created', 'Tidak ada pegawai yang dipilih.');
}

}