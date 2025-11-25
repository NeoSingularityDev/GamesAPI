<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\DeveloperController;

Route::prefix('v1')->group(function () {
    Route::get('games', [GameController::class, 'readAll']);
    Route::get('games/{id}', [GameController::class, 'readOne']);
    Route::post('games', [GameController::class, 'create']);
    Route::put('games/{id}', [GameController::class, 'update']);
    Route::delete('games/{id}', [GameController::class, 'delete']);

    Route::get('developers', [DeveloperController::class, 'readAll']);
    Route::get('developers/{id}', [DeveloperController::class, 'readOne']);
    Route::post('developers', [DeveloperController::class, 'create']);
    Route::put('developers/{id}', [DeveloperController::class, 'update']);
    Route::delete('developers/{id}', [DeveloperController::class, 'delete']);
});