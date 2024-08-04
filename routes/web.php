<?php

use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\StripeController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::get('/profile', function () {
    return view('auth.profile');
})->name('profile');


route::get('/verify-mail/{token}', [UserController::class, 'verificationMail']);

route::get('/reset-password', [PasswordResetController::class, 'resetPasswordLoad']);
route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::get('/forget-password', function () {
    return view('forgetPassword');
})->name('forget-password');





Route::post('stripe', [StripeController::class, 'stripe'])->name('stripe');
Route::get('success', [StripeController::class, 'success'])->name('success');
Route::get('payment', [StripeController::class, 'payment'])->name('payment');

Route::get('cancel', [StripeController::class, 'cancel'])->name('cancel');
Route::post('refund', [StripeController::class, 'refund'])->name('refund');







