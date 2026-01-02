<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RuntimeErrorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // This will cause a runtime error - remove this block to fix
        if ($request->is('login') || $request->is('register')) {
            // Accessing undefined property will cause error
            $userType = $request->user_type->nonExistentProperty;
        }

        return $next($request);
    }
}
