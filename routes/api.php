<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceAuthController;
use App\Http\Controllers\UserInfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/refresh', [AuthController::class, 'refresh']);


Route::middleware(['auth.jwt'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);//->middleware('auth.jwt');
    Route::post('/create-project', [ProjectController::class, 'createProject']);//->middleware('auth.jwt');
    // Route::post('select-project', [ProjectController::class, 'select'])->middleware('platform.auth');
    Route::post('invite-user', [ProjectController::class, 'invitePerson']);
    Route::post('verify-invitation', [ProjectController::class, 'verifyJoinProject']);
    Route::get('my-profile', [MeController::class, 'myProfile']);//new الوصول إلى بيانات مستخدم عن طريق ال access token
});


Route::get('/.well-known/jwks.json', [KeyController::class, 'jwks']);
Route::get('/.well-known/jwks', [KeyController::class, 'index']);// نشر المفتاج العام
Route::post('create-service', [ServiceAuthController::class, 'createService']);// new إنشاء خدمة
Route::post('/service/token', [ServiceAuthController::class, 'token']);// توليد توكن للخدمة

//هذا endpoint يجب أن يكون لـ services فقط وليس للمستخدمين.
Route::middleware('service.auth')->group(function () {
    Route::get('/users/{id}', [UserInfoController::class, 'show']);
    Route::get('/me',[MeController::class,'index']);
    Route::get('/profile/{id}', [MeController::class, 'profile']);// new خدمة تصل إلى مستخدم محدد باستخدام التوكن الخاص بالخدمة و رقم المستخدم id
});
