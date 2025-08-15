<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LampiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('peserta.pendaftaran.lampiran');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Lampiran $lampiran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lampiran $lampiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lampiran $lampiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lampiran $lampiran)
    {
        //
    }
}
