<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tes;
use App\Models\Peserta;
use App\Models\Percobaan;
use App\Models\JawabanUser;

class DashboardController extends Controller
{
    /**
     * Dashboard Home
     */
    public function home()
    {
        $peserta = Peserta::all();
        $pesertaId = session('peserta_id');
        $pesertaAktif = $pesertaId ? Peserta::find($pesertaId) : null;

        return view('dashboard.pages.home', compact('peserta', 'pesertaAktif'));
    }

    /**
     * Pilih Peserta (set peserta_id di session)
     */
    public function setPeserta(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
        ]);

        session(['peserta_id' => $request->peserta_id]);

        return redirect()->route('dashboard.home')
            ->with('success', 'Peserta berhasil dipilih!');
    }

    /**
     * Unset Peserta (hapus peserta_id dari session)
     * NOTE: route untuk method ini sebaiknya POST (bukan GET) agar konsisten dan aman.
     */
    public function unsetPeserta(Request $request)
    {
        $request->session()->forget('peserta_id');

        return redirect()->route('dashboard.home')
            ->with('success', 'Silakan pilih peserta untuk melanjutkan.');
    }

    /**
     * Logout - hapus semua session & token
     */
    public function logout(Request $request)
    {
        // Hapus peserta_id saja dulu, lalu invalidate session
        $request->session()->forget('peserta_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.home')
            ->with('success', 'Logout berhasil, silakan pilih peserta kembali.');
    }

    /**
     * Profile view
     */
    public function profile()
    {
        return view('dashboard.pages.profile');
    }

    /**
     * Materi listing
     */
    public function materi()
    {
        return view('dashboard.pages.materi');
    }

    /**
     * Materi single view
     */
    public function materiShow($materi)
    {
        return view('dashboard.pages.materi-show', compact('materi'));
    }

    /**
     * Survey (feedback)
     */
    public function survey()
    {
        return view('dashboard.pages.survey');
    }

    public function surveySubmit(Request $request)
    {
        // proses survey jika ada (disederhanakan)
        return redirect()->route('dashboard.survey')
            ->with('success', 'Survey berhasil dikerjakan!');
    }

    /**
     * Pre-test: daftar, start, begin, show, submit, result
     */
    public function pretest()
    {
        $tes = Tes::where('sub_tipe', 'pre-test')->get();
        return view('dashboard.pages.pre-test.pretest', compact('tes'));
    }

    public function pretestStart(Tes $tes)
    {
        $peserta = Peserta::all();
        return view('dashboard.pages.pre-test.pretest-start-form', compact('tes', 'peserta'));
    }

    public function pretestBegin(Request $request, Tes $tes)
    {
        $request->validate(['peserta_id' => 'required|exists:peserta,id']);

        $percobaan = Percobaan::create([
            'peserta_id' => $request->peserta_id,
            'tes_id' => $tes->id,
            'tipe' => 'pretest',
            'waktu_mulai' => now(),
        ]);

        return redirect()->route('dashboard.pretest.show', [
            'tes' => $tes->id,
            'percobaan' => $percobaan->id,
        ]);
    }

    public function pretestShow(Tes $tes, Request $request)
    {
        $percobaanId = (int) $request->query('percobaan');
        if (!$percobaanId) {
            return redirect()->route('dashboard.pretest.start', $tes->id)
                ->with('error', 'Pilih peserta terlebih dahulu untuk memulai pre-test.');
        }

        $percobaan = Percobaan::findOrFail($percobaanId);
        if ($percobaan->tes_id !== $tes->id) abort(404);

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawabans')->get();
        $currentQuestionIndex = max(0, (int) $request->query('q', 0));
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
        }

        $elapsedSeconds = $percobaan->waktu_mulai
            ? now()->diffInSeconds($percobaan->waktu_mulai)
            : 0;

        return view('dashboard.pages.pre-test.pretest-start', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'pertanyaanList',
            'currentQuestionIndex',
            'elapsedSeconds'
        ));
    }

    public function pretestSubmit(Request $request, Percobaan $percobaan)
    {
        $jawabanData = $request->input('jawaban', []);
        foreach ($jawabanData as $pertanyaanId => $opsiId) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id' => $percobaan->id,
                    'pertanyaan_id' => $pertanyaanId,
                ],
                ['opsi_jawaban_id' => $opsiId]
            );
        }

        $currentIndex = (int) $request->query('q', 0) + 1;
        $totalQuestions = $percobaan->tes->pertanyaan()->count();

        if ($currentIndex >= $totalQuestions) {
            $percobaan->waktu_selesai = now();
            $percobaan->skor = $this->hitungSkor($percobaan);
            $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
            $percobaan->save();

            return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
        }

        return redirect()->route('dashboard.pretest.show', [
            'tes' => $percobaan->tes_id,
            'q' => $currentIndex,
            'percobaan' => $percobaan->id,
        ]);
    }

    public function pretestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'tes', 'peserta']);
        return view('dashboard.pages.pre-test.pretest-result', compact('percobaan'));
    }

    /**
     * Post-test: daftar, start, begin, show, submit, result
     */
    public function posttest()
    {
        $tes = Tes::where('sub_tipe', 'post-test')->get();
        return view('dashboard.pages.post-test.posttest', compact('tes'));
    }

    public function posttestStart(Tes $tes)
    {
        $peserta = Peserta::all();
        return view('dashboard.pages.post-test.posttest-start-form', compact('tes', 'peserta'));
    }

    public function posttestBegin(Request $request, Tes $tes)
    {
        $request->validate(['peserta_id' => 'required|exists:peserta,id']);

        $percobaan = Percobaan::create([
            'peserta_id' => $request->peserta_id,
            'tes_id' => $tes->id,
            'tipe' => 'posttest',
            'waktu_mulai' => now(),
        ]);

        return redirect()->route('dashboard.posttest.show', [
            'tes' => $tes->id,
            'percobaan' => $percobaan->id,
        ]);
    }

    public function posttestShow(Tes $tes, Request $request)
    {
        $percobaanId = (int) $request->query('percobaan');
        if (!$percobaanId) {
            return redirect()->route('dashboard.posttest.start', $tes->id)
                ->with('error', 'Pilih peserta terlebih dahulu untuk memulai post-test.');
        }

        $percobaan = Percobaan::findOrFail($percobaanId);
        if ($percobaan->tes_id !== $tes->id) abort(404);

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawabans')->get();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
        }

        $elapsedSeconds = $percobaan->waktu_mulai
            ? now()->diffInSeconds($percobaan->waktu_mulai)
            : 0;

        return view('dashboard.pages.post-test.posttest-start', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'pertanyaanList',
            'currentQuestionIndex',
            'elapsedSeconds'
        ));
    }

    public function posttestSubmit(Request $request, Percobaan $percobaan)
    {
        $jawabanData = $request->input('jawaban', []);
        foreach ($jawabanData as $pertanyaanId => $opsiId) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id' => $percobaan->id,
                    'pertanyaan_id' => $pertanyaanId,
                ],
                ['opsi_jawaban_id' => $opsiId]
            );
        }

        $currentIndex = (int) $request->query('q', 0) + 1;
        $totalQuestions = $percobaan->tes->pertanyaan()->count();

        if ($currentIndex >= $totalQuestions) {
            $percobaan->waktu_selesai = now();
            $percobaan->skor = $this->hitungSkor($percobaan);
            $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
            $percobaan->save();

            return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
        }

        return redirect()->route('dashboard.posttest.show', [
            'tes' => $percobaan->tes_id,
            'q' => $currentIndex,
            'percobaan' => $percobaan->id,
        ]);
    }

    public function posttestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'tes', 'peserta']);
        return view('dashboard.pages.post-test.posttest-result', compact('percobaan'));
    }

    /**
     * Progress view (stub) — pastikan view exists or adjust accordingly
     */
    public function progress()
    {
        return view('dashboard.pages.progress');
    }

    /**
     * Helper: hitung skor berdasarkan jawaban user
     */
    protected function hitungSkor(Percobaan $percobaan): int
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawabans']);
        $jawabanCollection = $percobaan->jawabanUser ?? collect();

        $total = $jawabanCollection->count();
        if ($total === 0) return 0;

        $benar = $jawabanCollection->filter(fn($j) => ($j->opsiJawabans->apakah_benar ?? false))->count();

        return (int) round(($benar / $total) * 100);
    }
}
