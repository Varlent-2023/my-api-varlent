<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BakeryController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function(){
    Route::get('/users', function(){
        return $request->user();
    });
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::resource('bakery', BakeryController::class, [
    'only'=>[
        'index',
        'show'
    ]
]);

Route::resource('bakery', BakeryController::class, [
    'except'=>[
        'index',
        'show'
    ]
])->middleware(['auth:api']);