<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{

    function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => "teacher"
            ]);

            if ($user) {
                $credentials = $request->only('email', 'password');
                Auth::attempt($credentials);
                $request->session()->regenerate();

                session()->flash('flash.banner', 'You have successfully registered & logged in!');
                session()->flash('flash.bannerStyle', 'success');

                return redirect('/');
            }
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors());
        }
        return redirect()->back();
    }

    public function authenticate(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);

            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)){
                return redirect()->intended(route('admin.home'));
            } else {
                return redirect()->back()->withErrors(['message' => 'Invalid credentials']);
            }
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors());
        }
    }
}
