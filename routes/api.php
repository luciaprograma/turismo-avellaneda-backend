<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ApiVerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ResendVerifyEmailController;
use App\Http\Controllers\ExcursionController;
use App\Http\Controllers\Auth\ChangePasswordController;


// -------------------- 
// AUTH + CSRF + COOKIES 
// -------------------- 
Route::middleware('web')->group(function () {

    

    // Login y logout
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // Email verification
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth:sanctum', 'throttle:6,1'])
        ->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', [ApiVerifyEmailController::class, '__invoke'])
        ->middleware(['signed'])
        ->name('verification.verify');

   
});

// -------------------- 
// RUTAS SIN CSRF
// -------------------- 

// Registro de usuario
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->withoutMiddleware([\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class])
    ->name('register');

// Password reset
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->withoutMiddleware([\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class])
    ->middleware('throttle:5,1');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->withoutMiddleware([\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class]);


// Reenvío de link de verificación de correo
Route::post('/email/resend', [ResendVerifyEmailController::class, 'resend'])
    ->withoutMiddleware([\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class]);


// Usuario autenticado
Route::middleware(['web', 'auth:sanctum'])->get('/user', fn (Request $request) => $request->user());


// Profile
Route::middleware(['web', 'auth:sanctum'])->post('/profile', [ProfileController::class, 'store']);
Route::middleware(['web', 'auth:sanctum'])->get('/profile', [ProfileController::class, 'show']);



// Health check
Route::get('/health', fn () => response()->json(['ok' => true, 'time' => now()->toISOString()]));

//Excursiones pasajero
Route::middleware(['web', 'auth:sanctum'])->get('/excursions', [ExcursionController::class, 'indexForPassenger']);
Route::middleware(['web', 'auth:sanctum'])->get('/excursions/{id}', [ExcursionController::class, 'showForPassenger']);
Route::middleware(['web', 'auth:sanctum'])->post('/excursions/register', [ExcursionController::class, 'registerToExcursion']);

//Cambio de contraseña
Route::middleware(['web', 'auth:sanctum'])->post('/change-password', [ChangePasswordController::class, 'update']);