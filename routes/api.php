<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;

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

    //Student
    Route::post('/student/create', [StudentController::class, 'store']);
    Route::get('/students/{page?}', [StudentController::class, 'index'])
        ->where('page', '^[0-9]+$')->defaults('page', 1);
    Route::get('/student/{id}', [StudentController::class, 'show'])
        ->where('id', '^[0-9]+$');
    Route::put('/student/{id}', [StudentController::class, 'update'])
        ->where('id', '^[0-9]+$');
    Route::delete('/student/{id}', [StudentController::class, 'destroy'])
        ->where('id', '^[0-9]+$');

    //Book
    Route::post('/book/create', [BookController::class, 'store']);
    Route::get('/books/{page?}', [BookController::class, 'index'])
        ->where('page', '^[0-9]+$')->defaults('page', 1);
    Route::get('/book/{id}', [BookController::class, 'show'])
        ->where('id', '^[0-9]+$');
    Route::put('/book/{id}', [BookController::class, 'update'])
        ->where('id', '^[0-9]+$');
    Route::delete('/book/{id}', [BookController::class, 'destroy'])
        ->where('id', '^[0-9]+$');

    //Category
    Route::post('/category/create', [CategoryController::class, 'store']);
    Route::get('/categories/{page?}', [CategoryController::class, 'index'])
        ->where('page', '^[0-9]+$')->defaults('page', 1);
    Route::get('/category/{id}', [CategoryController::class, 'show'])
        ->where('id', '^[0-9]+$');
    Route::put('/category/{id}', [CategoryController::class, 'update'])
        ->where('id', '^[0-9]+$');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])
        ->where('id', '^[0-9]+$');
});
