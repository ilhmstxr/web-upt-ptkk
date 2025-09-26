<?php

namespace App\Http\Controllers;

use App\Models\Tes;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use Illuminate\Http\Request;

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
     * Menampilkan halaman tes untuk peserta.
     * Membuat percobaan baru jika belum ada.
     */
    public function show(Tes $tes, Request $request)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan pilih/aktifkan peserta terlebih dahulu.');
        }

        // 1 percobaan per peserta per tes (ubah sesuai kebijakanmu)
        $percobaan = Percobaan::firstOrCreate(
            ['peserta_id' => $pesertaId, 'tes_id' => $tes->id],
            ['waktu_mulai' => now()]
        );

        // Pastikan relasi di model Pertanyaan: function opsiJawaban() { ... }
        $pertanyaanList = $tes->pertanyaan()->with('opsiJawaban')->get();

        $currentQuestionIndex = max(0, (int) $request->query('q', 0));
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        // Jika index di luar jangkauan, arahkan ke hasil
        if (!$pertanyaan) {
            return redirect()->route('tes.result', $percobaan->id);
        }

        return view('tes.show', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'currentQuestionIndex',
            'pertanyaanList'
        ));
    }

    /**
     * Simpan jawaban peserta dan hitung skor jika selesai.
     */
    public function submit(Request $request, Percobaan $percobaan)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId || $percobaan->peserta_id !== $pesertaId) {
            abort(403, 'Unauthorized');
        }

        $jawaban = $request->input('jawaban', []);

        foreach ($jawaban as $pertanyaanId => $opsiJawabanId) {
            JawabanUser::updateOrCreate(
                ['percobaan_id' => $percobaan->id, 'pertanyaan_id' => $pertanyaanId],
                ['opsi_jawaban_id' => $opsiJawabanId]
            );
        }

        $total = $percobaan->tes->pertanyaan()->count();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $nextQuestion = $currentQuestionIndex + 1;

        if ($nextQuestion >= $total) {
            // Selesai: hitung skor & simpan
            // Pakai helper di model kalau ada:
            if (method_exists($percobaan, 'hitungDanSimpanSkor')) {
                $percobaan->hitungDanSimpanSkor();
            } else {
                $percobaan->waktu_selesai = now();
                $percobaan->skor = $this->hitungSkor($percobaan);
                $percobaan->save();
            }

            return redirect()->route('tes.result', $percobaan->id);
        }

        // Lanjut ke soal berikutnya
        return redirect()->route('tes.show', [
            'tes' => $percobaan->tes_id,
            'q'   => $nextQuestion
        ]);
    }

    /**
     * Menampilkan hasil tes.
     */
    public function result(Percobaan $percobaan)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId || $percobaan->peserta_id !== $pesertaId) {
            abort(403, 'Unauthorized');
        }

        $percobaan->loadMissing(['tes', 'jawabanUser.opsiJawaban']);
        return view('tes.result', compact('percobaan'));
    }

    /**
     * Hitung skor (persentase) berdasarkan jawaban peserta.
     * Dipakai jika model belum punya helper hitungDanSimpanSkor().
     */
    protected function hitungSkor(Percobaan $percobaan): float|int
    {
        // Pastikan relasi di model Percobaan: function jawabanUser() { return $this->hasMany(...); }
        $jawabanUsers = $percobaan->jawabanUser()->with('opsiJawaban')->get();
        $total = $jawabanUsers->count();

        if ($total === 0) return 0;

        $benar = $jawabanUsers->filter(
            fn($j) => ($j->opsiJawaban->apakah_benar ?? false)
        )->count();

        return round(($benar / $total) * 100, 2);
    }
}
