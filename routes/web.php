<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;

// ============================
// Halaman Utama (Landing Page)
// ============================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ============================
// Pendaftaran
// ============================
// Form pendaftaran
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');

// Proses submit pendaftaran
Route::post('/pendaftaran', [PendaftaranController::class, 'submit'])->name('pendaftaran.submit');

// ============================
// Halaman Detail Pelatihan
// ============================
Route::get('/pelatihan/{kompetensi}', function ($kompetensi) {
    $kompetensiList = [
        'tata-boga',
        'tata-busana',
        'tata-kecantikan',
        'teknik-pendingin-dan-tata-udara',
    ];

    if (!in_array($kompetensi, $kompetensiList)) {
        abort(404);
    }

    return view('detail-pelatihan', compact('kompetensi'));
})->name('detail-pelatihan');
