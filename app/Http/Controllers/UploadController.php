<?php
// app/Http/Controllers/UploadController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UploadController extends Controller
{
    /**
     * Unggah satu berkas gambar ke disk public.
     * Input utama: file (required, image). Opsional: folder, overwrite, filename_base.
     * Response: 201 JSON { path, url, original_name, size, mime }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file'          => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'folder'        => ['nullable', 'string'],
            'overwrite'     => ['nullable', 'boolean'],
            'filename_base' => ['nullable', 'string'],
        ]);

        $file      = $request->file('file');
        $disk      = Storage::disk('public');
        $folder    = trim($validated['folder'] ?? 'pertanyaan/opsi', '/');
        $overwrite = (bool)($validated['overwrite'] ?? false);
        $ext       = strtolower($file->getClientOriginalExtension() ?: $file->extension());

        // Nama dasar: filename_base atau slug dari nama asli
        $base = $validated['filename_base'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base = Str::slug($base) ?: Str::random(12);

        // Pastikan folder ada
        if (! $disk->exists($folder)) {
            $disk->makeDirectory($folder);
        }

        $name = "{$base}.{$ext}";
        if (! $overwrite && $disk->exists("{$folder}/{$name}")) {
            $name = $base . '-' . now()->format('YmdHisv') . '.' . $ext;
        }

        try {
            $path = $file->storeAs($folder, $name, 'public');
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal menyimpan berkas.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'path'          => $path,                   // simpan ke kolom DB
            'url'           => $disk->url($path),       // untuk preview
            'original_name' => $file->getClientOriginalName(),
            'size'          => $file->getSize(),
            'mime'          => $file->getMimeType(),
        ], Response::HTTP_CREATED);
    }
}
