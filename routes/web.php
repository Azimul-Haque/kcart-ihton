<?php

use Illuminate\Support\Facades\Route;

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


