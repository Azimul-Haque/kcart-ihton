<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ExamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::prefix('v1')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

    // অথেনটিকেটেড ইউজার রুট ( Sanctum দিয়ে সুরক্ষিত )
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });
    Route::middleware('auth:sanctum')->post('/update-name', [AuthController::class, 'updateName']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/home-data', [HomeController::class, 'getHomeData']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user/check-approval', [HomeController::class, 'checkUserApprovalStatus']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/home/exam-summary', [HomeController::class, 'getExamSummary']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/category/{id}/courses', [CourseController::class, 'getCategoryCourses']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/course/{id}/exams', [CourseController::class, 'getCourseExams']);
    });

    Route::get('/exam/{id}/questions', [ExamController::class, 'getExamQuestions']);

    // ৩. পরীক্ষার উত্তরপত্র ও প্রাপ্ত নাম্বার সেভ করার রুট
    Route::post('/exam/submit', [ExamController::class, 'submitExam']);
    Route::get('/course/{courseId}/exam/{examId}/merit-list', [ExamController::class, 'getExamMeritList']);
    
});












// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/testapi', 'APIController@test')->name('api.test');

// // Route::post('/generateotp', 'APIController@generateOTP')->name('api.generateotp');
// Route::post('/generateotp', 'APIController@generateOTP')->name('api.generateotp')->middleware('otp.throttle');
// Route::post('/loginorcreate', 'APIController@loginOrCreate')->name('api.loginorcreate');

// Route::get('/checkuid/{softtoken}/{phonenumber}', 'APIController@checkUid')->name('api.checkuid');
// Route::get('/checkpackagevalidity/{softtoken}/{phonenumber}', 'APIController@checkPackageValidity')->name('api.checkpackagevalidity');
// Route::post('/adduser', 'APIController@addUser')->name('api.adduser');
// Route::post('/addonesignaldata', 'APIController@addOneSignalData')->name('api.addonesignaldata');
// Route::post('/updateuser', 'APIController@updateUser')->name('api.updateuser');
// Route::post('/notification/single', 'APIController@sendSingleNotification')->name('api.sendsinglenotification');
// Route::get('/notification/test', 'APIController@testNotification')->name('api.testnotification');

// Route::get('/testcache', 'APIController@testCache')->name('api.testcache');
// Route::get('/getcourses/{softtoken}/{coursetype}', 'APIController@getCourses')->name('api.getcourses');
// Route::get('/getcourses/exams/{softtoken}/{id}', 'APIController@getCourseExams')->name('api.getcourses.exams');
// Route::get('/getothercourses/exams/{softtoken}/{coursetype}', 'APIController@getOtherCourseExams')->name('api.getothercourses.exams');
// Route::get('/getcourses/exam/{softtoken}/{id}/questions', 'APIController@getCourseExamQuestions')->name('api.getcourses.exam.questions');
// Route::get('/gettopicwise/exam/{softtoken}/{id}/questions', 'APIController@getTopicExamQuestions')->name('api.gettopicwise.exam.questions');
// Route::get('/gettopics/{softtoken}', 'APIController@getTopics')->name('api.gettopics');
// Route::get('/getpackages/{softtoken}', 'APIController@getPackages')->name('api.getpackages');
// Route::post('/payment/proceed', 'APIController@paymentProceed')->name('api.paymentproceed');

// // NEW VERSION CATEGORY WISE COURSES AND OTHERS
// Route::get('/getcourses/categorywise/{softtoken}/{category}', 'APIController@getCoursesCategoryWise')->name('api.getcategorywise');
// // NEW VERSION CATEGORY WISE COURSES AND OTHERS

// Route::post('/message/store', 'APIController@storeMessage')->name('api.storemessage');

// Route::get('/getpaymenthistory/{softtoken}/{phonenumber}', 'APIController@getPaymentHistory')->name('api.getpaymenthistory');

// Route::get('/getmaterials/{softtoken}', 'APIController@getMaterials')->name('api.getmaterials');
// Route::get('/getmaterials/single/{softtoken}/{id}', 'APIController@getSingleMaterial')->name('api.getsinglematerial');

// Route::post('/addexamresult', 'APIController@addExamResult')->name('api.addexamresult');
// Route::get('/meritlist/{softtoken}/{course_id}/{exam_id}', 'APIController@getMeritList')->name('api.getmeritlist');
// Route::post('/reportquestion', 'APIController@reportQuestion')->name('api.reportquestion');
// Route::get('/getexamcategories/{softtoken}', 'APIController@getExamCategories')->name('api.getexamcategories');
// Route::get('/getquestionbank/exams/{softtoken}/{getexamcategory}', 'APIController@getQBCatWise')->name('api.getquestionbank.exams');

// // Ambassador Code
// Route::get('/ambassador/profile/{softtoken}/{mobile}', 'APIController@getAmbassadorProfile');
// Route::post('/ambassador/payout-request', 'APIController@requestAmbassadorPayout');
// Route::post('/check-promo', 'APIController@checkPromoCode');

// // for dashboard
// Route::get('/search/topics/{softtoken}', 'APIController@searchTopics')->name('api.search.topics');
// Route::get('/gettopics/parentwise/{softtoken}/{parent_id}', 'APIController@getParentWiseTopics')->name('api.parentwise.topics');
// Route::get('/gettopicwise/questions/{softtoken}/{topic_id}', 'APIController@getTopicWiseQuestions')->name('api.topicwise.questions');