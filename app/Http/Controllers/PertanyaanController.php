<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PertanyaanController extends Controller
{
    /**
     * Tampilkan semua pertanyaan
     */
    public function index()
    {
        $pertanyaans = Pertanyaan::latest()->paginate(10);
        return view('pertanyaan.index', compact('pertanyaans'));
    }

    /**
     * Tampilkan form tambah pertanyaan
     */
    public function create()
    {
        return view('pertanyaan.create');
    }

    /**
     * Simpan pertanyaan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // upload gambar kalau ada
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pertanyaan', 'public');
        }

        Pertanyaan::create($validated);

        return redirect()->route('pertanyaan.index')->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail pertanyaan
     */
    public function show(Pertanyaan $pertanyaan)
    {
        return view('pertanyaan.show', compact('pertanyaan'));
    }

    /**
     * Tampilkan form edit pertanyaan
     */
    public function edit(Pertanyaan $pertanyaan)
    {
        return view('pertanyaan.edit', compact('pertanyaan'));
    }

    /**
     * Update pertanyaan
     */
    public function update(Request $request, Pertanyaan $pertanyaan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // hapus gambar lama kalau upload baru
        if ($request->hasFile('gambar')) {
            if ($pertanyaan->gambar && Storage::disk('public')->exists($pertanyaan->gambar)) {
                Storage::disk('public')->delete($pertanyaan->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('pertanyaan', 'public');
        }

        $pertanyaan->update($validated);

        return redirect()->route('pertanyaan.index')->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /**
     * Hapus pertanyaan
     */
    public function destroy(Pertanyaan $pertanyaan)
    {
        if ($pertanyaan->gambar && Storage::disk('public')->exists($pertanyaan->gambar)) {
            Storage::disk('public')->delete($pertanyaan->gambar);
        }

        $pertanyaan->delete();

        return redirect()->route('pertanyaan.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
