<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FilterController;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/properties', [PropertyController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/sale-modes', [PropertyController::class, 'getUniqueSaleModes']);

Route::prefix('user-filters')->group(function() {
    Route::post('/', [FilterController::class, 'store']); // Rota para criar um novo filtro
    Route::get('/{userId}', [FilterController::class, 'index']); // Rota para listar filtros de um usuário
    Route::delete('/{id}', [FilterController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

