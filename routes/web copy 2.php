<?php

use App\Http\Controllers\SchoolController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/excel', [ExcelController::class, 'importView'])->name('excel.view');
Route::post('/excel/', [ExcelController::class, 'convert'])->name('excel.convert');

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
            return redirect()->route('personnels.profile', ['personnel' => $user->personnel->id]);
        } elseif ($user->role === 'school_head') {
            return redirect()->route('schools.profile', ['school' => $user->personnel->school]);
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('/login');
        }
    });

    Route::get('personnels/export/{personnel}', [PersonnelController::class, 'export'])->name('personnels.export');

    Route::middleware(['user-access:teacher'])->group(function () {
        Route::get('/personnel/profile', [PersonnelController::class, 'profile'])->name('personnels.profile');
        Route::patch('/personnels/{personnel}', [PersonnelController::class, 'update'])->name('personnels.update');

        // Route::get('/personnels/{personnel}', [PersonnelController::class, 'show'])->name('personnels.show');
    });

    Route::middleware(['user-access:school_head'])->group(function () {
        Route::controller(SchoolController::class)->group(function(){
            Route::get('school/create', 'create')->name('schools.create');
            Route::post('schools/', 'store')->name('schools.store');
            Route::get('schools/{school}/edit', 'edit')->name('schools.edit');
            // Route::get('school/{school}', 'show')->name('schools.show');
            Route::get('school/{school}', 'show')->name('schools.profile');
            Route::patch('schools/{school}', 'update')->name('schools.update');
            Route::delete('schools/{school}', 'destroy')->name('schools.destroy');
            Route::get('/schools/export/{school}', 'export')->name('schools.export');
        });


        Route::controller(PersonnelController::class)->group(function(){
            // Route::get('personnel/create', 'create')->name('personnels.create');
            Route::get('personnels/', 'index')->name('personnels.index');
            Route::get('personnels/{personnel}/edit', 'edit')->name('personnels.edit');
            Route::get('personnels/export/{personnel}', 'export')->name('personnels.export');
            Route::patch('personnels/{personnel}', 'update')->name('personnels.update');
            // Route::get('personnel/{personnel}', 'show')->name('personnels.profile');
        });
    });

    Route::middleware(['user-access:admin'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('admin.home');

        Route::get('/loyalty-awards', [PersonnelController::class, 'loyaltyAwards'])->name('loyalty.awards');

        Route::resource('positions', PositionController::class);
        Route::resource('districts', DistrictController::class);



        // Route::resource('personnels', PersonnelController::class)->except('create');

        Route::controller(PersonnelController::class)->group(function(){
            Route::get('personnels/', 'index')->name('personnels.index');
            Route::get('personnel/create', 'create')->name('personnels.create');
            Route::post('personnels/', 'store')->name('personnels.store');
            Route::get('personnels/{personnel}/edit', 'edit')->name('personnels.edit');
            Route::get('personnels/export/{personnel}', 'export')->name('personnels.export');
            Route::patch('personnels/{personnel}', 'update')->name('personnels.update');
            Route::get('personnels/{personnel}', 'show')->name('personnels.show');
            Route::delete('personnels/{personnel}', 'destroy')->name('personnels.destroy');
        });

        Route::controller(SchoolController::class)->group(function(){
            Route::get('schools/', 'index')->name('schools.index');
            Route::get('school/create', 'create')->name('schools.create');
            Route::post('schools/', 'store')->name('schools.store');
            Route::get('schools/{school}/edit', 'edit')->name('schools.edit');
            Route::get('schools/{school}', 'show')->name('schools.show');
            Route::patch('schools/{school}', 'update')->name('schools.update');
            Route::delete('schools/{school}', 'destroy')->name('schools.destroy');
            Route::get('/schools/export/{school}', 'export')->name('schools.export');
        });

        // Route::get('/personnel/create', PersonnelController::class .'@index')->name('personnel.create');
        Route::get('/personnel/create', function () {
            return view('personnel.create');
        })->name('personnel.create');

        Route::get('/accounts', UserController::class .'@index')->name('accounts.index');
        Route::post('/accounts', [UserController::class, 'store'])->name('accounts.store');
        Route::put('/accounts/{account}', UserController::class .'@update')->name('accounts.update');
        Route::delete('/accounts/{account}', UserController::class .'@destroy')->name('accounts.destroy');
    });
});
