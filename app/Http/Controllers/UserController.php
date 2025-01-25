<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
                'role' => 'required|in:teacher,school_head,admin'
            ]);

            $personnel = Personnel::where('personnel_id', $request->personnel_id)->firstOrFail();

            User::create([
                'personnel_id' => $personnel->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

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

            $user = User::findOrFail($id);
            $personnel = Personnel::where('personnel_id', $request->personnel_id)->firstOrFail();

            $user->update([
                'personnel_id' => $personnel->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            $message = 'User Data Updated Successfully';
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
}

