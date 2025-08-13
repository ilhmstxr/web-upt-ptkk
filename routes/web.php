<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistrationFlowController;
use App\Http\Controllers\PendaftaranController;

// ============================
// API untuk Flow Pendaftaran (Step-by-Step)
// ============================
Route::prefix('api/flow')->middleware('api')->group(function () {
    Route::post('/register', [RegistrationFlowController::class, 'register'])->name('flow.register');
    Route::post('/biodata-sekolah', [RegistrationFlowController::class, 'saveSchool'])->name('flow.school');
    Route::post('/biodata-diri', [RegistrationFlowController::class, 'savePersonal'])->name('flow.personal');
    Route::post('/finish', [RegistrationFlowController::class, 'finish'])->name('flow.finish');
});

// ============================
// Halaman Utama (Landing Page)
// ============================
Route::get('/', function () {
    return view('landing'); // resources/views/landing.blade.php
})->name('landing');

// ============================
// Pendaftaran (Form Pendaftaran Baru)
// ============================
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
Route::post('/pendaftaran', [PendaftaranController::class, 'submit'])->name('pendaftaran.submit');

// Kalau mau akses langsung registration-form-new.blade.php
Route::get('/pendaftaran-baru', function () {
    return view('registration-form-new'); // resources/views/registration-form-new.blade.php
})->name('pendaftaran.baru');

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

    abort_unless(in_array($kompetensi, $kompetensiList), 404);

    return view('detail-pelatihan', compact('kompetensi'));
})->name('detail-pelatihan');
