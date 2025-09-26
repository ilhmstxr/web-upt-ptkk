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
        $userId = Auth::id();

        $percobaan = Percobaan::firstOrCreate(
            [
                'peserta_id' => $userId,
                'tes_id'           => $tes->id,
            ],
            [
                'waktu_mulai' => now(),
            ]
        );

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawabans')->get();

        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        return view('tes.show', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'currentQuestionIndex',
            'pertanyaanList'
        ));
    }

    /**
     * Menyimpan jawaban user dan hitung skor jika selesai.
     */
    public function submit(Request $request, Percobaan $percobaan)
    {
        $userId = Auth::id();

        // Pastikan percobaan milik user
        if ($percobaan->peserta_id != $userId) {
            abort(403, 'Unauthorized');
        }

        $jawaban = $request->input('jawaban', []);

        foreach ($jawaban as $pertanyaan_id => $opsi_jawaban_id) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id'  => $percobaan->id,
                    'pertanyaan_id' => $pertanyaan_id,
                ],
                [
                    'opsi_jawaban_id' => $opsi_jawaban_id,
                ]
            );
        }

        // Ambil semua pertanyaan
        $pertanyaanList = $percobaan->tes->pertanyaan()->get();
        $total = $pertanyaanList->count();

        $currentQuestionIndex = (int) $request->query('q', 0);
        $nextQuestion = $currentQuestionIndex + 1;

        if ($nextQuestion >= $total) {
            // Semua soal selesai, hitung skor
            $percobaan->waktu_selesai = now();
            $percobaan->skor = $this->hitungSkor($percobaan);
            $percobaan->save();

            return redirect()->route('tes.result', $percobaan->id);
        }

        // Lanjut ke pertanyaan berikutnya
        return redirect()->route('tes.show', [
            'tes' => $percobaan->tes_id,
            'q'   => $nextQuestion
        ]);
    }

    /**
     * Menampilkan hasil tes
     */
    public function result(Percobaan $percobaan)
    {
        // Pastikan percobaan milik user
        if ($percobaan->peserta_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('tes.result', compact('percobaan'));
    }

    /**
     * Hitung skor berdasarkan jawaban user
     */
    protected function hitungSkor(Percobaan $percobaan)
    {
        $jawabanUsers = $percobaan->jawabanUsers()->with('opsiJawaban')->get();
        $total = $jawabanUsers->count();

        if ($total === 0) return 0;

        $benar = $jawabanUsers->filter(
            fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar
        )->count();

        // Bisa pilih: jumlah benar atau persentase
        return round(($benar / $total) * 100);
    }
}
