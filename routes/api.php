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
});


Route::get('/.well-known/jwks.json', [KeyController::class, 'jwks']);
Route::get('/.well-known/jwks', [KeyController::class, 'index']);
Route::post('/service/token', [ServiceAuthController::class, 'token']);


//هذا endpoint يجب أن يكون لـ services فقط وليس للمستخدمين.
Route::middleware('service.auth')->group(function () {

    Route::get('/users/{id}', [UserInfoController::class, 'show']);
    Route::get('/me',[MeController::class,'index']);
});
