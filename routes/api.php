<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppController;

Route::post('/login', [AppController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [AppController::class, 'dashboard']);
    Route::get('/my-desk', [AppController::class, 'myDesk']);
    Route::get('/file/{uuid}', [AppController::class, 'fileDetails']);
    Route::post('/file/{file_id}/receive', [AppController::class, 'receiveFile']);
    Route::post('/file/{file_id}/forward', [AppController::class, 'forwardFile']);
    Route::post('/logout', [AppController::class, 'logout']);
});

Route::get('/app-version', function () {
    return response()->json([
        'min_version' => '1.1.0', // প্লে স্টোরে থাকা বর্তমান রিলিজ ভার্শন
        'force_update' => true,
        'update_url' => 'https://play.google.com/store/apps/details?id=com.orbachinujbuk.nothi_tracker'
    ]);
});