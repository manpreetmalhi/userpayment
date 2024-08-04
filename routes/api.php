<?php

use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// http://127.0.0.1:8000/api/register @ $ #

route::get('/test', [UserController::class, 'test']);
Route::post('/forget-password', [PasswordResetController::class, 'forgetPassword']);
Route::post('/refund', [StripeController::class, 'refund']);

Route::middleware(['throttle:api'])->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login'])->middleware('throttle:login');
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/deactive-profile', [UserController::class, 'deactiveProfile']);
    Route::get('/send-verify-mail/{email}', [UserController::class, 'sendVerifyMail']);
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);

});





