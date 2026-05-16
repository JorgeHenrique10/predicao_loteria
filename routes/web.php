<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LotofacilController;
use Illuminate\Support\Facades\Route;

// Menu principal
Route::get('/', fn () => \Inertia\Inertia::render('Home', [
    'megaSenaTotal' => \App\Models\Sorteio::count(),
    'lotofacilTotal' => \App\Models\SorteioLotofacil::count(),
]))->name('home');

// Mega-Sena
Route::prefix('megasena')->name('megasena.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::post('/sync', [DashboardController::class, 'sync'])->name('sync');
    Route::post('/predict', [DashboardController::class, 'predict'])->name('predict');
});

// Lotofácil
Route::prefix('lotofacil')->name('lotofacil.')->group(function () {
    Route::get('/', [LotofacilController::class, 'index'])->name('index');
    Route::post('/sync', [LotofacilController::class, 'sync'])->name('sync');
    Route::post('/predict', [LotofacilController::class, 'predict'])->name('predict');
});
