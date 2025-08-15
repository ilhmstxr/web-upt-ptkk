<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PendaftaranController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahap = session('tahap_pendaftaran', 1);

        if ($tahap == 1) {
            return view('peserta.pendaftaran.bio-peserta');
        } elseif ($tahap == 2) {
            return view('peserta.pendaftaran.bio-sekolah');
        } else {
            return 'halaman tidak ditemukan';
        }
        // return view('peserta.pendaftaran.bio-peserta');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)


    public function store(Request $request)
    {

        // iki marikno
        $tahap = session('tahap_pendaftaran', 1);

        if ($tahap == 1) {
            $tahap = 2;
            session(['tahap_pendaftaran' => 2]);

            return redirect()->route('pendaftaran.index')->with('success', 'Data berhasil disimpan. Silakan lanjut ke tahap berikutnya.');
        } elseif ($tahap == 2) {
            return redirect()->route('lampiran.index')->with('success', 'Data berhasil disimpan. Silakan lanjut ke tahap berikutnya.');
        } else {
            return 'halaman tidak ditemukan';
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
