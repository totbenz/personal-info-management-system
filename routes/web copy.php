<?php

use App\Http\Controllers\SchoolController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller('App\Http\Controllers\Auth\LoginController'::class)->group(function(){
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::controller('App\Http\Controllers\Auth\RegisterController'::class)->group(function(){
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        if ($user->role === 'teacher') {
            return redirect()->route('personnels.show', ['personnel' => $user->personnel->id]);
        } elseif ($user->role === 'school_head') {
            return redirect()->route('schools.show', ['school' => $user->personnel->school]);
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('/login');
        }
    });

    Route::middleware(['user-access:teacher'])->group(function () {
        Route::get('/personnels/{personnel}', [PersonnelController::class, 'show'])->name('personnels.show');
        Route::patch('/personnels/{personnel}', [PersonnelController::class, 'update'])->name('personnels.update');
    });

    Route::middleware(['user-access:school_head'])->group(function () {
        Route::get('/schools/{school}', [SchoolController::class, 'show'])->name('schools.show');
        Route::patch('/schools/{school}', [SchoolController::class, 'update'])->name('schools.update');

        Route::get('/schools/export/{school}', [SchoolController::class, 'export'])->name('schools.export');

        Route::controller(PersonnelController::class)->group(function(){
            Route::get('personnels/', 'index')->name('personnels.index');
            Route::get('personnel/create', 'create')->name('personnels.create');
            Route::post('personnels/', 'store')->name('personnels.store');
            Route::get('personnels/{personnel}/edit', 'edit')->name('personnels.edit');
            Route::patch('personnels/{personnel}', 'update')->name('personnels.update');
            Route::get('personnels/{personnel}', 'show')->name('personnels.show');
            Route::delete('personnels/', 'destroy')->name('personnels.destroy');
        });
    });

    Route::middleware(['user-access:admin'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('admin.home');

        Route::get('/loyalty-awards', [PersonnelController::class, 'loyaltyAwards'])->name('loyalty.awards');

        Route::resource('schools', SchoolController::class);
        Route::get('/schools/export/{school}', [SchoolController::class, 'export'])->name('schools.export');

        Route::resource('positions', PositionController::class);
        Route::resource('districts', DistrictController::class);

        Route::controller(PersonnelController::class)->group(function(){
            Route::get('personnels/', 'index')->name('personnels.index');
            Route::get('personnel/create', 'create')->name('personnels.create');
            Route::post('personnels/', 'store')->name('personnels.store');
            Route::get('personnels/{personnel}/edit', 'edit')->name('personnels.edit');
            Route::patch('personnels/{personnel}', 'update')->name('personnels.update');
            Route::get('personnels/{personnel}', 'show')->name('personnels.show');
            Route::delete('personnels/', 'destroy')->name('personnels.destroy');
        });
    });
});
