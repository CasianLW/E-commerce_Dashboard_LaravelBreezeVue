<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\AuthSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthSessionController::class, 'RegisterIndex'])
                ->name('register');
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //             ->name('register');

    Route::post('register', [AuthSessionController::class, 'RegisterStore']);
    // Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::get('login', [AuthSessionController::class, 'LoginIndex'])
                ->name('login');
    // Route::get('login', [AuthenticatedSessionController::class, 'create'])
    //             ->name('login');

    Route::post('/login', [AuthSessionController::class, 'LoginStore']);
    // Route::post('login', [AuthenticatedSessionController::class, 'store']);
 
});
