<?php

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\RegistrationFlowController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatistikPelatihanController;

Route::get('/statistik-pelatihan', [StatistikPelatihanController::class, 'index']);


// Route::post('/flow/register', [RegistrationFlowController::class, 'register']);
Route::get('/peserta/search', [PesertaController::class, 'search'])->name('peserta.search');

