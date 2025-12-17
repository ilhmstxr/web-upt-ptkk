<?php

namespace App\Http\Controllers;

use App\Models\MateriPelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /* =====================================================
     * INDEX
     * ===================================================== */
    public function index()
    {
        $materiList = MateriPelatihan::orderBy('urutan')->paginate(12);

        return view('dashboard.pages.materi.materi-index', compact('materiList'));
    }

    /* =====================================================
     * CREATE
     * ===================================================== */
    public function create()
    {
        return view('dashboard.pages.materi.create');
    }

    /* =====================================================
     * STORE
     * ===================================================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id',
            'kompetensi_id' => 'nullable|exists:kompetensi,id',

            'judul' => 'required|string|max:255',
            'tipe'  => 'required|in:video,file,link,teks',

            'deskripsi' => 'nullable|string',
            'estimasi_menit' => 'nullable|integer',

            'video_url' => 'nullable|url',
            'link_url'  => 'nullable|url',
            'teks'      => 'nullable|string',

            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120',
            'is_published' => 'nullable|boolean',
        ]);

        /* =============================
         * AUTO URUTAN (ANTI DUPLICATE)
         * ============================= */
        $maxUrutan = MateriPelatihan::where('pelatihan_id', $data['pelatihan_id'])
            ->max('urutan');

        $data['urutan'] = ($maxUrutan ?? 0) + 1;

        /* =============================
         * NORMALISASI YOUTUBE
         * ============================= */
        if ($data['tipe'] === 'video' && !empty($data['video_url'])) {
            $url = $data['video_url'];

            if (str_contains($url, 'watch?v=')) {
                $id = explode('watch?v=', $url)[1];
                $id = explode('&', $id)[0];
                $data['video_url'] = 'https://www.youtube.com/embed/' . $id;
            }

            if (str_contains($url, 'youtu.be/')) {
                $id = explode('youtu.be/', $url)[1];
                $data['video_url'] = 'https://www.youtube.com/embed/' . $id;
            }
        }

        /* =============================
         * FILE UPLOAD
         * ============================= */
        if ($data['tipe'] === 'file' && $request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materi-files', 'public');
        }

        $data['is_published'] = $data['is_published'] ?? true;

        MateriPelatihan::create($data);

        return redirect()
            ->route('dashboard.materi.index')
            ->with('success', 'Materi berhasil dibuat.');
    }

    /* =====================================================
     * SHOW
     * ===================================================== */
    public function show(MateriPelatihan $materi)
    {
        $relatedMateris = MateriPelatihan::where('pelatihan_id', $materi->pelatihan_id)
            ->orderBy('urutan')
            ->get();

        return view('dashboard.pages.materi.show', [
            'materi' => $materi,
            'relatedMateris' => $relatedMateris,
        ]);
    }

    /* =====================================================
     * EDIT
     * ===================================================== */
    public function edit(MateriPelatihan $materi)
    {
        return view('dashboard.pages.materi.edit', compact('materi'));
    }

    /* =====================================================
     * UPDATE
     * ===================================================== */
    public function update(Request $request, MateriPelatihan $materi)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe'  => 'required|in:video,file,link,teks',

            'deskripsi' => 'nullable|string',
            'estimasi_menit' => 'nullable|integer',

            'video_url' => 'nullable|url',
            'link_url'  => 'nullable|url',
            'teks'      => 'nullable|string',

            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120',
            'is_published' => 'nullable|boolean',
        ]);

        /* =============================
         * NORMALISASI YOUTUBE
         * ============================= */
        if ($data['tipe'] === 'video' && !empty($data['video_url'])) {
            $url = $data['video_url'];

            if (str_contains($url, 'watch?v=')) {
                $id = explode('watch?v=', $url)[1];
                $id = explode('&', $id)[0];
                $data['video_url'] = 'https://www.youtube.com/embed/' . $id;
            }

            if (str_contains($url, 'youtu.be/')) {
                $id = explode('youtu.be/', $url)[1];
                $data['video_url'] = 'https://www.youtube.com/embed/' . $id;
            }
        }

        /* =============================
         * FILE UPDATE
         * ============================= */
        if ($data['tipe'] === 'file' && $request->hasFile('file')) {
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $data['file_path'] = $request->file('file')->store('materi-files', 'public');
        }

        $materi->update($data);

        return redirect()
            ->route('dashboard.materi.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /* =====================================================
     * DESTROY
     * ===================================================== */
    public function destroy(MateriPelatihan $materi)
    {
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()
            ->route('dashboard.materi.index')
            ->with('success', 'Materi berhasil dihapus.');
    }

    /* =====================================================
     * COMPLETE (SESSION BASED)
     * ===================================================== */
    public function complete(Request $request, MateriPelatihan $materi)
    {
        $request->session()->put("materi_completed.{$materi->id}", true);

        return back()->with('success', 'Materi ditandai selesai.');
    }
}
