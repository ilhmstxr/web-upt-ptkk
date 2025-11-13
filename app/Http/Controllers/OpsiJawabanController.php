<?php

namespace App\Http\Controllers;

use App\Models\OpsiJawaban;
use Illuminate\Http\Request;

class OpsiJawabanController extends Controller
{
    /**
     * Tampilkan daftar semua opsi jawaban.
     */
    public function index()
    {
        $opsiJawaban = OpsiJawaban::all();
        return view('opsi-jawaban.index', compact('opsiJawaban'));
    }

    /**
     * Tampilkan form untuk membuat opsi jawaban baru.
     */
    public function create()
    {
        return view('opsi-jawaban.create');
    }

    /**
     * Simpan opsi jawaban baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'benar' => 'required|boolean',
        ]);

        OpsiJawaban::create([
            'nama' => $request->nama,
            'benar' => $request->benar,
        ]);

        return redirect()->route('opsi-jawaban.index')
            ->with('success', 'Opsi jawaban berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail opsi jawaban tertentu.
     */
    public function show(OpsiJawaban $opsiJawaban)
    {
        return view('opsi-jawaban.show', compact('opsiJawaban'));
    }

    /**
     * Tampilkan form untuk mengedit opsi jawaban.
     */
    public function edit(OpsiJawaban $opsiJawaban)
    {
        return view('opsi-jawaban.edit', compact('opsiJawaban'));
    }

    /**
     * Update opsi jawaban di database.
     */
    public function update(Request $request, OpsiJawaban $opsiJawaban)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'benar' => 'required|boolean',
        ]);

        $opsiJawaban->update([
            'nama' => $request->nama,
            'benar' => $request->benar,
        ]);

        return redirect()->route('opsi-jawaban.index')
            ->with('success', 'Opsi jawaban berhasil diperbarui.');
    }

    /**
     * Hapus opsi jawaban dari database.
     */
    public function destroy(OpsiJawaban $opsiJawaban)
    {
        $opsiJawaban->delete();

        return redirect()->route('opsi-jawaban.index')
            ->with('success', 'Opsi jawaban berhasil dihapus.');
    }
}
