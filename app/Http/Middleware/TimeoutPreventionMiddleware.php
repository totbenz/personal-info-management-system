<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimeoutPreventionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Set appropriate timeout and memory limits based on route
        $this->setResourceLimits($request);

        // Log the request for monitoring
        $this->logRequest($request);

        try {
            $response = $next($request);

            // Log successful completion
            $this->logCompletion($request, $response);

            return $response;
        } catch (\Exception $e) {
            // Log timeout or other errors
            $this->logError($request, $e);

            // If it's a timeout error, return a proper response
            if (strpos($e->getMessage(), 'Maximum execution time') !== false) {
                return response()->json([
                    'error' => 'Request timeout',
                    'message' => 'The operation took too long to complete. Please try again with smaller data sets or contact support.',
                    'code' => 408
                ], 408);
            }

            throw $e;
        }
    }

    /**
     * Set appropriate resource limits based on the request
     */
    private function setResourceLimits(Request $request)
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : '';
        $path = $request->path();

        // Debug routes - moderate limits
        if (strpos($path, 'debug') !== false) {
            set_time_limit(60);
            ini_set('memory_limit', '256M');
            return;
        }

        // CSV export routes - high limits
        if (strpos($path, 'csv-export') !== false || strpos($path, 'export') !== false) {
            set_time_limit(300); // 5 minutes
            ini_set('memory_limit', '1024M'); // 1GB
            return;
        }

        // Database operations - high limits
        if (strpos($path, 'import') !== false || strpos($path, 'recovery') !== false) {
            set_time_limit(600); // 10 minutes
            ini_set('memory_limit', '1024M'); // 1GB
            return;
        }

        // PDF generation routes - moderate limits
        if (strpos($path, 'pdf') !== false || strpos($path, 'download') !== false) {
            set_time_limit(120); // 2 minutes
            ini_set('memory_limit', '512M'); // 512MB
            return;
        }

        // Personnel profile routes - moderate limits
        if (strpos($path, 'personnels') !== false || strpos($path, 'profile') !== false) {
            set_time_limit(90); // 1.5 minutes
            ini_set('memory_limit', '256M');
            return;
        }

        // Admin dashboard routes - moderate limits
        if (strpos($path, 'admin') !== false) {
            set_time_limit(90); // 1.5 minutes
            ini_set('memory_limit', '256M');
            return;
        }

        // Default limits for other routes
        set_time_limit(60); // 1 minute (increased from 30 seconds)
        ini_set('memory_limit', '256M'); // 256MB (increased from 128MB)
    }

    /**
     * Log the request for monitoring
     */
    private function logRequest(Request $request)
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : 'unknown';

        Log::info('TimeoutPrevention: Request started', [
            'route' => $routeName,
            'path' => $request->path(),
            'method' => $request->method(),
            'memory_limit' => ini_get('memory_limit'),
            'time_limit' => ini_get('max_execution_time'),
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Log successful completion
     */
    private function logCompletion(Request $request, $response)
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : 'unknown';

        Log::info('TimeoutPrevention: Request completed', [
            'route' => $routeName,
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
            'memory_used' => memory_get_peak_usage(true),
            'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
        ]);
    }

    /**
     * Log errors including timeouts
     */
    private function logError(Request $request, \Exception $e)
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : 'unknown';

        Log::error('TimeoutPrevention: Request failed', [
            'route' => $routeName,
            'path' => $request->path(),
            'error' => $e->getMessage(),
            'memory_used' => memory_get_peak_usage(true),
            'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            'trace' => $e->getTraceAsString()
        ]);
    }
}
