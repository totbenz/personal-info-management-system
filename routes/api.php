<?php

use App\Models\District;
use App\Models\Position;
use App\Models\School;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/districts', function () {
    $query = District::query();

    if ($search = request('search')) {
        $query->where('name', 'like', "%{$search}%");
    }

    if ($selected = request('selected')) {
        $query->where('id', $selected);
    }

    return $query->get(['id','name']);
})->name('api.districts.index');

Route::get('/schools', function () {
    $query = School::query();

    if ($search = request('search')) {
        $query->where('school_name', 'like', "%{$search}%");
    }

    if ($selected = request('selected')) {
        $query->where('id', $selected);
    }

    return $query->get(['id','school_id','school_name']);
})->name('api.schools.index');

Route::get('/positions', function () {
    $query = Position::query();

    if ($search = request('search')) {
        $query->where('title', 'like', "%{$search}%");
    }

    if ($selected = request('selected')) {
        $query->where('id', $selected);
    }

    return $query->get(['id','title']);
})->name('api.positions.index');

Route::get('/personnel-ids', function () {
    // Get all personnel_id values from the Personnel model
    return \App\Models\Personnel::pluck('personnel_id');
})->name('api.personnel_ids.index');

Route::get('/personnel-list', function () {
    return \App\Models\Personnel::select('personnel_id', 'first_name', 'last_name')
        ->get()
        ->map(function ($personnel) {
            return [
                'personnel_id' => $personnel->personnel_id,
                'full_name' => $personnel->last_name . ', ' . $personnel->first_name,
            ];
        });
})->name('api.personnel_list.index');


