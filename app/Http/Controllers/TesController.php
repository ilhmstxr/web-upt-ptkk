<?php

namespace App\Http\Controllers;

use App\Models\Tes;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TesController extends Controller
{
    /**
     * Menampilkan daftar semua tes.
     */
    public function index()
    {
        $tes = Tes::with(['bidang', 'pelatihan'])->get();
        return view('tes.index', compact('tes'));
    }

    /**
     * Menampilkan halaman tes untuk user.
     * Membuat percobaan baru jika belum ada.
     */
    public function show(Tes $tes, Request $request)
    {
        $percobaan = Percobaan::firstOrCreate(
            [
                'peserta_id' => Auth::id(),
                'tes_id' => $tes->id,
            ],
            [
                'waktu_mulai' => now(),
            ]
        );

        // Ambil semua pertanyaan beserta opsi jawaban
        $pertanyaanList = $tes->pertanyaans()->with('opsiJawabans')->get();

        // Tentukan pertanyaan saat ini (default: 0)
        $currentQuestion = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestion);

        return view('tes.show', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'currentQuestion',
            'pertanyaanList'
        ));
    }

    /**
     * Menyimpan jawaban user dan hitung skor jika selesai.
     */
    public function submit(Request $request, Tes $tes)
    {
        $percobaan = Percobaan::where('peserta_id', Auth::id())
            ->where('tes_id', $tes->id)
            ->firstOrFail();

        $jawaban = $request->input('jawaban', []); // ['pertanyaan_id' => 'opsi_jawabans_id']

        // Simpan jawaban user
        foreach ($jawaban as $pertanyaan_id => $opsi_jawabans_id) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id' => $percobaan->id,
                    'pertanyaan_id' => $pertanyaan_id,
                ],
                [
                    'opsi_jawabans_id' => $opsi_jawabans_id, // nama kolom diperbaiki
                ]
            );
        }

        // Ambil semua pertanyaan
        $pertanyaanList = $tes->pertanyaans()->get();
        $total = $pertanyaanList->count();

        $currentQuestion = (int) $request->query('q', 0);
        $nextQuestion = $currentQuestion + 1;

        if ($nextQuestion >= $total) {
            // Semua soal selesai, hitung skor
            $percobaan->waktu_selesai = now();
            $percobaan->skor = $this->hitungSkor($percobaan);
            $percobaan->save();

            return redirect()->route('tes.result', $percobaan->id);
        }

        // Lanjut ke pertanyaan berikutnya
        return redirect()->route('tes.show', ['tes' => $tes->id, 'q' => $nextQuestion]);
    }

    /**
     * Menampilkan hasil tes
     */
    public function result(Percobaan $percobaan)
    {
        return view('tes.result', compact('percobaan'));
    }

    /**
     * Hitung skor berdasarkan jawaban user
     */
    protected function hitungSkor(Percobaan $percobaan)
    {
        $jawabanUser = $percobaan->jawabanUser()->with('opsiJawabans')->get();
        $total = $jawabanUser->count();

        if ($total === 0) return 0;

        // 'apakah_benar' sesuai kolom di tabel opsi_jawaban
        $benar = $jawabanUser->filter(fn($j) => $j->opsiJawabans->apakah_benar)->count();

        return round(($benar / $total) * 100);
    }
}
