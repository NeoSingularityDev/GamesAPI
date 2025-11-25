<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/games', function () {
    return view('games');
})->name('games');

Route::get('/developers', function () {
    return view('developers');
})->name('developers');