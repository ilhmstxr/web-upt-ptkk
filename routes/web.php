<?php

use App\Http\Controllers\LampiranController;
use App\Http\Controllers\PendaftaranController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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

// Rute untuk halaman utama dan pendaftaran
Route::get('/', function () {
    return redirect()->route('pendaftaran.create');
});

// Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
// Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');


// Rute bawaan Laravel untuk dashboard dan settings
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});


Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});

Route::resource('pendaftaran', PendaftaranController::class);

route::get('1',function () {
   return view('peserta.pendaftaran.bio-peserta'); 
});
route::get('2',function () {
   return view('peserta.pendaftaran.bio-sekolah'); 
});
route::get('3',function () {
   return view('peserta.pendaftaran.lampiran'); 
});
// Rute untuk autentikasi (login, register, dll.)
require __DIR__.'/auth.php';
