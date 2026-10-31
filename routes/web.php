<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ExamController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index')->name('index.index');
Route::get('/terms-and-conditions', 'IndexController@termsAndConditions')->name('index.terms-and-conditions');
Route::get('/privacy-policy', 'IndexController@privacyPolicy')->name('index.privacy-policy');
Route::get('/refund-policy', 'IndexController@refundPolicy')->name('index.refund-policy');
Route::get('/faq', 'IndexController@getFaq')->name('index.faq');

// PDFs
Route::get('/single/exam/pdf/{softtoken}/{examid}', 'IndexController@getExamSolvePDF')->name('index.single.exam.solve.pdf');

// Clear Route
Route::get('/sessionsclear', ['as'=>'sessionsclear','uses'=>'IndexController@deleteSessionFiles']);
Route::get('/clear', ['as'=>'clear','uses'=>'IndexController@clear']);
Route::get('/sitemap', ['as'=>'clear','uses'=>'IndexController@generateSiteMap']);


Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
Route::get('/dashboard/clear/query/cache', 'DashboardController@clearQueryCache')->name('dashboard.clearquerycache');

Route::get('/dashboard/users', 'DashboardController@getUsers')->name('dashboard.users');
Route::get('/dashboard/users/sort', 'DashboardController@getUsersSort')->name('dashboard.userssort');
Route::get('/dashboard/users/expired', 'DashboardController@getExpiredUsers')->name('dashboard.expiredusers');
Route::post('/dashboard/users/expired/send/sms', 'DashboardController@sendExpiredSMS')->name('dashboard.users.expired.sms');
Route::get('/dashboard/users/{search}', 'DashboardController@getUsersSearch')->name('dashboard.users.search');
Route::get('/dashboard/users/{id}/single', 'DashboardController@getUser')->name('dashboard.users.single');
Route::get('/dashboard/users/{id}/single/otherpage', 'DashboardController@getUserWithOtherPage')->name('dashboard.users.singleother');
Route::post('/dashboard/users/store', 'DashboardController@storeUser')->name('dashboard.users.store');
Route::post('/dashboard/users/{id}/update', 'DashboardController@updateUser')->name('dashboard.users.update');
Route::post('/dashboard/users/bulk/package/update', 'DashboardController@updateBulkPackageDate')->name('dashboard.users.bulk.package.update');
Route::get('/dashboard/users/{id}/delete', 'DashboardController@deleteUser')->name('dashboard.users.delete');
Route::post('/dashboard/users/{id}/single/notification', 'DashboardController@sendSingleNotification')->name('dashboard.users.singlenotification');

Route::post('/dashboard/users/{id}/approve', 'DashboardController@toggleApprove')->name('dashboard.users.approve');
Route::post('/dashboard/users/{id}/single/sms', 'DashboardController@sendSingleSMS')->name('dashboard.users.singlesms');

Route::get('/dashboard/messages', 'DashboardController@getMessages')->name('dashboard.messages');
Route::post('/dashboard/messages/{id}/update', 'DashboardController@updateMessage')->name('dashboard.messages.update');
Route::get('/dashboard/messages/delete/{id}', 'DashboardController@deleteMessage')->name('dashboard.messages.delete');

Route::get('/dashboard/notifications', 'DashboardController@getNotifications')->name('dashboard.notifications');
Route::post('/dashboard/notifications/send', 'DashboardController@sendNotification')->name('dashboard.notifications.send');
Route::get('/dashboard/notifications/delete/{id}', 'DashboardController@deleteNotification')->name('dashboard.notifications.delete');
Route::post('/dashboard/notifications/send/again', 'DashboardController@sendAgainNotification')->name('dashboard.notifications.sendagain');

// ---------------------------------------------------------
// Files & Tracking Routes
// ---------------------------------------------------------

// কাস্টম ফাইল রাউটস (resource এর আগে ডিফাইন করতে হবে)
Route::get('/dashboard/files/my-desk', [FileController::class, 'myDesk'])->name('files.my_desk'); // আমার ডেস্কের ফাইল
Route::get('/dashboard/files/transit', [FileController::class, 'transit'])->name('files.transit'); // ট্রানজিটে থাকা ফাইল (রিসিভ হয়নি)
Route::get('/dashboard/files/{file}/receipt', [FileController::class, 'receipt'])->name('files.receipt'); // 58mm QR প্রিন্ট ভিউ
Route::post('/dashboard/files/{file}/transfer', [FileController::class, 'transfer'])->name('files.transfer'); // ড্যাশবোর্ড থেকে অন্য ডেস্কে পাঠানো

// বেসিক ফাইল ক্রিয়েট ও লিস্ট (Resource)
Route::resource('dashboard/files', FileController::class)->except(['edit', 'update', 'destroy']);

// ---------------------------------------------------------
// Hearings (শুনানি) Routes
// ---------------------------------------------------------

Route::get('/dashboard/hearings/today', [HearingController::class, 'todayHearings'])->name('hearings.today'); // আজকের শুনানি তালিকা
Route::post('/dashboard/files/{file}/hearings', [HearingController::class, 'store'])->name('hearings.store'); // নতুন শুনানির আদেশ সেভ করা
