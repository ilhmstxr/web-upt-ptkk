<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BiodataDiri;
use App\Models\BiodataSekolah;
use App\Models\BiodataDokumen;
use App\Models\Registration;

class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('registration.registration-form-new');
    }

    public function submit(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'religion' => 'nullable|string',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|min:10|max:15',
            'email' => 'required|email|max:255',

            // sekolah
            'school_name' => 'nullable|string|max:255',
            'school_address' => 'nullable|string',
            'competence' => 'nullable|string|max:255',
            'class' => 'nullable|string|max:50',
            'dinas_branch' => 'nullable|string|max:255',

            // dokumen
            'ktp_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_tugas_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_sehat_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'pas_foto_path' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
            'surat_tugas_nomor' => 'nullable|string|max:255',
        ]);

        // 2. Cek atau buat user baru
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'] ?? $validated['email'],
                    'password' => bcrypt(Str::random(12)),
                ]
            );
        }

        // 3. Simpan ke tabel biodata_diri
        BiodataDiri::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik' => $validated['nik'],
                'birth_place' => $validated['birth_place'],
                'birth_date' => $validated['birth_date'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'user_id' => $user->id
            ]
        );

        // 4. Simpan ke tabel biodata_sekolah
        BiodataSekolah::updateOrCreate(
            ['user_id' => $user->id],
            [
                'school_name' => $validated['school_name'] ?? null,
                'school_address' => $validated['school_address'] ?? null,
                'competence' => $validated['competence'] ?? null,
                'class' => $validated['class'] ?? null,
                'dinas_branch' => $validated['dinas_branch'] ?? null,
                'user_id' => $user->id
            ]
        );

        // 5. Simpan file dokumen
        $dokData = [
            'surat_tugas_nomor' => $validated['surat_tugas_nomor'] ?? null,
            'user_id' => $user->id
        ];

        foreach (['ktp_path','ijazah_path','surat_tugas_path','surat_sehat_path','pas_foto_path'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $dokData[$fileField] = $request->file($fileField)->store('uploads/dokumen', 'public');
            }
        }

        BiodataDokumen::updateOrCreate(
            ['user_id' => $user->id],
            $dokData
        );

        // 6. Simpan ke tabel registrations (untuk Filament)
        Registration::create([
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'religion' => $validated['religion'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'school_name' => $validated['school_name'] ?? null,
            'school_address' => $validated['school_address'] ?? null,
            'competence' => $validated['competence'] ?? null,
            'class' => $validated['class'] ?? null,
            'dinas_branch' => $validated['dinas_branch'] ?? null,
            'surat_tugas_nomor' => $validated['surat_tugas_nomor'] ?? null,
            'ktp_path' => $dokData['ktp_path'] ?? null,
            'ijazah_path' => $dokData['ijazah_path'] ?? null,
            'surat_tugas_path' => $dokData['surat_tugas_path'] ?? null,
            'surat_sehat_path' => $dokData['surat_sehat_path'] ?? null,
            'pas_foto_path' => $dokData['pas_foto_path'] ?? null,
        ]);

        // 7. Redirect ke halaman sukses
        return redirect()->route('registration.success')->with('status', 'success');
    }

    public function success()
    {
        return view('registration.success');
    }

    public function submitBiodata(Request $request)
    {
        return $this->submit($request);
    }
}
