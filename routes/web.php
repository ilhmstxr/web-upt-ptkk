<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistrationFlowController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\OtpController;
use App\Models\Pelatihan;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Ambil 10 pelatihan terbaru
    $pelatihans = Pelatihan::orderBy('tanggal_mulai', 'desc')->take(10)->get();
    return view('landing', compact('pelatihans'));
})->name('landing-page');

/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
*/
Route::get('/otp/send', [OtpController::class, 'showSendForm'])->name('otp.send.form');
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::get('/otp', [RegistrationController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify-alt', [RegistrationController::class, 'verifyOtp'])->name('otp.verify.alt');

/*
|--------------------------------------------------------------------------
| Form Biodata & Halaman Sukses
|--------------------------------------------------------------------------
*/
Route::post('/submit-biodata', function () {
    // setelah submit, langsung arahkan ke halaman sukses
    return view('registration.success');
})->name('submit-biodata');

Route::get('/pendaftaran/success', fn () => view('registration.success'))
    ->name('registration.success');

Route::post('/registration/submit', [RegistrationController::class, 'submit'])
    ->name('registration.submit');

Route::get('/registration/success', fn () => view('registration.success'))
    ->name('registration.success.page');

/*
|--------------------------------------------------------------------------
| API Flow Pendaftaran
|--------------------------------------------------------------------------
*/
Route::prefix('api/flow')->middleware('api')->group(function () {
    Route::post('/register', [RegistrationFlowController::class, 'register'])->name('flow.register');
    Route::post('/biodata-sekolah', [RegistrationFlowController::class, 'saveSchool'])->name('flow.school');
    Route::post('/biodata-diri', [RegistrationFlowController::class, 'savePersonal'])->name('flow.personal');
    Route::post('/finish', [RegistrationFlowController::class, 'finish'])->name('flow.finish');
});

/*
|--------------------------------------------------------------------------
| Pendaftaran Routes
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
Route::post('/pendaftaran', [PendaftaranController::class, 'submit'])->name('pendaftaran.submit');
Route::get('/pendaftaran-baru', fn () => view('registration-form-new'))->name('pendaftaran.baru');

/*
|--------------------------------------------------------------------------
| Halaman Detail Pelatihan
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Filament Admin
|--------------------------------------------------------------------------
| Filament otomatis pakai prefix "/admin".
| Jangan buat route manual dengan path "/admin" supaya tidak bentrok.
*/
