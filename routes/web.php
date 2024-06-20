<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BakeryController;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/bakery', function () {
    return view('pages.plp');
})->name('plp');

Route::get('/bakery/{i}', function () {
    return view('pages.pdp');
})->name('pdp');