<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userRole)
    {
        $user = auth()->user();

        if ($user->role == $userRole) {
            return $next($request);
        }

        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.home');
            case 'school_head':
                return redirect()->route('schools.profile', ['school' => $user->personnel->school]);
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            default:
                session()->flash('flash.banner', 'You do not have permission to access this page.');
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->back();
        }
    }
}
