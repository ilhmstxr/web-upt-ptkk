<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BiodataDiri;
use App\Models\BiodataSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegistrationFlowController extends Controller
{
    /**
     * Step 0: Registrasi user minimal (name, email/phone).
     * Simpan user_id ke session: pending_user_id
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['nullable','email','max:255', Rule::unique('users','email')],
            'phone' => ['nullable','string','max:20'],
            // kalau pakai password:
            // 'password' => ['nullable','string','min:8','confirmed'],
        ]);

        if (empty($data['email']) && empty($data['phone'])) {
            return response()->json(['message' => 'Email atau phone wajib diisi minimal salah satu.'], 422);
        }

        // Buat user. Password boleh null jika pakai OTP.
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'password' => null, // atau Hash::make($data['password'])
        ]);

        // Simpan ke session untuk langkah berikutnya
        $request->session()->put('pending_user_id', $user->id);

        return response()->json([
            'message' => 'User created. Lanjut ke biodata sekolah.',
            'pending_user_id' => $user->id,   // kalau perlu dipakai di FE
            'next' => route('flow.school'),
        ]);
    }

    /**
     * Step 1: Simpan Biodata Sekolah
     */
    public function saveSchool(Request $request)
    {
        $userId = $request->session()->get('pending_user_id');
        if (! $userId) {
            return response()->json(['message' => 'Session pendaftaran tidak ditemukan. Mulai ulang.'], 440);
        }

        $data = $request->validate([
            'school_name'    => ['required','string','max:255'],
            'school_address' => ['nullable','string','max:255'],
            'competence'     => ['nullable','string','max:255'],
            'class'          => ['nullable','string','max:50'],
            'dinas_branch'   => ['nullable','string','max:100'],
        ]);

        BiodataSekolah::updateOrCreate(
            ['user_id' => $userId],
            $data + ['user_id' => $userId]
        );

        return response()->json([
            'message' => 'Biodata sekolah tersimpan. Lanjut ke biodata diri.',
            'next' => route('flow.personal'),
        ]);
    }

    /**
     * Step 2: Simpan Biodata Diri
     */
    public function savePersonal(Request $request)
    {
        $userId = $request->session()->get('pending_user_id');
        if (! $userId) {
            return response()->json(['message' => 'Session pendaftaran tidak ditemukan. Mulai ulang.'], 440);
        }

        $data = $request->validate([
            'nik'          => ['required','digits:16', Rule::unique('biodata_diri','nik')->ignore($userId,'user_id')],
            'birth_place'  => ['required','string','max:255'],
            'birth_date'   => ['required','date'],
            'gender'       => ['required', Rule::in(['Laki-laki','Perempuan'])],
            'religion'     => ['nullable','string','max:50'],
            'address'      => ['nullable','string','max:500'],
        ]);

        BiodataDiri::updateOrCreate(
            ['user_id' => $userId],
            $data + ['user_id' => $userId]
        );

        return response()->json([
            'message' => 'Biodata diri tersimpan. Selesai.',
            'next' => route('flow.finish'),
        ]);
    }

    /**
     * Step 3 (opsional): Akhiri flow dan bersihkan session
     */
    public function finish(Request $request)
    {
        $userId = $request->session()->pull('pending_user_id'); // hapus dari session
        return response()->json([
            'message' => 'Pendaftaran selesai',
            'user_id' => $userId,
            // di sini kamu bisa trigger kirim email/WA, generate token pendaftaran, dll.
        ]);
    }
}
