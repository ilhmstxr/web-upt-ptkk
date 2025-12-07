<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPelatihan;
use Carbon\Carbon;

class AssessmentLoginController extends Controller
{
    public function show()
    {
        return view('assessment.login'); // view overlay login assessment
    }

    public function submit(Request $request)
    {
        $request->validate([
            'token'    => ['required', 'string'], // nomor_registrasi / assessment_token
            'password' => ['required', 'string'], // ddmmyyyy
        ]);

        $token = trim($request->token);
        $pass  = trim($request->password);

        // ✅ cari pendaftaran berdasarkan nomor_registrasi ATAU assessment_token
        $reg = PendaftaranPelatihan::with(['peserta.instansi', 'pelatihan', 'kompetensi'])
            ->where(function ($q) use ($token) {
                $q->where('nomor_registrasi', $token)
                  ->orWhere('assessment_token', $token);
            })
            ->first();

        if (!$reg) {
            return back()->with('error', 'Nomor registrasi tidak ditemukan.');
        }

        $peserta = $reg->peserta;
        if (!$peserta) {
            return back()->with('error', 'Data peserta tidak ditemukan.');
        }

        // ✅ cek password = tanggal lahir ddmmyyyy
        $tgl = $peserta->tanggal_lahir;
        if (!$tgl) {
            return back()->with('error', 'Tanggal lahir peserta belum diisi admin.');
        }

        $expected = Carbon::parse($tgl)->format('dmY');

        if ($pass !== $expected) {
            return back()->with('error', 'Password salah. Gunakan format ddmmyyyy.');
        }

        // ✅ reset session biar bersih, lalu bawa konteks pelatihan dari token yang dipakai
        $request->session()->forget([
            'peserta_id','pesertaSurvei_id',
            'instansi_id','instansi_nama','instansi_kota',
            'pendaftaran_pelatihan_id','pelatihan_id','kompetensi_id',
        ]);
        $request->session()->regenerate();

        session([
            'peserta_id'               => $peserta->id,
            'pendaftaran_pelatihan_id' => $reg->id,
            'pelatihan_id'             => $reg->pelatihan_id,
            'kompetensi_id'            => $reg->kompetensi_id,

            'instansi_id'   => optional($peserta->instansi)->id,
            'instansi_nama' => optional($peserta->instansi)->asal_instansi,
            'instansi_kota' => optional($peserta->instansi)->kota,
        ]);

        return redirect()->route('dashboard.home')
            ->with('success', 'Login berhasil. Selamat mengerjakan!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'peserta_id','pesertaSurvei_id',
            'instansi_id','instansi_nama','instansi_kota',
            'pendaftaran_pelatihan_id','pelatihan_id','kompetensi_id',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.home')
            ->with('success', 'Logout berhasil.');
    }
}
