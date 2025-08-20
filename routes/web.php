<?php

use App\Http\Controllers\PendaftaranController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Exports\PesertaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;

Route::get('/test-peserta', function () {
    // ambil 5 data pertama dari PesertaSheet
    dd((new PesertaSheet())->collection()->take(5));
});

Route::get('/test-lampiran', function () {
    // ambil 5 data pertama dari LampiranSheet
    dd((new LampiranSheet())->collection()->take(5));
});

Route::get('/export-peserta', function () {
    return Excel::download(new PesertaExport, 'peserta.xlsx');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama (landing page)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Rute untuk pendaftaran (menggunakan resource controller)
Route::resource('pendaftaran', PendaftaranController::class);

// Halaman detail pelatihan
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

// Rute bawaan Laravel untuk dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rute untuk pengaturan (menggunakan Volt)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Rute untuk mengirim email (gunakan salah satu, jangan duplikasi)
Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});

// Rute-rute ini tampaknya untuk pengujian, disarankan untuk dihapus setelah selesai
Route::get('1', function () {
    return view('peserta.pendaftaran.bio-peserta');
});
Route::get('2', function () {
    return view('peserta.pendaftaran.bio-sekolah');
});
Route::get('3', function () {
    return view('peserta.pendaftaran.lampiran');
});
Route::get('4', function () {
    return view('peserta.pendaftaran.selesai');
});

// Rute untuk autentikasi (login, register, dll.)
require __DIR__ . '/auth.php';