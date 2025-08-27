<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;

class DashboardController extends Controller
{
    // Halaman utama dashboard
    public function home()
    {
        // ambil semua peserta dari database
        $peserta = Peserta::all();

        return view('dashboard.pages.home', compact('peserta'));
    }

    // Profil peserta
    public function profile()
    {
        return view('dashboard.pages.profile');
    }

    // Materi
    public function materi()
    {
        return view('dashboard.pages.materi');
    }

    // Materi per detail (opsional)
    public function materiShow($materi)
    {
        return view('dashboard.pages.materi', compact('materi'));
    }

    // Pre-test
    public function pretest()
    {
        return view('dashboard.pages.pretest');
    }

    public function pretestStart()
    {
        return view('dashboard.pages.pretest');
    }

    // Post-test
    public function posttest()
    {
        return view('dashboard.pages.posttest');
    }

    public function posttestStart()
    {
        return view('dashboard.pages.posttest');
    }

    // Feedback
    public function feedback()
    {
        return view('dashboard.pages.feedback');
    }

    public function feedbackSubmit(Request $request)
    {
        // proses submit feedback
        return redirect()->route('dashboard.feedback')->with('success', 'Feedback berhasil dikirim!');
    }
}
