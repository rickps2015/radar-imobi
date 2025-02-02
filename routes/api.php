<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/properties', [PropertyController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

