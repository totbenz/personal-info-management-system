<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class TimeoutPrevention
{
    /**
     * Set appropriate timeout and memory limits based on operation type
     */
    public static function setLimits(string $operationType = 'default'): void
    {
        $config = Config::get('timeout_prevention.operations', []);
        $defaults = Config::get('timeout_prevention.defaults', []);

        $settings = $config[$operationType] ?? $defaults;

        $timeout = $settings['timeout'] ?? 30;
        $memoryLimit = $settings['memory_limit'] ?? '128M';

        set_time_limit($timeout);
        ini_set('memory_limit', $memoryLimit);

        Log::info("TimeoutPrevention: Set limits for {$operationType}", [
            'timeout' => $timeout,
            'memory_limit' => $memoryLimit
        ]);
    }

    /**
     * Set limits based on route name
     */
    public static function setLimitsForRoute(string $routeName): void
    {
        $routeConfig = Config::get('timeout_prevention.routes', []);

        foreach ($routeConfig as $pattern => $operationType) {
            if (fnmatch($pattern, $routeName)) {
                self::setLimits($operationType);
                return;
            }
        }

        // Default limits if no pattern matches
        self::setLimits('default');
    }

    /**
     * Execute a callback with timeout protection
     */
    public static function executeWithProtection(callable $callback, string $operationType = 'default')
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        try {
            self::setLimits($operationType);
            $result = $callback();

            $executionTime = microtime(true) - $startTime;
            $memoryUsed = memory_get_usage(true) - $startMemory;

            Log::info("TimeoutPrevention: Operation completed successfully", [
                'operation' => $operationType,
                'execution_time' => round($executionTime, 2),
                'memory_used' => self::formatBytes($memoryUsed)
            ]);

            return $result;

        } catch (\Exception $e) {
            $executionTime = microtime(true) - $startTime;
            $memoryUsed = memory_get_usage(true) - $startMemory;

            Log::error("TimeoutPrevention: Operation failed", [
                'operation' => $operationType,
                'execution_time' => round($executionTime, 2),
                'memory_used' => self::formatBytes($memoryUsed),
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Check if current execution is approaching timeout
     */
    public static function isApproachingTimeout(int $bufferSeconds = 5): bool
    {
        $maxExecutionTime = ini_get('max_execution_time');

        if ($maxExecutionTime == 0) {
            return false; // No time limit
        }

        $elapsedTime = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
        $remainingTime = $maxExecutionTime - $elapsedTime;

        return $remainingTime <= $bufferSeconds;
    }

    /**
     * Get current memory usage percentage
     */
    public static function getMemoryUsagePercentage(): float
    {
        $currentMemory = memory_get_usage(true);
        $memoryLimit = self::parseMemoryLimit(ini_get('memory_limit'));

        if ($memoryLimit == 0) {
            return 0;
        }

        return ($currentMemory / $memoryLimit) * 100;
    }

    /**
     * Check if memory usage is high
     */
    public static function isHighMemoryUsage(float $threshold = 80.0): bool
    {
        return self::getMemoryUsagePercentage() >= $threshold;
    }

    /**
     * Process large datasets in chunks to prevent timeout
     */
    public static function processInChunks($query, callable $processor, int $chunkSize = null): void
    {
        $chunkSize = $chunkSize ?? Config::get('timeout_prevention.chunking.default_chunk_size', 1000);

        $query->chunk($chunkSize, function ($chunk) use ($processor) {
            // Check if we're approaching timeout before processing each chunk
            if (self::isApproachingTimeout()) {
                Log::warning('TimeoutPrevention: Approaching timeout, stopping chunk processing');
                return false; // Stop processing
            }

            $processor($chunk);
        });
    }

    /**
     * Format bytes to human readable format
     */
    private static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Parse memory limit string to bytes
     */
    private static function parseMemoryLimit(string $memoryLimit): int
    {
        $memoryLimit = trim($memoryLimit);
        $last = strtolower($memoryLimit[strlen($memoryLimit) - 1]);
        $memoryLimit = (int) $memoryLimit;

        switch ($last) {
            case 'g':
                $memoryLimit *= 1024;
            case 'm':
                $memoryLimit *= 1024;
            case 'k':
                $memoryLimit *= 1024;
        }

        return $memoryLimit;
    }
}
