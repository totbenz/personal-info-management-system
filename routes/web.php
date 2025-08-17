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
use App\Http\Controllers\EventController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\NosaController;
use App\Http\Controllers\NosiController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\SalaryChangesController;

Route::middleware('guest')->group(function () {
    Route::controller('App\Http\Controllers\Auth\LoginController'::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/authenticate', 'authenticate')->name('authenticate');
    });
});

Route::controller('App\Http\Controllers\Auth\LoginController'::class)->group(function () {
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::controller('App\Http\Controllers\Auth\RegisterController'::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
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
        Route::get('/teacher-dashboard', [HomeController::class, 'teacherDashboard'])->name('teacher.dashboard');
        Route::get('profile/{personnel}', [PersonnelController::class, 'profile'])->name('personnel.profile');
        Route::get('/profile', [PersonnelController::class, 'profile'])->name('personnel.profile');
        Route::patch('personnels/{personnel}', [PersonnelController::class, 'update'])->name('personnels.update');
        Route::get('personnel/export/{personnel}', [PersonnelController::class, 'export'])->name('personnels.export');
        
        // Service Credit Routes
        Route::post('/service-credit-request', [App\Http\Controllers\ServiceCreditRequestController::class, 'store'])->name('service-credit-request.store');

        // Leave request submission
        Route::post('/leave-request', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave-request.store');
    });

    // SCHOOL HEAD ACCESS
    Route::middleware(['user-access:school_head'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('admin.home');

        // school routes
        Route::get('/school-head-dashboard', [HomeController::class, 'schoolHeadDashboard'])->name('school_head.dashboard');
        Route::controller(SchoolController::class)->group(function () {
            // Route::get('school/create', 'create')->name('schools.create');
            // Route::post('schools/', 'store')->name('schools.store');
            Route::get('schools/{school}/edit', 'edit')->name('schools.edit');
            Route::get('school/{school}', 'show')->name('schools.profile');
            Route::patch('schools/{school}', 'update')->name('schools.update');
            Route::get('school/export/{school}', 'export')->name('school.export');
        });
        //personnel routes
        Route::controller(PersonnelController::class)->group(function () {
            Route::get('personnels/', 'index')->name('school_personnels.index');
            Route::get('personnels/{personnel}/edit', 'edit')->name('school_personnels.edit');
            Route::patch('personnels/{personnel}', 'update')->name('school_personnels.update');
            Route::get('personnel/{personnel}/export', [PersonnelController::class, 'export'])->name('pds.export');
            Route::get('/personnel/profile', [PersonnelController::class, 'profile'])->name('personnels.profile');
            Route::get('school/personnels/{personnel}', 'show')->name('school_personnels.show');
        });

        // School Head Leaves
        Route::get('school-head/leaves', [App\Http\Controllers\SchoolHeadLeaveController::class, 'index'])->name('school_head.leaves');
        // CTO Request Routes
        Route::post('/cto-request', [\App\Http\Controllers\CTORequestController::class, 'store'])->name('cto-request.store');

        // Leave request submission for school heads
        Route::post('/leave-request', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave-request.store');
    });

    // Leave request submission - available to both teachers and school heads
    Route::post('/leave-request', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave-request.store');
    // SERVICE RECORD
    Route::get('/personnels/{personnelId}/download-service-record', [ServiceRecordController::class, 'download'])->name('service-record.download');
    Route::get('/service-records/{personnelId}/preview', [ServiceRecordController::class, 'preview'])->name('service-records.preview');
    Route::get('/service-records/{personnelId}', [ServiceRecordController::class, 'index'])->name('service-records.index');

    // //NOSA
    // Route::get('/personnels/{personnelId}/download-nosa', [NosaController::class, 'download'])->name('nosa.download');
    // Route::get('/nosa/{personnelId}/preview', [NosaController::class, 'preview'])->name('nosa.preview');

    // //NOSI
    // Route::get('/personnels/{personnelId}/download-nosi', [NosiController::class, 'download'])->name('nosi.download');
    // Route::get('/nosi/{personnelId}/preview', [NosiController::class, 'preview'])->name('nosi.preview');
    //DOWNLOAD ALL
    Route::get('/personnels/{personnelId}/download-all', [DownloadController::class, 'downloadAll'])->name('download-all.download');

    //DOWNLOAD SPECIFIC TYPE
    Route::get('/personnels/{personnelId}/download/{type}', [DownloadController::class, 'downloadSpecific'])->name('download-specific.download');

    // ADMIN ACCESS
    Route::middleware(['user-access:admin'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('admin.home');

        // essential school and personnel-related routes
        Route::resource('positions', PositionController::class)->only('index', 'store', 'update', 'destroy');
        Route::delete('positions/{position}', [PositionController::class, 'deletePosition'])->name('positions.deletePosition');

        Route::resource('districts', DistrictController::class)->only('index', 'store', 'update', 'destroy');

        // personnel routes
        Route::controller(PersonnelController::class)->group(function () {
            Route::get('personnels/', 'index')->name('personnels.index');
            Route::get('personnel/create', 'create')->name('personnels.create');
            Route::post('personnels/', 'store')->name('personnels.store');
            Route::get('personnels/{personnel}/edit', 'edit')->name('personnels.edit');
            Route::patch('personnels/{personnel}', 'update')->name('personnels.update');
            Route::get('personnels/export/{personnel}', 'export')->name('personnels.export');
            Route::get('personnels/{personnel}', 'show')->name('personnels.show');
            Route::delete('personnels/{personnel}', 'destroy')->name('personnels.destroy');
        });

        // Leave request admin view and approval
        Route::get('/admin/leave-requests', [\App\Http\Controllers\LeaveRequestController::class, 'index'])->name('admin.leave-requests');
        Route::post('/admin/leave-requests/{id}', [\App\Http\Controllers\LeaveRequestController::class, 'update'])->name('admin.leave-requests.update');

        // CTO Request admin view and approval
        Route::get('/admin/cto-requests', [\App\Http\Controllers\CTORequestController::class, 'index'])->name('admin.cto-requests');
        Route::post('/admin/cto-requests/{ctoRequest}/approve', [\App\Http\Controllers\CTORequestController::class, 'approve'])->name('admin.cto-requests.approve');
        Route::post('/admin/cto-requests/{ctoRequest}/deny', [\App\Http\Controllers\CTORequestController::class, 'deny'])->name('admin.cto-requests.deny');

        Route::controller(SchoolController::class)->group(function () {
            Route::get('schools/', 'index')->name('schools.index');
            Route::get('school/create', 'create')->name('schools.create');
            Route::post('schools/', 'store')->name('schools.store');
            Route::get('schools/{school}/edit', 'edit')->name('schools.edit');
            Route::patch('schools/{school}', 'update')->name('schools.update');
            Route::get('schools/{school}', 'show')->name('schools.show');
            Route::get('/schools/export/{school}', 'export')->name('schools.export');
            Route::delete('schools/{school}', 'destroy')->name('schools.destroy');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('accounts/', 'index')->name('accounts.index');
            Route::post('accounts/', 'store')->name('accounts.store');
            Route::patch('accounts/{account}', 'update')->name('accounts.update');
            Route::delete('accounts/{account}', 'destroy')->name('accounts.destroy');
        });

        Route::controller(SalaryGradeController::class)->group(function () {
            Route::get('salary-grades/', 'index')->name('salary_grades.index');
        });

        Route::controller(SalaryStepController::class)->group(function () {
            Route::get('salary-steps/', 'index')->name('salary_steps.index');
        });

        Route::controller(SalaryChangesController::class)->group(function () {
            Route::get('personnels/{personnel}/salary-changes', 'index')->name('personnel-salary-changes.index');
            Route::get('personnels/{personnel}/salary-changes/{change}/download', 'download')->name('personnel-salary-changes.download');
        });
        // Events routes
        Route::resource('events', EventController::class);

        // Signatures Settings (admin only)
        Route::get('/admin/signatures', [\App\Http\Controllers\SignatureController::class, 'edit'])->name('admin.signatures.edit');
        Route::post('/admin/signatures', [\App\Http\Controllers\SignatureController::class, 'update'])->name('admin.signatures.update');
    });
    // SETTINGS ROUTE
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    Route::post('/settings/change-password', [UserController::class, 'changePassword'])->name('settings.changePassword');

    // Add this route for loyalty awards PDF export
    Route::get('/loyalty-awards/export-pdf', [\App\Livewire\Datatable\LoyaltyDatatable::class, 'exportPdf'])->name('loyalty-awards.export-pdf');
});
