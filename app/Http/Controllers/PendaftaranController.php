<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    // Tampilkan form pendaftaran
    public function index()
    {
        return view('registration-form-new');
    }

    // Proses submit form pendaftaran
    public function submit(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            // tambah validasi sesuai kebutuhan
        ]);

        // Simpan ke database jika perlu
        // Pendaftaran::create($validated);

        return redirect()->route('pendaftaran')->with('success', 'Pendaftaran berhasil!');
    }
}
