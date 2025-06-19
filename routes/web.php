<?php

use App\Http\Controllers\SchoolController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\SalaryGradeController;
use App\Http\Controllers\SalaryStepController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceRecordController;

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
            return redirect()->route('personnel.profile');
        } elseif ($user->role === 'school_head') {
            return redirect()->route('schools.profile', ['school' => $user->personnel->school]);
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('/login');
        }
    });
    Route::get('/profile/export', [PersonnelController::class, 'exportTeacherProfile'])->name('teacher-profile.export');
    // PERSONNEL ACCESS
    Route::middleware(['user-access:teacher'])->group(function () {
        Route::get('profile/{personnel}', [PersonnelController::class, 'profile'])->name('personnel.profile');
        Route::get('/profile', [PersonnelController::class, 'profile'])->name('personnel.profile');
        Route::patch('personnels/{personnel}', [PersonnelController::class, 'update'])->name('personnels.update');
        Route::get('personnel/export/{personnel}', [PersonnelController::class, 'export'])->name('personnels.export');
    });

    // SCHOOL HEAD ACCESS
    Route::middleware(['user-access:school_head'])->group(function () {
        // school routes
        Route::controller(SchoolController::class)->group(function(){
            // Route::get('school/create', 'create')->name('schools.create');
            // Route::post('schools/', 'store')->name('schools.store');
            Route::get('schools/{school}/edit', 'edit')->name('schools.edit');
            Route::get('school/{school}', 'show')->name('schools.profile');
            Route::patch('schools/{school}', 'update')->name('schools.update');
            Route::get('school/export/{school}', 'export')->name('school.export');
        });
        //personnel routes
        Route::controller(PersonnelController::class)->group(function(){
            Route::get('personnels/', 'index')->name('school_personnels.index');
            Route::get('personnels/{personnel}/edit', 'edit')->name('school_personnels.edit');
            Route::patch('personnels/{personnel}', 'update')->name('school_personnels.update');
            // Route::get('personnel/{personnel}/export', [PersonnelController::class, 'export'])->name('pds.export');
            Route::get('/personnel/profile', [PersonnelController::class, 'profile'])->name('personnels.profile');
            Route::get('school/personnels/{personnel}', 'show')->name('school_personnels.show');
        });
    });
    // SERVICE RECORD
    Route::get('/personnels/{personnelId}/download-service-record', [ServiceRecordController::class, 'download'])->name('service-record.download');
    Route::get('/service-records/{personnelId}/preview', [ServiceRecordController::class, 'preview'])->name('service-records.preview');
    // ADMIN ACCESS
    Route::middleware(['user-access:admin'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('admin.home');

        // essential school and personnel-related routes
        Route::resource('positions', PositionController::class)->only('index', 'store', 'update', 'destroy');
        Route::resource('districts', DistrictController::class)->only('index', 'store', 'update', 'destroy');

        // personnel routes
        Route::controller(PersonnelController::class)->group(function(){
            Route::get('personnels/', 'index')->name('personnels.index');
            Route::get('personnel/create', 'create')->name('personnels.create');
            Route::post('personnels/', 'store')->name('personnels.store');
            Route::get('personnels/{personnel}/edit', 'edit')->name('personnels.edit');
            Route::patch('personnels/{personnel}', 'update')->name('personnels.update');
            Route::get('personnels/export/{personnel}', 'export')->name('personnels.export');
            Route::get('personnels/{personnel}', 'show')->name('personnels.show');
            Route::delete('personnels/{personnel}', 'destroy')->name('personnels.destroy');
        });

        Route::controller(SchoolController::class)->group(function(){
            Route::get('schools/', 'index')->name('schools.index');
            Route::get('school/create', 'create')->name('schools.create');
            Route::post('schools/', 'store')->name('schools.store');
            Route::get('schools/{school}/edit', 'edit')->name('schools.edit');
            Route::patch('schools/{school}', 'update')->name('schools.update');
            Route::get('schools/{school}', 'show')->name('schools.show');
            Route::get('/schools/export/{school}', 'export')->name('schools.export');
            Route::delete('schools/{school}', 'destroy')->name('schools.destroy');
        });

        Route::controller(UserController::class)->group(function(){
            Route::get('accounts/', 'index')->name('accounts.index');
            Route::post('accounts/', 'store')->name('accounts.store');
            Route::patch('accounts/{account}', 'update')->name('accounts.update');
            Route::delete('accounts/{account}', 'destroy')->name('accounts.destroy');
        });

        Route::controller(SalaryGradeController::class)->group(function()
        {
            Route::get('salary-grades/', 'index')->name('salary_grades.index');
        });

        Route::controller(SalaryStepController::class)->group(function()
        {
            Route::get('salary-steps/', 'index')->name('salary_steps.index');
        });
    });

});
