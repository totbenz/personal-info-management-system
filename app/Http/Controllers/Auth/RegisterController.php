<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'personnel_id' => 'required',
                'email' => 'required|email|max:250|unique:users',
                'password' => 'required|min:8|confirmed'
            ]);

            try {
                $personnel = Personnel::where('personnel_id', $request->personnel_id)->firstOrFail();

                $user = $personnel->user()->create([
                    'personnel_id' => $personnel->id,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $personnel->job_title === 'School Head' ? 'school_head' : 'teacher'
                ]);

                if ($user) {
                    $credentials = $request->only('email', 'password');
                    Auth::attempt($credentials);
                    $request->session()->regenerate();

                    session()->flash('flash.banner', 'You have successfully registered & logged in!');
                    session()->flash('flash.bannerStyle', 'success');

                    return redirect('/');
                }
            } catch (\Throwable $th) {
                dd($th);
            }
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors());
        }
        return redirect()->back();
    }
}
