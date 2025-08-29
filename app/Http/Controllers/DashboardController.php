<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tes;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ======================
    // HOME & PROFILE
    // ======================
    public function home()
    {
        return view('dashboard.pages.home');
    }

    public function profile()
    {
        return view('dashboard.pages.profile');
    }

    public function materi()
    {
        return view('dashboard.pages.materi');
    }

    public function materiShow($materi)
    {
        return view('dashboard.pages.materi-show', compact('materi'));
    }

    // ======================
    // PRE-TEST
    // ======================
    public function pretest()
    {
        $tes = Tes::where('sub_tipe', 'pre-test')->get();
        return view('dashboard.pages.pre-test.pretest', compact('tes'));
    }

    /**
     * Tampilkan soal pretest.
     * Route: GET /dashboard/pretest/{tes}?q=...
     */
    public function pretestShow(Tes $tes, Request $request)
    {
        $userId = Auth::id();

        // Buat percobaan baru jika belum ada, atau ambil yang sudah ada
        $percobaan = Percobaan::firstOrCreate(
            ['peserta_id' => $userId, 'tes_id' => $tes->id],
            ['waktu_mulai' => now()]
        );

        // Load pertanyaan dan opsi
        $pertanyaanList = $tes->pertanyaans()->with('opsiJawabans')->get();
        $currentQuestionIndex = max(0, (int) $request->query('q', 0));
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        // Jika tidak ada pertanyaan (mis. index out of range), arahkan ke hasil
        if (!$pertanyaan) {
            return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
        }

        // Durasi berjalan (detik)
        $elapsedSeconds = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;

        return view('dashboard.pages.pre-test.pretest-start', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'pertanyaanList',
            'currentQuestionIndex',
            'elapsedSeconds'
        ));
    }

    /**
     * Terima submit jawaban pretest.
     * Route: POST /dashboard/pretest/{percobaan}/submit
     */
    public function pretestSubmit(Request $request, ?Percobaan $percobaan = null)
    {
        // Jika percobaan null (mis. form lama), ambil dari request
        if (!$percobaan) {
            $percobaanId = $request->input('percobaan_id') ?? $request->route('percobaan');
            $percobaan = Percobaan::find($percobaanId);
            if (!$percobaan) {
                abort(404, 'Percobaan tidak ditemukan.');
            }
        }

        // Verifikasi kepemilikan
        if ($percobaan->peserta_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Simpan jawaban (updateOrCreate)
        $jawabanData = $request->input('jawaban', []);
        foreach ($jawabanData as $pertanyaanId => $opsiId) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id' => $percobaan->id,
                    'pertanyaan_id' => $pertanyaanId,
                ],
                ['opsi_jawabans_id' => $opsiId]
            );
        }

        // Reload relasi jawaban
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'jawabanUser.pertanyaan', 'tes.pertanyaans']);

        $currentIndex = (int) $request->query('q', 0) + 1;
        $totalQuestions = $percobaan->tes->pertanyaans()->count();

        if ($currentIndex >= $totalQuestions) {
            // Semua soal selesai -> hitung skor, waktu selesai, dan status lulus
            $percobaan->waktu_selesai = now();
            $percobaan->skor = $this->hitungSkor($percobaan);
            // ambil passing_score dari tes jika ada, default 70
            $passingScore = $percobaan->tes->passing_score ?? 70;
            $percobaan->lulus = $percobaan->skor >= $passingScore;
            $percobaan->save();

            return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
        }

        // Redirect ke soal berikutnya
        return redirect()->route('dashboard.pretest.show', [
            'tes' => $percobaan->tes_id,
            'q' => $currentIndex
        ]);
    }

    public function pretestResult(Percobaan $percobaan)
    {
        // Pastikan pemilik
        if ($percobaan->peserta_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Load jawaban & tes
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'jawabanUser.opsiJawabans', 'tes']);

        return view('dashboard.pages.pre-test.pretest-result', compact('percobaan'));
    }

    // ======================
    // POST-TEST (mirip pretest)
    // ======================
    public function posttest()
    {
        $tes = Tes::where('sub_tipe', 'post-test')->get();
        return view('dashboard.pages.post-test.posttest', compact('tes'));
    }

    public function posttestShow(Tes $tes, Request $request)
    {
        $userId = Auth::id();

        $percobaan = Percobaan::firstOrCreate(
            ['peserta_id' => $userId, 'tes_id' => $tes->id],
            ['waktu_mulai' => now()]
        );

        $pertanyaanList = $tes->pertanyaans()->with('opsiJawabans')->get();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
        }

        $elapsedSeconds = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;

        return view('dashboard.pages.post-test.posttest-start', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'pertanyaanList',
            'currentQuestionIndex',
            'elapsedSeconds'
        ));
    }

    public function posttestSubmit(Request $request, ?Percobaan $percobaan = null)
    {
        if (!$percobaan) {
            $percobaanId = $request->input('percobaan_id') ?? $request->route('percobaan');
            $percobaan = Percobaan::find($percobaanId);
            if (!$percobaan) {
                abort(404, 'Percobaan tidak ditemukan.');
            }
        }

        if ($percobaan->peserta_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($request->has('jawaban')) {
            foreach ($request->jawaban as $pertanyaanId => $opsiId) {
                JawabanUser::updateOrCreate(
                    [
                        'percobaan_id' => $percobaan->id,
                        'pertanyaan_id' => $pertanyaanId,
                    ],
                    ['opsi_jawabans_id' => $opsiId]
                );
            }
        }

        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'jawabanUser.opsiJawabans', 'tes.pertanyaans']);

        $currentIndex = (int) $request->query('q', 0) + 1;
        $totalQuestions = $percobaan->tes->pertanyaans()->count();

        if ($currentIndex >= $totalQuestions) {
            $percobaan->waktu_selesai = now();
            $percobaan->skor = $this->hitungSkor($percobaan);
            $passingScore = $percobaan->tes->passing_score ?? 70;
            $percobaan->lulus = $percobaan->skor >= $passingScore;
            $percobaan->save();

            return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
        }

        return redirect()->route('dashboard.posttest.show', [
            'tes' => $percobaan->tes_id,
            'q' => $currentIndex
        ]);
    }

    public function posttestResult(Percobaan $percobaan)
    {
        if ($percobaan->peserta_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'jawabanUser.opsiJawabans', 'tes']);

        return view('dashboard.pages.post-test.posttest-result', compact('percobaan'));
    }

    // ======================
    // FEEDBACK
    // ======================
    public function feedback()
    {
        return view('dashboard.pages.feedback');
    }

    public function feedbackSubmit(Request $request)
    {
        return redirect()->route('dashboard.feedback')->with('success', 'Feedback berhasil dikirim!');
    }

    // ======================
    // Helper: hitung skor
    // ======================
    protected function hitungSkor(Percobaan $percobaan): int
    {
        // load jawaban beserta opsi jawaban
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'jawabanUser.opsiJawabans']);

        // preferensi relasi plural jika ada
        $jawabanCollection = $percobaan->jawabanUser ?? $percobaan->jawabanUser ?? collect();

        $total = $jawabanCollection->count();
        if ($total === 0) {
            return 0;
        }

        // hitung benar (defensif terhadap nama properti pada opsi jawaban)
        $benar = $jawabanCollection->filter(function ($j) {
            // beberapa model mungkin punya relasi 'opsiJawabans' (yang kita pakai)
            // gunakan null-safe operator
            return ($j->opsiJawabans->apakah_benar ?? $j->opsiJawabans->apakah_benar ?? false);
        })->count();

        return (int) round(($benar / $total) * 100);
    }
}