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

                // Return JSON response with success message for AJAX requests
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful! Welcome back.',
                        'redirect' => $this->getRedirectUrl($user)
                    ]);
                }

                // For regular requests, redirect to a temporary success page
                session()->flash('success_message', 'Login successful! Welcome back.');
                session()->flash('redirect_url', $this->getRedirectUrl($user));
                session()->flash('show_delayed_redirect', true);

                return redirect()->route('login.success');
            } else {
                $account = User::where('email', $request->email)->first();
                $errorMessage = 'Authentication failed';

                if (!$account) {
                    $errorMessage = 'Account doesn\'t exist';
                } elseif (!password_verify($request->password, $account->password)) {
                    $errorMessage = 'Password is incorrect';
                }

                // Return JSON response with error message for AJAX requests
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }

                // For regular requests, redirect back with error message
                session()->flash('error_message', $errorMessage);
            }
        } catch (\Exception $e) {
            $errorMessage = 'Authentication error occurred';

            // Return JSON response with error message for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            // For regular requests, redirect back with error message
            session()->flash('error_message', $errorMessage);
        }

        return redirect()->back();
    }

    /**
     * Get the appropriate redirect URL based on user role
     */
    private function getRedirectUrl($user)
    {
        if ($user->role == 'admin') {
            return route('admin.home');
        } elseif ($user->role == 'school_head') {
            return route('school_head.dashboard');
        } elseif ($user->role == 'teacher') {
            return route('personnel.profile');
        }

        return '/dashboard'; // Default fallback
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();

        // Show success message for logout
        session()->flash('success_message', 'You have been successfully logged out.');

        return redirect('/login');
    }
}
