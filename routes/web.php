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
use App\Http\Controllers\PostTestController;

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

// Redirect root ke form pendaftaran
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
Route::get('/pendaftaran-baru', function () {
    return view('registration-form-new');
})->name('pendaftaran.baru');

// Route view testing step pendaftaran (opsional)
Route::get('1', fn() => view('peserta.pendaftaran.bio-peserta'));
Route::get('2', fn() => view('peserta.pendaftaran.bio-sekolah'));
Route::get('3', fn() => view('peserta.pendaftaran.lampiran'));
Route::get('4', fn() => view('peserta.pendaftaran.selesai'));
Route::get('5', fn() => view('template_surat.instruktur'));
Route::get('6', fn() => view('peserta.monev.pendaftaran'));

// ============================
// Dashboard & PostTest
// ============================
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::get('/pretest', [DashboardController::class, 'pretest'])->name('pretest');
    Route::get('/pretest/start', [DashboardController::class, 'pretestStart'])->name('pretest.start');
    Route::get('/posttest', [DashboardController::class, 'posttest'])->name('posttest');
    Route::get('/posttest/start', [DashboardController::class, 'posttestStart'])->name('posttest.start');
    Route::get('/feedback', [DashboardController::class, 'feedback'])->name('feedback');
    Route::post('/feedback/submit', [DashboardController::class, 'feedbackSubmit'])->name('feedback.submit');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    // PostTest tambahan
    Route::get('/posttest/start', [PostTestController::class, 'start'])->name('posttest.start');
    Route::post('/posttest/submit', [PostTestController::class, 'submit'])->name('posttest.submit');
    Route::get('/posttest/result', [PostTestController::class, 'result'])->name('posttest.result');
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
// Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.show');
// Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');
Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey_checkCredentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');

Route::resource('/survey', SurveyController::class);
Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.show');

// Rute untuk menyimpan jawaban dari setiap langkah survei
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');



// ============================
// Test Excel Export
// ============================
Route::get('/test-peserta', fn() => dd((new PesertaSheet())->collection()->take(5)));
Route::get('/test-lampiran', fn() => dd((new LampiranSheet())->collection()->take(5)));
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



// route fix
// route pendaftaran
Route::resource('/pendaftaran', PendaftaranController::class);
Route::get('/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');

Route::get('pendaftaran_selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])
    ->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])
    ->name('peserta.download-bulk');


route::get('/cek_icon',function (){
    return view('cek_icon');
});

route::get('/100',function(){
    return view('peserta.monev.pendaftaran');
});
// Rute untuk autentikasi (login, register, dll.)
require __DIR__ . '/auth.php';
