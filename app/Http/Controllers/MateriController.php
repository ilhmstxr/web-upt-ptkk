<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\StoreMateriRequest; // opsional jika pakai FormRequest
use App\Http\Requests\UpdateMateriRequest;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar semua materi.
     * Menggunakan materi-index.blade.php.
     */
    public function index()
    {
        // Tentukan kolom pengurutan (prioritas 'order', fallback 'judul')
        $orderBy = Schema::hasColumn('materi', 'order') ? 'order' : 'judul';
        
        // Ambil data materi dan lakukan paginasi
        $materiList = Materi::orderBy($orderBy, 'asc')->paginate(12);

        // Memanggil view yang sudah disesuaikan dengan nama file
        return view('dashboard.pages.materi.materi-index', compact('materiList'));
    }

    /**
     * Menampilkan formulir untuk membuat materi baru.
     * Diasumsikan menggunakan create.blade.php atau materi-create.blade.php.
     */
    public function create()
    {
        return view('dashboard.pages.materi.create');
    }

    /**
     * Menyimpan materi baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:materi,slug',
            'order' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'durasi' => 'nullable|integer',
            'konten' => 'nullable|string',
            'file_pendukung' => 'nullable|file',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['judul']);
        }

        if ($request->hasFile('file_pendukung')) {
            $data['file_pendukung'] = $request->file('file_pendukung')->store('materi/files', 'public');
        }

        Materi::create($data);

        return redirect()->route('dashboard.materi.index')->with('success', 'Materi berhasil dibuat.');
    }

    /**
     * Menampilkan detail materi tertentu.
     * Menggunakan materi-show.blade.php.
     */
    public function show(Materi $materi)
    {
        // Cari materi sebelumnya (prev) dan materi selanjutnya (next) berdasarkan 'order'
        $prev = Materi::where('order', '<', $materi->order)->orderBy('order','desc')->first();
        $next = Materi::where('order', '>', $materi->order)->orderBy('order','asc')->first();

        // Memanggil view yang sudah disesuaikan dengan nama file
        return view('dashboard.pages.materi.materi-show', [
            'materi' => $materi,
            'prevMateri' => $prev,
            'nextMateri' => $next,
        ]);
    }

    /**
     * Menampilkan formulir untuk mengedit materi tertentu.
     * Diasumsikan menggunakan edit.blade.php atau materi-edit.blade.php.
     */
    public function edit(Materi $materi)
    {
        return view('dashboard.pages.materi.edit', compact('materi'));
    }

    /**
     * Memperbarui materi tertentu di database.
     */
    public function update(Request $request, Materi $materi)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:materi,slug,'.$materi->id,
            'order' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'durasi' => 'nullable|integer',
            'konten' => 'nullable|string',
            'file_pendukung' => 'nullable|file',
        ]);

        if ($request->hasFile('file_pendukung')) {
            // Hapus file lama jika ada
            if ($materi->file_pendukung && Storage::disk('public')->exists($materi->file_pendukung)) {
                Storage::disk('public')->delete($materi->file_pendukung);
            }
            // Simpan file baru
            $data['file_pendukung'] = $request->file('file_pendukung')->store('materi/files', 'public');
        }

        $materi->update($data);

        return redirect()->route('dashboard.materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Menghapus materi tertentu dari database.
     */
    public function destroy(Materi $materi)
    {
        // Hapus file pendukung terkait
        if ($materi->file_pendukung && Storage::disk('public')->exists($materi->file_pendukung)) {
            Storage::disk('public')->delete($materi->file_pendukung);
        }
        
        $materi->delete();

        return redirect()->route('dashboard.materi.index')->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Optional: Menandai materi sebagai selesai (menggunakan session).
     */
    public function complete(Request $request, Materi $materi)
    {
        $request->session()->put("materi_completed.{$materi->id}", true);
        return back()->with('success', 'Materi ditandai selesai.');
    }
}