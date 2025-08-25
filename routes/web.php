<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Volt\Volt;

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;

use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;

use App\Mail\TestMail;

/*
|--------------------------------------------------------------------------
| Download Biodata Peserta
|--------------------------------------------------------------------------
*/
Route::get('/peserta/{peserta}/download', [PesertaController::class, 'download'])
    ->name('peserta.download');
Route::get('/peserta/{id}/download-biodata', [PesertaController::class, 'downloadBiodata'])
    ->name('peserta.downloadBiodata');

/*
|--------------------------------------------------------------------------
| Export Excel & Test Routes
|--------------------------------------------------------------------------
*/
Route::get('/test-peserta', fn() => dd((new PesertaSheet())->collection()->take(5)));
Route::get('/test-lampiran', fn() => dd((new LampiranSheet())->collection()->take(5)));
Route::get('/export-peserta', fn() => Excel::download(new PesertaExport, 'peserta.xlsx'));

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('landing'))->name('landing');

/*
|--------------------------------------------------------------------------
| Pendaftaran Publik
|--------------------------------------------------------------------------
*/
Route::resource('pendaftaran', PendaftaranController::class);

/*
|--------------------------------------------------------------------------
| Detail Pelatihan
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
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
});

/*
|--------------------------------------------------------------------------
| Settings (Volt)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

/*
|--------------------------------------------------------------------------
| Surat Generate
|--------------------------------------------------------------------------
*/
Route::get('/surat/{id}/generate', [SuratController::class, 'generate'])->name('surat.generate');

/*
|--------------------------------------------------------------------------
| Kirim Email Test
|--------------------------------------------------------------------------
*/
Route::get('/send', fn() => Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail()));

/*
|--------------------------------------------------------------------------
| Rute Testing Pendaftaran (sementara)
|--------------------------------------------------------------------------
*/
Route::get('1', fn() => view('peserta.pendaftaran.bio-peserta'));
Route::get('2', fn() => view('peserta.pendaftaran.bio-sekolah'));
Route::get('3', fn() => view('peserta.pendaftaran.lampiran'));
Route::get('4', fn() => view('peserta.pendaftaran.selesai'));
Route::get('pendaftaran_selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');

/*
|--------------------------------------------------------------------------
| Auth (Login, Register, dll)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
