<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use App\Models\TeacherLeave;
use App\Models\SchoolHeadLeave;
use App\Models\NonTeachingLeave;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return view('user.index', compact('user'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'personnel_id' => 'required|integer',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:8',
                'role' => 'required|in:teacher,school_head,non_teaching,admin'
            ]);

            $personnel = Personnel::where('personnel_id', $request->personnel_id)->firstOrFail();

            $user = User::create([
                'personnel_id' => $personnel->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Create initial leave data based on role
            $this->createInitialLeaveData($personnel->id, $request->role);

            $message = 'User Data Added Successfully';
            $bannerStyle = 'success';

        } catch (ValidationException $e) {
            $errorMessage = 'Validation errors occurred:<br>';
            $errorMessage .= implode('<br>', $e->validator->errors()->all());
            $message = $errorMessage;
            $bannerStyle = 'danger';

        } catch (\Exception $e) {
            $message = 'Failed To Add User: ' . $e->getMessage();
            $bannerStyle = 'danger';
        }

        session()->flash('flash.banner', $message);
        session()->flash('flash.bannerStyle', $bannerStyle);

        return redirect()->back();
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'personnel_id' => 'required',
                'email' => 'required',
                'role' => 'required'
            ]);

                $personnel = Personnel::where('personnel_id', $request->personnel_id)->firstOrFail();
                // Prevent duplicate account creation for the same personnel
                if ($personnel->user) {
                    $message = 'This personnel already has an account.';
                    $bannerStyle = 'danger';
                } else {
                    User::create([
                        'personnel_id' => $personnel->id,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role' => $request->role,
                    ]);
                    $message = 'User Data Added Successfully';
                    $bannerStyle = 'success';
                }
            $bannerStyle = 'success';

        } catch (ValidationException $e) {
            $errorMessage = 'Validation errors occurred:<br>';
            $errorMessage .= implode('<br>', $e->validator->errors()->all());
            $message = $errorMessage;
            $bannerStyle = 'danger';

        } catch (ModelNotFoundException $e) { // User not found
            $message = 'User Data Updated Successfully';
            $bannerStyle = 'success';
        } catch (\Exception $e) {
            $message = 'Failed To Add User.';
            $bannerStyle = 'danger';
        }

        session()->flash('flash.banner', $message);
        session()->flash('flash.bannerStyle', $bannerStyle);

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            $message = 'User deleted successfully';
            $bannerStyle = 'success';
        } catch (\Exception $e) {
            $message = 'Failed to delete User';
            $bannerStyle = 'danger';
        }

        session()->flash('flash.banner', $message);
        session()->flash('flash.bannerStyle', $bannerStyle);

        return redirect()->back();
    }

    /**
     * Change password for the authenticated user
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Retrieve the fresh User model instance if needed
        $userModel = \App\Models\User::find($user->id);
        $userModel->password = Hash::make($request->new_password);
        $userModel->save();

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Create initial leave data for a new user based on their role
     */
    private function createInitialLeaveData($personnelId, $role)
    {
        $year = Carbon::now()->year;

        if ($role === 'teacher') {
            $leaveTypes = [
                'SICK LEAVE',
                'MATERNITY LEAVE',
                'PATERNITY LEAVE',
                'SOLO PARENT LEAVE',
                'STUDY LEAVE',
                'VAWC LEAVE',
                'REHABILITATION PRIVILEGE',
                'SPECIAL LEAVE BENEFITS FOR WOMEN',
                'SPECIAL EMERGENCY (CALAMITY LEAVE)',
                'ADOPTION LEAVE'
            ];

            foreach ($leaveTypes as $leaveType) {
                TeacherLeave::create([
                    'teacher_id' => $personnelId,
                    'leave_type' => $leaveType,
                    'year' => $year,
                    'available' => 0,
                    'used' => 0,
                    'remarks' => 'Auto-generated on account creation',
                ]);
            }
        } elseif ($role === 'school_head') {
            $leaveTypes = [
                'VACATION LEAVE',
                'SICK LEAVE',
                'MANDATORY FORCED LEAVE',
                'MATERNITY LEAVE',
                'PATERNITY LEAVE',
                'SPECIAL PRIVILEGE LEAVE',
                'SOLO PARENT LEAVE',
                'STUDY LEAVE',
                'VAWC LEAVE',
                'REHABILITATION PRIVILEGE',
                'SPECIAL LEAVE BENEFITS FOR WOMEN',
                'SPECIAL EMERGENCY (CALAMITY LEAVE)',
                'ADOPTION LEAVE'
            ];

            foreach ($leaveTypes as $leaveType) {
                SchoolHeadLeave::create([
                    'school_head_id' => $personnelId,
                    'leave_type' => $leaveType,
                    'year' => $year,
                    'available' => 0,
                    'used' => 0,
                    'ctos_earned' => 0,
                    'remarks' => 'Auto-generated on account creation',
                ]);
            }
        } elseif ($role === 'non_teaching') {
            $leaveTypes = [
                'VACATION LEAVE',
                'SICK LEAVE',
                'MANDATORY FORCED LEAVE',
                'MATERNITY LEAVE',
                'PATERNITY LEAVE',
                'SPECIAL PRIVILEGE LEAVE',
                'SOLO PARENT LEAVE',
                'STUDY LEAVE',
                'VAWC LEAVE',
                'REHABILITATION PRIVILEGE',
                'SPECIAL LEAVE BENEFITS FOR WOMEN',
                'SPECIAL EMERGENCY (CALAMITY LEAVE)',
                'ADOPTION LEAVE'
            ];

            foreach ($leaveTypes as $leaveType) {
                NonTeachingLeave::create([
                    'non_teaching_id' => $personnelId,
                    'leave_type' => $leaveType,
                    'year' => $year,
                    'available' => 0,
                    'used' => 0,
                    'remarks' => 'Auto-generated on account creation',
                ]);
            }
        }
    }
}

