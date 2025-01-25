<?php
// Cannot declare class App\Http\Controllers\LoginController, because the name is already in use

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $redirectAfterLogout = '/';

    function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = auth()->user();

                if ($user->role == 'admin') {
                    return redirect()->route('admin.home');
                } elseif ($user->role == 'school_head') {
                    return redirect()->route('schools.profile', ['school' => auth()->user()->personnel->school->id]);
                } elseif ($user->role == 'teacher') {
                    return redirect()->route('personnel.profile');
                }
            } else {
                $account = User::where('email', $request->email)->first();
                if (!$account) {
                    $flashMessage = ['banner' => 'Account Doesn\'t Exist', 'bannerStyle' => 'danger'];
                } elseif (!password_verify($request->password, $account->password)) {
                    $flashMessage = ['banner' => 'Password Incorrect', 'bannerStyle' => 'danger'];
                } else {
                    $flashMessage = ['banner' => 'Authentication Error', 'bannerStyle' => 'danger'];
                }

                session()->flash('flash', $flashMessage);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Authentication Error!');
        }

        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect('/login');
    }
}
