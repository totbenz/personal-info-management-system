<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});

// Role-based redirection
Route::middleware(['user.access:admin'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('admin.home');
});

Route::middleware(['user.access:school_head'])->group(function () {
    Route::get('school-profile', [HomeController::class, 'index'])->name('schools.show');
});

Route::middleware(['user.access:personnel'])->group(function () {
    Route::get('profile', [HomeController::class, 'profile'])->name('personnels.show');
});
