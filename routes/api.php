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
        'min_version'  => Cache::get('app_min_version', '1.0.0'),
        'force_update' => (bool) Cache::get('app_force_update', true),
        'update_url'   => Cache::get('app_update_url', 'https://play.google.com/store/apps/details?id=com.orbachinujbuk.nothi_tracker')
    ]);
});