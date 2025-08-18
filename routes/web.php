<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistrationFlowController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\OtpController;
use App\Models\Pelatihan;


Route::get('/', function () {
    $pelatihans = Pelatihan::all(); // Query model
    return view('landing', compact('pelatihans')); // Kirim data ke view
})->name('landing-page'); // Beri nama route
Route::get('/otp/send', [OtpController::class, 'showSendForm'])->name('otp.send.form'); // Form input email/WA
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');           // Kirim OTP
Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form'); // Form verifikasi OTP
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');     // Submit OTP
Route::get('/otp', [RegistrationController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [RegistrationController::class, 'verifyOtp'])->name('otp.verify');


// ============================
// Form Biodata & Halaman Sukses
// ============================
Route::post('/submit-biodata', [RegistrationController::class, 'submit'])
    ->name('submit-biodata');

Route::get('/pendaftaran/success', [RegistrationController::class, 'success'])
    ->name('registration.success');

Route::post('/registration/submit', [RegistrationController::class, 'submit'])->name('registration.submit');
Route::get('/registration/success', function () {
    return view('registration.success');
})->name('registration.success');
// ============================
// Halaman Utama (Landing Page)
// ============================
Route::get('/', function () {
    $pelatihans = Pelatihan::orderBy('tanggal_mulai', 'desc')->take(10)->get();
    return view('landing', compact('pelatihans'));
})->name('landing');

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
