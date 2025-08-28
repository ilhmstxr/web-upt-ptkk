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
    public function home() {
        return view('dashboard.pages.home');
    }

    public function profile() {
        return view('dashboard.pages.profile');
    }

    public function materi() {
        return view('dashboard.pages.materi');
    }

    public function materiShow($materi) {
        return view('dashboard.pages.materi-show', compact('materi'));
    }

    // ======================
    // PRE-TEST
    // ======================
    public function pretest() {
        $tes = Tes::all();
        return view('dashboard.pages.pre-test.pretest', compact('tes'));
    }

    public function pretestShow(Tes $tes) {
        $userId = Auth::id();

        // Ambil atau buat percobaan
        $percobaan = Percobaan::firstOrCreate(
            ['peserta_id' => $userId, 'tes_id' => $tes->id],
            ['waktu_mulai' => now()]
        );

        // Ambil pertanyaan yang belum dijawab
        $pertanyaan = $tes->pertanyaans()
            ->whereDoesntHave('jawabanUsers', fn($q) => $q->where('percobaan_id', $percobaan->id))
            ->first();

        return view('dashboard.pages.pre-test.pretest-start', compact('tes', 'pertanyaan', 'percobaan'));
    }

    public function pretestSubmit(Request $request, Percobaan $percobaan) {
        foreach ($request->jawaban as $pertanyaanId => $opsiId) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id' => $percobaan->id,
                    'pertanyaan_id' => $pertanyaanId,
                ],
                ['opsi_jawabans_id' => $opsiId]
            );
        }

        return redirect()->route('dashboard.pretestShow', $percobaan->tes_id);
    }

    public function pretestResult(Percobaan $percobaan) {
        return view('dashboard.pages.pre-test.pretest-result', compact('percobaan'));
    }

    // ======================
    // POST-TEST
    // ======================
    public function posttest() {
        $tes = Tes::all();
        return view('dashboard.pages.post-test.posttest', compact('tes'));
    }

    public function posttestShow(Tes $tes) {
        $userId = Auth::id();

        $percobaan = Percobaan::firstOrCreate(
            ['peserta_id' => $userId, 'tes_id' => $tes->id],
            ['waktu_mulai' => now()]
        );

        $pertanyaan = $tes->pertanyaans()
            ->whereDoesntHave('jawabanUsers', fn($q) => $q->where('percobaan_id', $percobaan->id))
            ->first();

        return view('dashboard.pages.post-test.posttest-start', compact('tes', 'pertanyaan', 'percobaan'));
    }

    public function posttestSubmit(Request $request, Percobaan $percobaan) {
        foreach ($request->jawaban as $pertanyaanId => $opsiId) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id' => $percobaan->id,
                    'pertanyaan_id' => $pertanyaanId,
                ],
                ['opsi_jawabans_id' => $opsiId]
            );
        }

        return redirect()->route('dashboard.posttestShow', $percobaan->tes_id);
    }

    public function posttestResult(Percobaan $percobaan) {
        return view('dashboard.pages.post-test.posttest-result', compact('percobaan'));
    }

    // ======================
    // FEEDBACK
    // ======================
    public function feedback() {
        return view('dashboard.pages.feedback');
    }

    public function feedbackSubmit(Request $request) {
        return redirect()->route('dashboard.feedback')->with('success', 'Feedback berhasil dikirim!');
    }
}
