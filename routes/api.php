<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


// Public routes

Route::group([''], function () {
    Route::post('/login', [UserController::class, 'login']);
});


// Protected routes

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Super
    Route::post('/user/create', [UserController::class, 'store']);
    Route::delete('/user/{id}', [UserController::class, 'destroy'])
        ->where('id', '^[0-9]+$');
    Route::put('/user/{id}', [UserController::class, 'update'])
        ->where('id', '^[0-9]+$');
    Route::get('/users/{page?}', [UserController::class, 'index'])
        ->where('page', '^[0-9]+$')->defaults('page', 1);
    Route::get('/user/{id}', [UserController::class, 'show'])
        ->where('id', '^[0-9]+$');
    Route::put('/user/{id}', [UserController::class, 'update'])
        ->where('id', '^[0-9]+$');

    //Default
    Route::post('/logout', [UserController::class, 'logout']);

    Route::post('/student/create', [StudentController::class, 'store']);
});
