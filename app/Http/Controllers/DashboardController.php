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

    // Status survey
    $surveyStatus = $user && $user->survey ? 'done' : 'pending';

    // Hitung percobaan pre-test (tes_id=1)
    $preTestAttempts = \App\Models\Percobaan::where('tes_id', 1)
        ->where('pesertaSurvei_id', $user->id ?? null)
        ->count();

    $preTestMax = 1;
    $postTestMax = 1;
    $monevMax    = 1;

    // Cek post-test & monev
    $postTestDone = \App\Models\Percobaan::where('tes_id', 2)
        ->where('pesertaSurvei_id', $user->id ?? null)
        ->exists();

    $monevDone = \App\Models\Percobaan::where('tes_id', 3)
        ->where('pesertaSurvei_id', $user->id ?? null)
        ->exists();

    return view('dashboard.index', compact(
        'surveyStatus',
        'preTestAttempts',
        'preTestMax',
        'postTestMax',
        'monevMax',
        'postTestDone',
        'monevDone'
    ));
}

// ======================
// HOME & PROFILE
// ======================
public function home()
{
    $user = Auth::user();

    // Hitung percobaan pre-test (tes_id=1)
    $preTestAttempts = \App\Models\Percobaan::where('tes_id', 1)
        ->where('pesertaSurvei_id', $user->id ?? null)
        ->count();

    $preTestMax = 1;
    $postTestMax = 1;
    $monevMax    = 1;

    // Cek post-test & monev
    $postTestDone = \App\Models\Percobaan::where('tes_id', 2)
        ->where('pesertaSurvei_id', $user->id ?? null)
        ->exists();

    $monevDone = \App\Models\Percobaan::where('tes_id', 3)
        ->where('pesertaSurvei_id', $user->id ?? null)
        ->exists();

    return view('dashboard.pages.home', compact(
        'preTestAttempts',
        'preTestMax',
        'postTestMax',
        'monevMax',
        'postTestDone',
        'monevDone'
    ));
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

    // hitung waktu
    $duration = $tes->durasi_menit * 60; // total detik
    $elapsed = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
    $remaining = max($duration - $elapsed, 0);

    // kalau sudah habis
    if ($remaining <= 0) {
        $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();
        $percobaan->skor = $percobaan->skor ?? $this->hitungSkor($percobaan);
        $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
        $percobaan->save();

        return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id])
            ->with('error', 'Waktu tes sudah habis.');
    }

    $pertanyaanList = $tes->pertanyaan()->with('opsiJawabans')->get();
    $currentQuestionIndex = (int) $request->query('q', 0);
    $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

    if (!$pertanyaan) {
        return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
    }

    return view('dashboard.pages.pre-test.pretest-start', compact(
        'tes',
        'pertanyaan',
        'percobaan',
        'pertanyaanList',
        'currentQuestionIndex',
        'remaining'
    ));
}


    public function pretestSubmit(Request $request, Percobaan $percobaan)
{
    // Simpan jawaban
    $data = $request->input('jawaban', []);
    foreach ($data as $pertanyaanId => $opsiId) {
        JawabanUser::updateOrCreate(
            [
                'percobaan_id' => $percobaan->id,
                'pertanyaan_id' => $pertanyaanId,
            ],
            [
                'opsi_jawaban_id' => $opsiId,
            ]
        );
    }

    // Ambil soal berikutnya
    $nextQ = $request->input('next_q');
    $totalQuestions = $percobaan->tes->pertanyaan()->count();

    // Kalau tombol nomor / prev / next ditekan
    if ($nextQ !== null && $nextQ < $totalQuestions) {
        return redirect()->route('dashboard.pretest.show', [
            'tes' => $percobaan->tes_id,
            'percobaan' => $percobaan->id,
            'q' => $nextQ
        ]);
    }

    // Kalau sudah soal terakhir atau "Selesai" ditekan
    $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();
    $percobaan->skor = $percobaan->skor ?? $this->hitungSkor($percobaan);
    $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
    $percobaan->save();

    return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
}

    public function pretestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'tes', 'pesertaSurvei']);
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

    // hitung waktu
    $duration = $tes->durasi_menit * 60; // total detik
    $elapsed = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
    $remaining = max($duration - $elapsed, 0);

    // kalau sudah habis
    if ($remaining <= 0) {
        $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();
        $percobaan->skor = $percobaan->skor ?? $this->hitungSkor($percobaan);
        $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
        $percobaan->save();

        return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id])
            ->with('error', 'Waktu tes sudah habis.');
    }

    $pertanyaanList = $tes->pertanyaan()->with('opsiJawabans')->get();
    $currentQuestionIndex = (int) $request->query('q', 0);
    $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

    if (!$pertanyaan) {
        return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
    }

    return view('dashboard.pages.post-test.posttest-start', compact(
        'tes',
        'pertanyaan',
        'percobaan',
        'pertanyaanList',
        'currentQuestionIndex',
        'remaining'
    ));
}


    public function posttestSubmit(Request $request, Percobaan $percobaan)
{
    // Simpan jawaban
    $data = $request->input('jawaban', []);
    foreach ($data as $pertanyaanId => $opsiId) {
        JawabanUser::updateOrCreate(
            [
                'percobaan_id' => $percobaan->id,
                'pertanyaan_id' => $pertanyaanId,
            ],
            [
                'opsi_jawaban_id' => $opsiId,
            ]
        );
    }

    // Ambil soal berikutnya
    $nextQ = $request->input('next_q');
    $totalQuestions = $percobaan->tes->pertanyaan()->count();

    // Kalau tombol nomor / prev / next ditekan
    if ($nextQ !== null && $nextQ < $totalQuestions) {
        return redirect()->route('dashboard.posttest.show', [
            'tes' => $percobaan->tes_id,
            'percobaan' => $percobaan->id,
            'q' => $nextQ
        ]);
    }

    // Kalau sudah soal terakhir atau "Selesai" ditekan
    $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();
    $percobaan->skor = $percobaan->skor ?? $this->hitungSkor($percobaan);
    $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
    $percobaan->save();

    return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
}


    public function posttestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'tes', 'pesertaSurvei']);
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