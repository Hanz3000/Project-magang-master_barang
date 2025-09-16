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
        $divisions = Division::all(); // Kirim data divisi ke view
        return view('pegawai.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|size:8|unique:pegawais,nip',
            'divisi' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $validated['nama'],
            'nip' => $validated['nip'],
            'password' => bcrypt($validated['password']),
        ]);

        // Cari atau buat divisi
        $division = Division::firstOrCreate(
            ['name' => $validated['divisi']],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Simpan pegawai tanpa user_id (karena email tidak digunakan)
        Pegawai::create([
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'divisi_id' => $division->id,
            'user_id' => $user->id, // ✅ simpan relasi user
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        $divisions = Division::all();
        return view('pegawai.edit', compact('pegawai', 'divisions'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|size:8|unique:pegawais,nip,' . $pegawai->id,
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
        // Cek hanya di pengeluarans (karena struks tidak punya pegawai_id)
        if (\DB::table('pengeluarans')->where('pegawai_id', $pegawai->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Pegawai '{$pegawai->nama}' tidak dapat dihapus karena sudah digunakan di data pengeluaran barang."
            ], 422);
        }

        // Hapus user jika ada (meskipun saat ini tidak digunakan)
        if ($pegawai->user) {
            $pegawai->user->delete();
        }

        // Hapus pegawai
        $pegawai->delete();

        return response()->json([
            'success' => true,
            'nama' => $pegawai->nama
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        $failedDeletes = [];
        $successfulDeletes = [];

        if (empty($ids)) {
            return redirect()->route('pegawai.index')->with('info', 'Tidak ada pegawai yang dipilih.');
        }

        foreach ($ids as $id) {
            $pegawai = Pegawai::find($id);
            if (!$pegawai) continue;

            // Cek hanya di pengeluarans
            if (\DB::table('pengeluarans')->where('pegawai_id', $pegawai->id)->exists()) {
                $failedDeletes[] = $pegawai->nama;
            } else {
                if ($pegawai->user) {
                    $pegawai->user->delete();
                }
                $pegawai->delete();
                $successfulDeletes[] = $pegawai->nama;
            }
        }

        $message = '';
        if ($successfulDeletes) {
            $message .= 'Berhasil dihapus: ' . count($successfulDeletes) . ' pegawai. ';
        }
        if ($failedDeletes) {
            $message .= 'Gagal dihapus: ' . implode(', ', $failedDeletes) . ' (digunakan di pengeluaran).';
        }

        return redirect()->route('pegawai.index')->with('message', $message);
    }
}