<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tes;
use App\Models\Percobaan; // model percobaan jawaban peserta
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
        $tes = Tes::all(); // ambil semua pre-test
        return view('dashboard.pages.pre-test.pretest', compact('tes'));
    }

    public function pretestShow(Tes $tes)
    {
        // periksa apakah peserta sudah pernah mencoba tes ini
        $percobaan = Percobaan::where('peserta_id', Auth::id())
                               ->where('tes_id', $tes->id)
                               ->first();

        return view('dashboard.pages.pre-test.pretest-start', compact('tes', 'percobaan'));
    }

    public function pretestSubmit(Request $request, Tes $tes)
    {
        $percobaan = Percobaan::create([
            'peserta_id' => Auth::id(),
            'tes_id' => $tes->id,
            'jawaban' => json_encode($request->jawaban),
            'waktu_mulai' => now(),
        ]);

        return redirect()->route('dashboard.pretest.result', $percobaan->id);
    }

    public function pretestResult(Percobaan $percobaan)
    {
        return view('dashboard.pages.pre-test.pretest-result', compact('percobaan'));
    }

    // ======================
    // POST-TEST
    // ======================
    public function posttest()
    {
        $tes = Tes::all(); // ambil semua post-test
        return view('dashboard.pages.post-test.posttest', compact('tes'));
    }

    public function posttestShow(Tes $tes)
    {
        $percobaan = Percobaan::where('peserta_id', Auth::id())
                               ->where('tes_id', $tes->id)
                               ->first();

        return view('dashboard.pages.post-test.posttest-start', compact('tes', 'percobaan'));
    }

    public function posttestSubmit(Request $request, Tes $tes)
    {
        $percobaan = Percobaan::create([
            'peserta_id' => Auth::id(),
            'tes_id' => $tes->id,
            'jawaban' => json_encode($request->jawaban),
            'waktu_mulai' => now(),
        ]);

        return redirect()->route('dashboard.posttest.result', $percobaan->id);
    }

    public function posttestResult(Percobaan $percobaan)
    {
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
}
