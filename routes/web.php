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
use App\Http\Controllers\ServiceCreditRequestController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\NosaController;
use App\Http\Controllers\NosiController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\SalaryChangesController;

// Debug route to show current service credit requests
Route::get('/debug/current-service-credits', function () {
    try {
        $allRequests = \App\Models\ServiceCreditRequest::with('teacher')->orderBy('created_at', 'desc')->get();
        $pendingRequests = \App\Models\ServiceCreditRequest::where('status', 'pending')->with('teacher')->orderBy('created_at', 'desc')->get();

        // Test admin dashboard query
        $adminDashboardQuery = \App\Models\ServiceCreditRequest::where('status', 'pending')
            ->with(['teacher'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return [
            'total_requests' => $allRequests->count(),
            'pending_requests' => $pendingRequests->count(),
            'admin_dashboard_query_count' => $adminDashboardQuery->count(),
            'all_requests' => $allRequests->map(function ($r) {
                return [
                    'id' => $r->id,
                    'teacher_id' => $r->teacher_id,
                    'teacher_name' => $r->teacher ? $r->teacher->first_name . ' ' . $r->teacher->last_name : 'No teacher',
                    'status' => $r->status,
                    'reason' => $r->reason,
                    'requested_days' => $r->requested_days,
                    'created_at' => $r->created_at->toDateTimeString()
                ];
            }),
            'pending_details' => $pendingRequests->map(function ($r) {
                return [
                    'id' => $r->id,
                    'teacher_id' => $r->teacher_id,
                    'teacher_name' => $r->teacher ? $r->teacher->first_name . ' ' . $r->teacher->last_name : 'No teacher relationship found',
                    'teacher_exists' => $r->teacher ? true : false,
                    'status' => $r->status,
                    'reason' => $r->reason,
                    'requested_days' => $r->requested_days,
                    'work_date' => $r->work_date ? $r->work_date->toDateString() : null,
                    'created_at' => $r->created_at->toDateTimeString()
                ];
            }),
            'admin_dashboard_data' => $adminDashboardQuery->map(function ($r) {
                return [
                    'id' => $r->id,
                    'teacher_name' => $r->teacher ? $r->teacher->first_name . ' ' . $r->teacher->last_name : 'No teacher',
                    'status' => $r->status,
                    'reason' => $r->reason,
                    'requested_days' => $r->requested_days
                ];
            })
        ];
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
})->name('debug.current-service-credits');

// Debug route to clear all test data and start fresh
Route::get('/debug/reset-service-credits', function () {
    try {
        // Delete all service credit requests
        $deletedCount = \App\Models\ServiceCreditRequest::truncate();

        // Verify deletion
        $remainingCount = \App\Models\ServiceCreditRequest::count();

        return [
            'success' => true,
            'message' => 'All service credit requests cleared',
            'remaining_count' => $remainingCount,
            'instruction' => 'Now test: 1) Login as teacher, 2) Submit service credit request, 3) Login as admin, 4) Check dashboard'
        ];
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage()
        ];
    }
})->name('debug.reset-service-credits');

// Debug route to test teacher service credit submission
Route::get('/debug/test-teacher-service-credit', function () {
    try {
        // Find a teacher user
        $teacher = \App\Models\User::where('role', 'teacher')->whereHas('personnel')->first();

        if (!$teacher) {
            return ['error' => 'No teacher found'];
        }

        // Log in as this teacher for the test
        \Illuminate\Support\Facades\Auth::login($teacher);

        // Create a test service credit request as if submitted by teacher
        $serviceCredit = \App\Models\ServiceCreditRequest::create([
            'teacher_id' => $teacher->personnel->id,
            'requested_days' => 1.0,
            'work_date' => now()->subDays(1)->toDateString(),
            'morning_in' => '08:00:00',
            'morning_out' => '12:00:00',
            'afternoon_in' => '13:00:00',
            'afternoon_out' => '17:00:00',
            'total_hours' => 8.0,
            'reason' => 'Teacher submitted - Weekend school activity',
            'description' => 'Supervised student activity during weekend',
            'status' => 'pending',
        ]);

        // Test if the admin query picks it up
        $adminQuery = \App\Models\ServiceCreditRequest::where('status', 'pending')
            ->with(['teacher'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return [
            'success' => true,
            'teacher_id' => $teacher->id,
            'teacher_email' => $teacher->email,
            'personnel_id' => $teacher->personnel->id,
            'teacher_name' => $teacher->personnel->first_name . ' ' . $teacher->personnel->last_name,
            'service_credit_id' => $serviceCredit->id,
            'admin_query_finds_it' => $adminQuery->where('id', $serviceCredit->id)->count() > 0,
            'total_pending_in_admin_query' => $adminQuery->count(),
            'created_request' => [
                'id' => $serviceCredit->id,
                'teacher_id' => $serviceCredit->teacher_id,
                'reason' => $serviceCredit->reason,
                'status' => $serviceCredit->status,
                'teacher_name' => $serviceCredit->teacher ? $serviceCredit->teacher->first_name . ' ' . $serviceCredit->teacher->last_name : 'No teacher relationship'
            ]
        ];
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
})->name('debug.test-teacher-service-credit');

// Public debug route (remove in production)
Route::get('/debug/create-service-credit-data', function () {
    try {
        $result = [];

        // Check if table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('service_credit_requests')) {
            return ['error' => 'service_credit_requests table does not exist. Run migrations first.'];
        }

        $result['table_exists'] = true;

        // Count existing requests
        $totalRequests = \App\Models\ServiceCreditRequest::count();
        $pendingRequests = \App\Models\ServiceCreditRequest::where('status', 'pending')->count();

        $result['existing_total'] = $totalRequests;
        $result['existing_pending'] = $pendingRequests;

        // Check for teachers with personnel
        $teachersCount = \App\Models\User::where('role', 'teacher')->whereHas('personnel')->count();
        $result['teachers_with_personnel'] = $teachersCount;

        if ($teachersCount === 0) {
            // Create a test teacher
            $personnel = \App\Models\Personnel::first();
            if (!$personnel) {
                return ['error' => 'No personnel records found. Create personnel data first.'];
            }

            $teacher = \App\Models\User::create([
                'email' => 'debug.teacher@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'teacher',
                'personnel_id' => $personnel->id,
            ]);

            $result['created_teacher'] = $teacher->email;
        } else {
            $teacher = \App\Models\User::where('role', 'teacher')->whereHas('personnel')->first();
            $result['using_teacher'] = $teacher->email;
        }

        // Create test requests if none exist
        if ($pendingRequests === 0) {
            $testRequests = [
                [
                    'teacher_id' => $teacher->personnel->id,
                    'requested_days' => 1.0,
                    'work_date' => now()->subDays(1)->toDateString(),
                    'morning_in' => '08:00:00',
                    'morning_out' => '12:00:00',
                    'afternoon_in' => '13:00:00',
                    'afternoon_out' => '17:00:00',
                    'total_hours' => 8.0,
                    'reason' => 'Emergency weekend school cleanup',
                    'description' => 'Assisted in cleaning school after storm damage',
                    'status' => 'pending',
                ],
                [
                    'teacher_id' => $teacher->personnel->id,
                    'requested_days' => 0.5,
                    'work_date' => now()->subDays(3)->toDateString(),
                    'morning_in' => '08:00:00',
                    'morning_out' => '12:00:00',
                    'afternoon_in' => null,
                    'afternoon_out' => null,
                    'total_hours' => 4.0,
                    'reason' => 'School event preparation',
                    'description' => 'Prepared materials for science fair',
                    'status' => 'pending',
                ]
            ];

            $created = [];
            foreach ($testRequests as $requestData) {
                $request = \App\Models\ServiceCreditRequest::create($requestData);
                $created[] = $request->id;
            }

            $result['created_requests'] = $created;
        }

        // Final count
        $result['final_total'] = \App\Models\ServiceCreditRequest::count();
        $result['final_pending'] = \App\Models\ServiceCreditRequest::where('status', 'pending')->count();

        // Test the dashboard query
        $dashboardQuery = \App\Models\ServiceCreditRequest::where('status', 'pending')
            ->with(['teacher'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $result['dashboard_query_count'] = $dashboardQuery->count();
        $result['dashboard_data'] = $dashboardQuery->map(function ($req) {
            return [
                'id' => $req->id,
                'teacher_name' => $req->teacher ? $req->teacher->first_name . ' ' . $req->teacher->last_name : 'Unknown',
                'reason' => $req->reason,
                'status' => $req->status,
                'requested_days' => $req->requested_days,
            ];
        });

        $result['success'] = true;
        $result['message'] = 'Test data created successfully. Check admin dashboard at /dashboard';

        return $result;
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
})->name('debug.create-service-credit-data');

Route::get('/debug/check-admin-users', function () {
    try {
        $adminUsers = \App\Models\User::where('role', 'admin')->get(['id', 'email', 'role', 'created_at']);
        $allRoles = \App\Models\User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get();

        return [
            'admin_users' => $adminUsers->toArray(),
            'all_user_roles' => $allRoles->toArray(),
            'total_users' => \App\Models\User::count(),
            'message' => 'Use one of these admin emails to login and access /dashboard'
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
})->name('debug.check-admin-users');

Route::middleware('guest')->group(function () {
    Route::controller('App\\Http\\Controllers\\Auth\\LoginController'::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/authenticate', 'authenticate')->name('authenticate');
    });
});

Route::controller('App\\Http\\Controllers\\Auth\\LoginController'::class)->group(function () {
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::controller('App\\Http\\Controllers\\Auth\\RegisterController'::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->role === 'non_teaching') {
            return redirect()->route('non_teaching.dashboard');
        } elseif ($user->role === 'school_head') {
            return redirect()->route('schools.profile', ['school' => $user->personnel->school]);
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('login');
        }
    });
    Route::get('/profile/export', [PersonnelController::class, 'exportTeacherProfile'])->name('teacher-profile.export');
    // PERSONNEL ACCESS - TEACHER
    Route::middleware(['user-access:teacher'])->group(function () {
        Route::get('/teacher-dashboard', [HomeController::class, 'teacherDashboard'])->name('teacher.dashboard');
        Route::get('profile/{personnel}', [PersonnelController::class, 'profile'])->name('personnel.profile');
        Route::get('/profile', [PersonnelController::class, 'profile'])->name('personnel.profile');
        Route::patch('personnels/{personnel}', [PersonnelController::class, 'update'])->name('personnels.update');
        Route::get('personnel/export/{personnel}', [PersonnelController::class, 'export'])->name('personnels.export');

        // Service Credit Routes
        Route::post('/service-credit-request', [ServiceCreditRequestController::class, 'store'])->name('service-credit-request.store');

        // Teacher Leave Routes
        Route::post('teacher/leaves/add', [App\Http\Controllers\TeacherLeaveController::class, 'addLeave'])->name('teacher.leaves.add');

        // Leave request submission
        Route::post('/leave-request', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave-request.store');
    });

    // PERSONNEL ACCESS - NON TEACHING (separate dashboard route name)
    Route::middleware(['user-access:non_teaching'])->group(function () {
        Route::get('/non-teaching-dashboard', [HomeController::class, 'nonTeachingDashboard'])->name('non_teaching.dashboard');
        Route::get('/profile', [PersonnelController::class, 'profile'])->name('personnel.profile2');
        Route::patch('personnels/{personnel}', [PersonnelController::class, 'update'])->name('personnels.update');
        Route::get('personnel/export/{personnel}', [PersonnelController::class, 'export'])->name('personnels.export');

        // Non-Teaching Leave Routes
        Route::post('non-teaching/leaves/add', [App\Http\Controllers\NonTeachingLeaveController::class, 'addLeave'])->name('non_teaching.leaves.add');

        // (CTO request route defined once globally below to avoid duplicates)

        // Leave request submission
        Route::post('/leave-request', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave-request.store');
    });

    // SCHOOL HEAD ACCESS
    Route::middleware(['user-access:school_head'])->group(function () {
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
        Route::post('school-head/leaves/add', [App\Http\Controllers\SchoolHeadLeaveController::class, 'addLeave'])->name('school_head.leaves.add');
        // (Global CTO request route defined below)

        // Leave request submission for school heads
        Route::post('/leave-request', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave-request.store');
    });

    // Leave request submission - available to all authenticated roles
    Route::post('/leave-request', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave-request.store');

    // Global CTO request route (teacher, non_teaching, school_head)
    Route::post('/cto-request', [\App\Http\Controllers\CTORequestController::class, 'store'])->name('cto-request.store');
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
        // JSON feed for Service Credit pending requests (AJAX refresh)
        Route::get('/admin/service-credit-requests/pending.json', [ServiceCreditRequestController::class, 'pendingJson'])->name('admin.service-credit-requests.pending-json');

        // TEMP DEBUG: recent Service Credit requests (remove in production)
        Route::get('/admin/_debug/service-credits', function () {
            return \App\Models\ServiceCreditRequest::orderByDesc('id')->take(10)->get();
        })->name('admin.debug.service-credits');

        Route::get('/admin/_debug/service-credits-schema', function () {
            $hasTable = \Illuminate\Support\Facades\Schema::hasTable('service_credit_requests');
            $columns = $hasTable ? \Illuminate\Support\Facades\Schema::getColumnListing('service_credit_requests') : [];
            return [
                'has_table' => $hasTable,
                'columns' => $columns,
                'latest' => $hasTable ? \App\Models\ServiceCreditRequest::orderByDesc('id')->take(5)->get() : [],
            ];
        })->name('admin.debug.service-credits-schema');

        Route::get('/admin/_debug/create-test-service-credit', function () {
            try {
                // Find a teacher user with personnel
                $teacher = \App\Models\User::where('role', 'teacher')
                    ->whereHas('personnel')
                    ->with('personnel')
                    ->first();

                if (!$teacher) {
                    // Create a test teacher if none exists
                    $personnel = \App\Models\Personnel::first();
                    if (!$personnel) {
                        return ['error' => 'No personnel records found. Create personnel first.'];
                    }

                    $teacher = \App\Models\User::create([
                        'email' => 'debug.teacher@test.com',
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'role' => 'teacher',
                        'personnel_id' => $personnel->id,
                    ]);
                }

                // Check if test request already exists
                $existing = \App\Models\ServiceCreditRequest::where('teacher_id', $teacher->personnel->id)
                    ->where('reason', 'LIKE', 'DEBUG%')
                    ->first();

                if ($existing) {
                    return [
                        'message' => 'DEBUG test request already exists',
                        'request_id' => $existing->id,
                        'status' => $existing->status,
                        'teacher' => $teacher->personnel->first_name . ' ' . $teacher->personnel->last_name
                    ];
                }

                // Create test request
                $request = \App\Models\ServiceCreditRequest::create([
                    'teacher_id' => $teacher->personnel->id,
                    'requested_days' => 1.0,
                    'work_date' => now()->subDays(1),
                    'morning_in' => '08:00',
                    'morning_out' => '12:00',
                    'afternoon_in' => '13:00',
                    'afternoon_out' => '17:00',
                    'total_hours' => 8.0,
                    'reason' => 'DEBUG: Test request for admin dashboard',
                    'description' => 'This is a debug test request to verify the admin dashboard displays service credit requests',
                    'status' => 'pending',
                ]);

                // Verify the request can be fetched
                $testFetch = \App\Models\ServiceCreditRequest::where('status', 'pending')
                    ->with(['teacher'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                return [
                    'success' => true,
                    'message' => 'DEBUG service credit request created successfully',
                    'request_id' => $request->id,
                    'teacher' => $teacher->personnel->first_name . ' ' . $teacher->personnel->last_name,
                    'teacher_id' => $teacher->personnel->id,
                    'total_pending_requests' => $testFetch->count(),
                    'redirect_message' => 'Now check /dashboard to see if it appears'
                ];
            } catch (\Exception $e) {
                return [
                    'error' => 'Failed to create test request',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ];
            }
        })->name('admin.debug.create-test-service-credit');

        // Approved requests filtering and PDF downloads
        Route::get('/admin/approved-leave-requests/filter', [HomeController::class, 'filterApprovedLeaveRequests'])->name('admin.approved-leave-requests.filter');
        Route::get('/admin/approved-cto-requests/filter', [HomeController::class, 'filterApprovedCTORequests'])->name('admin.approved-cto-requests.filter');
        Route::get('/admin/approved-leave-requests/download-pdf', [HomeController::class, 'downloadApprovedLeaveRequestsPDF'])->name('admin.approved-leave-requests.download-pdf');
        Route::get('/admin/approved-cto-requests/download-pdf', [HomeController::class, 'downloadApprovedCTORequestsPDF'])->name('admin.approved-cto-requests.download-pdf');

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

        // Service Credit Requests (teacher only) admin approval
        Route::get('/admin/service-credit-requests', [ServiceCreditRequestController::class, 'index'])->name('admin.service-credit-requests');
        Route::post('/admin/service-credit-requests/{serviceCreditRequest}/approve', [ServiceCreditRequestController::class, 'approve'])->name('admin.service-credit-requests.approve');
        Route::post('/admin/service-credit-requests/{serviceCreditRequest}/deny', [ServiceCreditRequestController::class, 'deny'])->name('admin.service-credit-requests.deny');

        // Leave Management admin interface
        Route::get('/admin/leave-management', [\App\Http\Controllers\LeaveManagementController::class, 'index'])->name('admin.leave-management');
        Route::post('/admin/leave-management/add', [\App\Http\Controllers\LeaveManagementController::class, 'addLeave'])->name('admin.leave-management.add');
        Route::get('/admin/leave-management/personnel/{personnelId}', [\App\Http\Controllers\LeaveManagementController::class, 'getPersonnelLeaves'])->name('admin.leave-management.personnel');

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

    // Loyalty awards routes
    Route::get('/loyalty-awards/export-10year-pdf', [\App\Http\Controllers\LoyaltyAwardController::class, 'export10YearPdf'])->name('loyalty-awards.export-10year-pdf');
    Route::get('/loyalty-awards/export-5year-pdf', [\App\Http\Controllers\LoyaltyAwardController::class, 'export5YearPdf'])->name('loyalty-awards.export-5year-pdf');
    Route::post('/loyalty-awards/claim', [\App\Http\Controllers\LoyaltyAwardController::class, 'claim'])->name('loyalty-awards.claim');
    Route::get('/personnels/{personnel}/loyalty-awards', [\App\Http\Controllers\LoyaltyAwardController::class, 'show'])->name('loyalty-awards.show');
});
