<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Public routes

Route::post('/login', [UserController::class, 'login']);

// Protected routes

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user/create', [UserController::class, 'store']);
    Route::post('/logout', [UserController::class, 'logout']);
});

