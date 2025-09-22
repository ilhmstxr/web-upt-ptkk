<?php
// app/Http/Controllers/UploadController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate(['file' => ['required', 'image', 'max:2048']]);
    //     $path = $request->file('file')->store('pertanyaan/opsi', 'public');

    //     return response()->json([
    //         'path' => $path,
    //         'url'  => Storage::disk('public')->url($path),
    //     ]);
    // }

    public function store(Request $request)
    {
        // metadata opsional untuk pola nama file
        $request->validate([
            // single file "file" (dipakai oleh simple-uploader), tapi controller ini juga mendukung banyak field
            'file'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'field'         => ['nullable', 'string'],   // nama field tunggal (mis. 'gambar' / 'fc_ktp')
            'fields'        => ['nullable', 'array'],    // daftar field (mis. ['fc_ktp','fc_ijazah'])
            'folder'        => ['nullable', 'string'],   // default di bawah
            'peserta_id'    => ['nullable', 'string'],
            'bidang_id'     => ['nullable', 'string'],
            'instansi_id'   => ['nullable', 'string'],
            'overwrite'     => ['nullable', 'boolean'],
        ]);

        $disk      = Storage::disk('public');
        $folder    = trim($request->input('folder', 'pertanyaan/opsi'), '/');
        $overwrite = $request->boolean('overwrite', false);

        // metadata penamaan
        $pesertaId   = (string) ($request->input('peserta_id', 'x'));
        $bidangId    = (string) ($request->input('bidang_id', 'x'));
        $instansiId  = (string) ($request->input('instansi_id', 'x'));

        // tentukan field-file yang akan diproses
        $fileFields = (array) ($request->input('fields') ?? []);
        if (empty($fileFields)) {
            // fallback: jika tidak ada 'fields', coba 'field', jika tetap kosong pakai kunci file yang ada
            if ($request->input('field')) {
                $fileFields = [$request->input('field')];
            } else {
                $fileFields = array_keys($request->files->all()) ?: ['file'];
            }
        }

        $hasil = [];

        foreach ($fileFields as $field) {
            if (! $request->hasFile($field)) {
                // kalau datang dari simple-uploader, file ada di 'file'
                if (! $request->hasFile('file')) continue;
                $fileObj = $request->file('file');
            } else {
                $fileObj = $request->file($field);
            }

            $ext      = $fileObj->extension();
            $baseName = "{$pesertaId}_{$bidangId}_{$instansiId}_{$field}";
            $fileName = "{$baseName}.{$ext}";

            if (! $overwrite && $disk->exists("$folder/$fileName")) {
                $fileName = $baseName . '-' . now()->format('YmdHisv') . '.' . $ext;
            }

            $path = $fileObj->storeAs($folder, $fileName, 'public');

            $hasil[$field] = [
                'field' => $field,
                'path'  => $path,
                'url'   => $disk->url($path),
            ];
        }

        if (empty($hasil)) {
            return response()->json(['message' => 'Tidak ada file yang diupload.'], 422);
        }

        // kompatibel untuk single-file (simple-uploader mengharapkan {path, url})
        return count($hasil) === 1
            ? response()->json(reset($hasil), 201)
            : response()->json(['files' => $hasil], 201);
    }
}
