<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MessageController;

require __DIR__ . '/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('events', EventController::class);
    Route::get('messages', [MessageController::class, 'index']);
});


// have to be put at last line
Route::get('{slug}', [EventController::class, 'showEventPage']);
Route::post('{slug}', [MessageController::class, 'submitMessage']);
