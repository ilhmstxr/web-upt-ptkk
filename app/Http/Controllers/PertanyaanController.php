<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PertanyaanController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Pertanyaan $pertanyaan)
    {
        //
    }

    public function edit(Pertanyaan $pertanyaan)
    {
        //
    }

    public function update(Request $request, Pertanyaan $pertanyaan) {}

    // public function update(Request $request, Pertanyaan $pertanyaan)
    // {
    //     $data = $request->validate([
    //         'nomor' => ['required', 'integer', 'min:1'],
    //         'teks_pertanyaan' => ['required', 'string'],
    //         'tipe_jawaban' => ['required', Rule::in(['pilihan_ganda', 'skala_likert', 'esai'])],
    //         'opsiJawabans' => [Rule::requiredIf(fn () => $request->input('tipe_jawaban') !== 'esai'), 'array'],
    //         'opsiJawabans.*.id' => ['nullable', 'integer', 'exists:opsi_jawaban,id'],
    //         'opsiJawabans.*.teks_opsi' => ['required_with:opsiJawabans', 'string'],
    //         'opsiJawabans.*.gambar' => ['nullable'],
    //         'opsiJawabans.*.apakah_benar' => ['nullable', 'boolean'],
    //     ]);

    //     if (($data['tipe_jawaban'] ?? null) === 'esai') {
    //         $data['opsiJawabans'] = [];
    //     }

    //     if (($data['tipe_jawaban'] ?? null) === 'pilihan_ganda') {
    //         $jumlahBenar = collect($data['opsiJawabans'] ?? [])->where('apakah_benar', true)->count();
    //         if ($jumlahBenar !== 1) {
    //             throw ValidationException::withMessages(['opsiJawabans' => 'Untuk tipe pilihan_ganda wajib tepat 1 opsi benar.']);
    //         }
    //     }

    //     return DB::transaction(function () use ($pertanyaan, $data) {
    //         $pertanyaan->fill([
    //             'nomor' => $data['nomor'],
    //             'teks_pertanyaan' => $data['teks_pertanyaan'],
    //             'tipe_jawaban' => $data['tipe_jawaban'],
    //         ]);
    //         $pertanyaan->save();

    //         if ($data['tipe_jawaban'] === 'esai') {
    //             $pertanyaan->opsiJawabans()->delete();
    //             return $pertanyaan->fresh('opsiJawabans');
    //         }

    //         $items = collect($data['opsiJawabans'] ?? [])->values();
    //         $keepIds = $items->pluck('id')->filter()->all();

    //         if (count($keepIds)) {
    //             $pertanyaan->opsiJawabans()->whereNotIn('id', $keepIds)->delete();
    //         } else {
    //             $pertanyaan->opsiJawabans()->delete();
    //         }

    //         foreach ($items as $index => $item) {
    //             $gambarInput = $item['gambar'] ?? null;

    //             if ($gambarInput instanceof UploadedFile) {
    //                 $destinationRelative = PendaftaranController::LAMPIRAN_DESTINATION;
    //                 $destinationPath = public_path($destinationRelative);

    //                 if (! File::isDirectory($destinationPath)) {
    //                     File::makeDirectory($destinationPath, 0755, true);
    //                 }

    //                 $originalName = pathinfo($gambarInput->getClientOriginalName(), PATHINFO_FILENAME);
    //                 $baseName = Str::slug($originalName ?: 'opsi');
    //                 $extension = strtolower($gambarInput->getClientOriginalExtension() ?: $gambarInput->extension());
    //                 $fileName = $pertanyaan->id . '_' . ($index + 1) . '_' . $baseName . '.' . $extension;

    //                 $gambarInput->move($destinationPath, $fileName);

    //                 $gambarPath = $destinationRelative . '/' . $fileName;
    //             } else {
    //                 $gambarPath = is_string($gambarInput) && $gambarInput !== '' ? $gambarInput : null;
    //             }

    //             $payload = [
    //                 'pertanyaan_id' => $pertanyaan->id,
    //                 'teks_opsi' => $item['teks_opsi'],
    //                 'gambar' => $gambarPath,
    //                 'apakah_benar' => (bool) ($item['apakah_benar'] ?? false),
    //                 'sort_order' => $index,
    //             ];

    //             if (! empty($item['id'])) {
    //                 $pertanyaan->opsiJawabans()->whereKey($item['id'])->update($payload);
    //             } else {
    //                 $pertanyaan->opsiJawabans()->create($payload);
    //             }
    //         }

    //         return $pertanyaan->fresh('opsiJawabans');
    //     });
    // }

    public function destroy(Pertanyaan $pertanyaan)
    {
        //
    }
}
