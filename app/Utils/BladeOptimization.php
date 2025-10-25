<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class BladeOptimization
{
    /**
     * Optimize Blade view data to prevent timeout errors
     */
    public static function optimizeViewData(array $data, int $maxRecords = 100): array
    {
        $optimized = [];

        foreach ($data as $key => $value) {
            if (is_object($value) && method_exists($value, 'count')) {
                // If it's a collection or query builder with many records
                if ($value->count() > $maxRecords) {
                    Log::warning("BladeOptimization: Limiting {$key} from {$value->count()} to {$maxRecords} records");

                    if (method_exists($value, 'limit')) {
                        $optimized[$key] = $value->limit($maxRecords)->get();
                    } else {
                        $optimized[$key] = $value->take($maxRecords);
                    }
                } else {
                    $optimized[$key] = $value;
                }
            } else {
                $optimized[$key] = $value;
            }
        }

        return $optimized;
    }

    /**
     * Check if a collection is too large for safe rendering
     */
    public static function isCollectionTooLarge($collection, int $threshold = 1000): bool
    {
        if (is_object($collection) && method_exists($collection, 'count')) {
            return $collection->count() > $threshold;
        }

        if (is_array($collection)) {
            return count($collection) > $threshold;
        }

        return false;
    }

    /**
     * Get safe collection size for rendering
     */
    public static function getSafeCollectionSize($collection, int $maxSize = 100): int
    {
        if (is_object($collection) && method_exists($collection, 'count')) {
            return min($collection->count(), $maxSize);
        }

        if (is_array($collection)) {
            return min(count($collection), $maxSize);
        }

        return 0;
    }

    /**
     * Log view performance metrics
     */
    public static function logViewPerformance(string $viewName, array $data): void
    {
        $metrics = [
            'view' => $viewName,
            'data_keys' => array_keys($data),
            'memory_usage' => memory_get_usage(true),
            'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
        ];

        // Count collections
        foreach ($data as $key => $value) {
            if (is_object($value) && method_exists($value, 'count')) {
                $metrics["{$key}_count"] = $value->count();
            } elseif (is_array($value)) {
                $metrics["{$key}_count"] = count($value);
            }
        }

        Log::info('BladeOptimization: View performance metrics', $metrics);
    }
}
