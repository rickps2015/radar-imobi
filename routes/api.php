<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/properties', [PropertyController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/sale-modes', [PropertyController::class, 'getUniqueSaleModes']);
Route::post('/scrape', [ScrapingController::class, 'postLeilaoData']);

Route::post('/send-reset-code', [NewPasswordController::class, 'sendResetCode']);
Route::post('/verify-reset-code', [NewPasswordController::class, 'verifyResetCode']);
Route::post('/check-reset-code', [NewPasswordController::class, 'checkResetCode']);

Route::prefix('user-filters')->group(function() {
    Route::post('/', [FilterController::class, 'store']); // Rota para criar um novo filtro
    Route::get('/{userId}', [FilterController::class, 'index']); // Rota para listar filtros de um usuário
    Route::delete('/{id}', [FilterController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

