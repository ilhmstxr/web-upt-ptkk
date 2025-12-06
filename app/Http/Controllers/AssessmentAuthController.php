<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPelatihan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AssessmentAuthController extends Controller
{
    // Menampilkan halaman Login (jika diakses langsung via URL /assessment/login)
    public function showLoginForm()
    {
        // Jika sudah login, lempar ke dashboard
        if (Session::has('peserta_aktif')) {
            return redirect()->route('dashboard.home');
        }
        // Jika belum, lempar ke home dashboard (karena overlay ada di sana)
        return redirect()->route('dashboard.home');
    }

    // Logic Dashboard & Overlay
    public function dashboard()
    {
        // Cek Session Peserta
        $pesertaAktif = Session::get('peserta_aktif');

        // Logic Overlay: Tampilkan jika session kosong
        $data = [
            'pesertaAktif'      => $pesertaAktif,
            'showTokenOverlay'  => is_null($pesertaAktif),
            
            // Variabel Default (Dummy agar view tidak error)
            'preTestDone'       => false,
            'postTestDone'      => false,
            'monevDone'         => false,
            'materiDoneCount'   => 0,
            'totalMateri'       => 15,
            'preTestScore'      => null,
            'postTestScore'     => null,
            'monevScore'        => null,
            'preTestAttempts'   => 0,
            'postTestAttempts'  => 0,
            'monevAttempts'     => 0,
        ];

        // Jika sudah login, Anda bisa load data real dari DB di sini
        if ($pesertaAktif) {
            // Contoh pengambilan data real (sesuaikan dengan model relasi Anda)
            // $realData = $pesertaAktif->progress...;
        }

        return view('dashboard.pages.home', $data);
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'token'    => 'required|string',
            'password' => 'required|string', // Password (Tgl Lahir) wajib diisi
        ]);

        // 2. Cari Pendaftaran berdasarkan Token
        // Kita load relasi ke peserta -> user untuk cek password
        $pendaftaran = PendaftaranPelatihan::with(['peserta.user'])
                        ->where('assessment_token', $request->token)
                        ->first();

        // Cek 1: Token Valid?
        if (!$pendaftaran) {
            return back()->with('error', 'Token ID Peserta tidak ditemukan.');
        }

        // Cek 2: Password Valid? (Format ddmmyyyy)
        $user = $pendaftaran->peserta->user ?? null;

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah. Gunakan Tanggal Lahir (Format: ddmmyyyy).');
        }

        // 3. Login Berhasil -> Simpan ke Session
        Session::put('peserta_aktif', $pendaftaran->peserta);
        
        // Opsional: Simpan data pendaftaran spesifik ini agar tahu dia login pake token pelatihan mana
        Session::put('pendaftaran_aktif_id', $pendaftaran->id);

        return redirect()->route('dashboard.home')->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        Session::forget('peserta_aktif');
        Session::forget('pendaftaran_aktif_id');
        return redirect()->route('dashboard.home');
    }
}