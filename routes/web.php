<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SurveyController;

use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;
use App\Models\Peserta;
use App\Mail\TestMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke form pendaftaran / landing page
Route::get('/', function () {
    return view('landing');
});

// ============================
// Pendaftaran
// ============================
Route::resource('pendaftaran', PendaftaranController::class);
Route::get('pendaftaran-selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('/pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');

// ============================
// Dashboard & Pre/Post Test
// ============================
Route::prefix('dashboard')->name('dashboard.')->group(function () {

    // Dashboard utama
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    // Pre-test
    Route::get('/pretest', [DashboardController::class, 'pretest'])->name('pretest');
    Route::get('/pretest/start', [DashboardController::class, 'pretestStart'])->name('pretest.start');
    Route::post('/pretest/submit', [DashboardController::class, 'pretestSubmit'])->name('pretest.submit');
    Route::get('/pretest/result', [DashboardController::class, 'pretestResult'])->name('pretest.result');

    // Post-test
    Route::get('/posttest', [DashboardController::class, 'posttest'])->name('posttest');
    Route::get('/posttest/start', [DashboardController::class, 'posttestStart'])->name('posttest.start');
    Route::post('/posttest/submit', [DashboardController::class, 'posttestSubmit'])->name('posttest.submit');
    Route::get('/posttest/result', [DashboardController::class, 'posttestResult'])->name('posttest.result');

    // Feedback
    Route::get('/feedback', [DashboardController::class, 'feedback'])->name('feedback');
    Route::post('/feedback/submit', [DashboardController::class, 'feedbackSubmit'])->name('feedback.submit');
});

// ============================
// Detail Pelatihan
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

// ============================
// Survey / Monev
// ============================
Route::resource('/survey', SurveyController::class);
Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');
Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/survey_checkCredentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');

// ============================
// Test Excel Export
// ============================
Route::get('/test-peserta', function () {
    $pesertaIds = null; // bisa diisi array ID peserta jika perlu
    return dd((new PesertaSheet($pesertaIds))->collection()->take(5));
});
Route::get('/test-lampiran', function () {
    $pesertaIds = null;
    return dd((new LampiranSheet($pesertaIds))->collection()->take(5));
});
Route::get('/export-peserta', fn() => Excel::download(new PesertaExport(), 'peserta.xlsx'));

// ============================
// Settings (Volt)
// ============================
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// ============================
// Mail Testing
// ============================
Route::get('/send', fn() => Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail()));

// ============================
// Data Peserta API
// ============================
Route::get('test-peserta', function () {
    return Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')->get();
});

// ============================
// Autentikasi
// ============================
require __DIR__ . '/auth.php';
