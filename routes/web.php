<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TinkerCommandController;

Route::get('/dashboard', [TinkerCommandController::class, 'index']);
Route::post('/run', [TinkerCommandController::class, 'store']);

Route::get('/favorite/{id}', [TinkerCommandController::class, 'favorite']);
Route::get('/delete/{id}', [TinkerCommandController::class, 'delete']);
Route::get('/restore/{id}', [TinkerCommandController::class, 'restore']);