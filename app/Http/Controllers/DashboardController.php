<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tes;
use App\Models\Peserta;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ======================
    // DASHBOARD INDEX
    // ======================
    public function index()
    {
        $user = Auth::user();
        $surveyStatus = $user && $user->survey ? 'done' : 'pending';

        return view('dashboard.index', compact('surveyStatus'));
    }

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

    public function pretestStart(Tes $tes)
    {
        $peserta = Peserta::all();
        return view('dashboard.pages.pre-test.pretest-start-form', compact('tes', 'peserta'));
    }

    public function pretestBegin(Request $request, $tesId)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
        ]);

        $percobaan = Percobaan::create([
            'peserta_id'  => $request->peserta_id,
            'tes_id'      => $tesId,
            'tipe'        => 'pretest',
            'waktu_mulai' => now(),
        ]);

        return redirect()->route('dashboard.pretest.show', [
            'tes' => $tesId,
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

    public function pretestSubmit(Request $request, ?Percobaan $percobaan = null)
    {
        if (!$percobaan) {
            $percobaanId = $request->input('percobaan_id')
                ?? $request->route('percobaan')
                ?? $request->query('percobaan');
            $percobaan = Percobaan::find($percobaanId);
            if (!$percobaan) abort(404, 'Percobaan tidak ditemukan.');
        }

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

    // ======================
    // POST-TEST
    // ======================
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

    public function posttestBegin(Request $request, $tesId)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
        ]);

        $percobaan = Percobaan::create([
            'pesertaSurvei_id' => $request->peserta_id,
            'tes_id'           => $tesId,
            'tipe'             => 'posttest',
            'waktu_mulai'      => now(),
        ]);

        return redirect()->route('dashboard.posttest.show', [
            'tes' => $tesId,
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

    public function posttestSubmit(Request $request, ?Percobaan $percobaan = null)
    {
        if (!$percobaan) {
            $percobaanId = $request->input('percobaan_id')
                ?? $request->route('percobaan')
                ?? $request->query('percobaan');
            $percobaan = Percobaan::find($percobaanId);
            if (!$percobaan) abort(404, 'Percobaan tidak ditemukan.');
        }

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

    // ======================
    // SURVEY
    // ======================
    public function survey()
    {
        return view('dashboard.pages.survey');
    }

    public function surveySubmit(Request $request)
    {
        return redirect()->route('dashboard.survey')->with('success', 'Survey berhasil dikerjakan!');
    }

    // ======================
    // Helper: hitung skor
    // ======================
    protected function hitungSkor(Percobaan $percobaan): int
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawabans']);
        $jawabanCollection = $percobaan->jawabanUser ?? collect();

        $total = $jawabanCollection->count();
        if ($total === 0) {
            return 0;
        }

        $benar = $jawabanCollection->filter(fn($j) => ($j->opsiJawabans->apakah_benar ?? false))->count();

        return (int) round(($benar / $total) * 100);
    }
}