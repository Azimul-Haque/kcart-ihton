<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers Import
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HearingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================
// Public Routes (সবার জন্য উন্মুক্ত)
// =========================================================
Route::get('/', [IndexController::class, 'index'])->name('index.index');
Route::get('/terms-and-conditions', [IndexController::class, 'termsAndConditions'])->name('index.terms-and-conditions');
Route::get('/privacy-policy', [IndexController::class, 'privacyPolicy'])->name('index.privacy-policy');
Route::get('/refund-policy', [IndexController::class, 'refundPolicy'])->name('index.refund-policy');
Route::get('/faq', [IndexController::class, 'getFaq'])->name('index.faq');
Route::get('/contact', [IndexController::class, 'getContact'])->name('index.contact');

Route::get('/case-list', [IndexController::class, 'publicCaseList'])->name('public.case_list');

// Utility Routes
Route::get('/sessionsclear', [IndexController::class, 'deleteSessionFiles'])->name('sessionsclear');
Route::get('/clear', [IndexController::class, 'clear'])->name('clear');

// Authentication Routes (Login, Logout, Password Reset)
Auth::routes();


// =========================================================
// Protected Dashboard Routes (শুধুমাত্র লগইন করা ইউজারের জন্য)
// =========================================================
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard Landing & Cache
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/clear/query/cache', [DashboardController::class, 'clearQueryCache'])->name('dashboard.clearquerycache');
    Route::post('/dashboard/app-version-update', [DashboardController::class, 'updateAppVersion'])->name('dashboard.app-version.update');


    // 2. Files & Tracking Routes (কাস্টম রাউটগুলো Resource Route এর আগে থাকবে)
    Route::get('/dashboard/files/my-desk', [FileController::class, 'myDesk'])->name('files.my_desk');
    Route::get('/dashboard/files/transit', [FileController::class, 'transit'])->name('files.transit');
    Route::get('/dashboard/files/{file}/receipt', [FileController::class, 'receipt'])->name('files.receipt');
    Route::post('/dashboard/files/{file}/transfer', [FileController::class, 'transfer'])->name('files.transfer');
    Route::get('/dashboard/files/{file}/print-slip', [FileController::class, 'printSlip'])->name('files.print_slip');
    Route::post('/dashboard/files/{file}/dispose', [FileController::class, 'dispose'])->name('files.dispose');
    Route::get('/dashboard/files/archived', [FileController::class, 'archivedList'])->name('files.archived');
    
    // Resource Route for Files
    Route::resource('dashboard/files', FileController::class)->except(['destroy']);


    // 3. Hearings (শুনানি) Routes
    Route::get('/dashboard/hearings/today', [HearingController::class, 'todayHearings'])->name('hearings.today');
    Route::delete('/hearings/{hearing}', [HearingController::class, 'destroy'])->name('hearings.destroy');
    Route::get('/hearings/pdf', [HearingController::class, 'downloadPdf'])->name('hearings.pdf');

    Route::post('/dashboard/files/{file}/hearings', [HearingController::class, 'store'])->name('hearings.store');


    // 4. Staff & User Management Routes
    Route::get('/dashboard/users', [DashboardController::class, 'getUsers'])->name('dashboard.users');
    Route::get('/dashboard/users/{user}', [DashboardController::class, 'getUser'])->name('dashboard.users.single');
    Route::post('/dashboard/users/store', [DashboardController::class, 'storeUser'])->name('dashboard.users.store');
    Route::post('/dashboard/users/{id}/update', [DashboardController::class, 'updateUser'])->name('dashboard.users.update');
    Route::get('/dashboard/users/{id}/delete', [DashboardController::class, 'deleteUser'])->name('dashboard.users.delete');
    Route::post('/dashboard/users/{id}/approve', [DashboardController::class, 'toggleApprove'])->name('dashboard.users.approve');


    // 5. Messages & Notifications
    Route::get('/dashboard/messages', [DashboardController::class, 'getMessages'])->name('dashboard.messages');
    Route::post('/dashboard/messages/{id}/update', [DashboardController::class, 'updateMessage'])->name('dashboard.messages.update');
    Route::get('/dashboard/messages/delete/{id}', [DashboardController::class, 'deleteMessage'])->name('dashboard.messages.delete');

    Route::get('/dashboard/notifications', [DashboardController::class, 'getNotifications'])->name('dashboard.notifications');
    Route::post('/dashboard/notifications/send', [DashboardController::class, 'sendNotification'])->name('dashboard.notifications.send');
    Route::get('/dashboard/notifications/delete/{id}', [DashboardController::class, 'deleteNotification'])->name('dashboard.notifications.delete');

});