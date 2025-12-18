<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranPelatihan;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AssessmentLoginController extends Controller
{
    public function show()
    {
        return view('pages.masuk');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'token'    => ['required', 'string'],
            'password' => ['required', 'string'], // ddmmyyyy
        ]);

        $token = trim($request->token);
        $pass  = preg_replace('/\D/', '', trim($request->password)); // buang selain angka

        // 1) ambil pendaftaran by nomor_registrasi / assessment_token
        $reg = PendaftaranPelatihan::with('peserta.instansi')
            ->where(function ($q) use ($token) {
                $q->where('nomor_registrasi', $token)
                    ->orWhere('assessment_token', $token);
            })
            ->latest('id')
            ->first();

        if (!$reg) {
            Log::warning('LOGIN FAIL: token tidak ketemu', ['token' => $token]);
            return back()->with('error', 'Nomor registrasi tidak ditemukan.');
        }

        if (!$reg->peserta) {
            Log::warning('LOGIN FAIL: peserta relasi null', ['reg_id' => $reg->id]);
            return back()->with('error', 'Data peserta tidak ditemukan (relasi kosong).');
        }

        $peserta = $reg->peserta;

        // 2) cek tanggal lahir peserta
        if (!$peserta->tanggal_lahir) {
            Log::warning('LOGIN FAIL: tanggal lahir kosong', ['peserta_id' => $peserta->id]);
            return back()->with('error', 'Tanggal lahir peserta belum diisi admin.');
        }

        // expected password
        $expected = Carbon::parse($peserta->tanggal_lahir)->format('dmY');

        if ($pass !== $expected) {
            Log::warning('LOGIN FAIL: password mismatch', [
                'peserta_id' => $peserta->id,
                'input'      => $pass,
                'expected'   => $expected,
            ]);
            return back()->with('error', 'Password salah. Gunakan format ddmmyyyy.');
        }

        /**
         * 3) bersihkan session lama (VERSI AMAN)
         * - jangan flush() karena bisa bikin session request berikutnya kosong lagi
         */
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

        /**
         * 4) set session baru (dashboard pakai peserta_id)
         * - pakai put() supaya jelas nempel di session yang baru diregenerate
         */
        $request->session()->put([
            'peserta_id'               => $peserta->id,
            'pendaftaran_pelatihan_id' => $reg->id,
            'pelatihan_id'             => $reg->pelatihan_id,
            'kompetensi_id'            => $reg->kompetensi_id,

            'instansi_id'   => optional($peserta->instansi)->id,
            'instansi_nama' => optional($peserta->instansi)->asal_instansi,
            'instansi_kota' => optional($peserta->instansi)->kota,
        ]);

        Log::info('LOGIN OK', [
            'peserta_id' => $peserta->id,
            'reg_id'     => $reg->id
        ]);

        return redirect()->route('dashboard.home')
            ->with('success', 'Login berhasil. Selamat mengerjakan!');
    }

    public function logout(Request $request)
    {
        // Bersihkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 🔑 Kembali ke login assessment
        return redirect()->route('landing');
    }
}
