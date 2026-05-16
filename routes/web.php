<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/sync', [DashboardController::class, 'sync'])->name('sync');
Route::post('/predict', [DashboardController::class, 'predict'])->name('predict');
