<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tes;
use App\Models\Peserta;
use App\Models\Percobaan;
use App\Models\JawabanUser;

class DashboardController extends Controller
{
    // ======================
    // DASHBOARD INDEX
    // ======================
    public function index()
    {
        $pesertaId = session('peserta_id');

        $surveyStatus = 'pending';

        $preTestAttempts = Percobaan::where('tes_id', 1)
            ->where('pesertaSurvei_id', $pesertaId)
            ->count();

        $preTestMax = 1;
        $postTestMax = 1;
        $monevMax    = 1;

        $postTestDone = Percobaan::where('tes_id', 2)
            ->where('pesertaSurvei_id', $pesertaId)
            ->exists();

        $monevDone = Percobaan::where('tes_id', 3)
            ->where('pesertaSurvei_id', $pesertaId)
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
        $pesertaId = session('peserta_id');

        $preTestAttempts = Percobaan::where('tes_id', 1)
            ->where('pesertaSurvei_id', $pesertaId)
            ->count();

        $preTestMax = 1;
        $postTestMax = 1;
        $monevMax    = 1;

        $postTestDone = Percobaan::where('tes_id', 2)
            ->where('pesertaSurvei_id', $pesertaId)
            ->exists();

        $monevDone = Percobaan::where('tes_id', 3)
            ->where('pesertaSurvei_id', $pesertaId)
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
    // STORE PESERTA dari MODAL HOME
    // ======================
    public function storePeserta(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:150',
            'sekolah' => 'required|string|max:150',
        ]);

        $nama    = strtolower(trim($request->nama));
        $sekolah = strtolower(trim($request->sekolah));

        // Cari peserta sesuai nama + sekolah (case-insensitive)
        $peserta = Peserta::whereRaw('LOWER(nama) = ?', [$nama])
            ->whereHas('instansi', function($q) use ($sekolah) {
                $q->whereRaw('LOWER(nama_instansi) = ?', [$sekolah]);
            })
            ->first();

        if (!$peserta) {
            return back()->with('error', 'Peserta dengan nama & sekolah tersebut tidak ditemukan.');
        }

        session(['peserta_id' => $peserta->id]);

        return redirect()->route('dashboard.home')->with('success', 'Data peserta berhasil dipilih!');
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
        return view('dashboard.pages.pre-test.pretest-start-form', compact('tes'));
    }

    public function pretestBegin(Request $request, $tesId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }

        $percobaan = Percobaan::create([
            'pesertaSurvei_id' => $pesertaId,
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
        $duration = $tes->durasi_menit * 60;
        $elapsed = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
        $remaining = max($duration - $elapsed, 0);

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

        $nextQ = $request->input('next_q');
        $totalQuestions = $percobaan->tes->pertanyaan()->count();

        if ($nextQ !== null && $nextQ < $totalQuestions) {
            return redirect()->route('dashboard.pretest.show', [
                'tes' => $percobaan->tes_id,
                'percobaan' => $percobaan->id,
                'q' => $nextQ
            ]);
        }

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
        return view('dashboard.pages.post-test.posttest-start-form', compact('tes'));
    }

    public function posttestBegin(Request $request, $tesId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }

        $percobaan = Percobaan::create([
            'pesertaSurvei_id' => $pesertaId,
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

        $duration = $tes->durasi_menit * 60;
        $elapsed = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
        $remaining = max($duration - $elapsed, 0);

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

        $nextQ = $request->input('next_q');
        $totalQuestions = $percobaan->tes->pertanyaan()->count();

        if ($nextQ !== null && $nextQ < $totalQuestions) {
            return redirect()->route('dashboard.posttest.show', [
                'tes' => $percobaan->tes_id,
                'percobaan' => $percobaan->id,
                'q' => $nextQ
            ]);
        }

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
    // MONEV
    // ======================
    public function monev()
    {
        $tes = Tes::where('sub_tipe', 'monev')->get();
        return view('dashboard.pages.monev.monev', compact('tes'));
    }

    public function monevStart(Tes $tes)
    {
        return view('dashboard.pages.monev.monev-start-form', compact('tes'));
    }

    public function monevBegin(Request $request, $tesId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }

        $percobaan = Percobaan::create([
            'pesertaSurvei_id' => $pesertaId,
            'tes_id'           => $tesId,
            'tipe'             => 'monev',
            'waktu_mulai'      => now(),
        ]);

        return redirect()->route('dashboard.monev.show', [
            'tes' => $tesId,
            'percobaan' => $percobaan->id,
        ]);
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
