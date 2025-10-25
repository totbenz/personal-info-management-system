<?php

/**
 * Timeout Prevention Configuration
 *
 * This file contains timeout prevention settings and utilities
 * to prevent "Maximum execution time exceeded" errors.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Timeout Settings
    |--------------------------------------------------------------------------
    |
    | These are the default timeout and memory limits for different types
    | of operations in the application.
    |
    */

    'defaults' => [
        'timeout' => 30, // seconds
        'memory_limit' => '128M',
    ],

    /*
    |--------------------------------------------------------------------------
    | Operation-Specific Timeout Settings
    |--------------------------------------------------------------------------
    |
    | Timeout and memory limits for specific types of operations.
    |
    */

    'operations' => [
        'debug' => [
            'timeout' => 60,
            'memory_limit' => '256M',
        ],

        'csv_export' => [
            'timeout' => 300, // 5 minutes
            'memory_limit' => '1024M', // 1GB
        ],

        'csv_import' => [
            'timeout' => 600, // 10 minutes
            'memory_limit' => '1024M', // 1GB
        ],

        'database_operations' => [
            'timeout' => 120,
            'memory_limit' => '512M',
        ],

        'pdf_generation' => [
            'timeout' => 120, // 2 minutes
            'memory_limit' => '512M',
        ],

        'zip_creation' => [
            'timeout' => 180, // 3 minutes
            'memory_limit' => '512M',
        ],

        'recovery' => [
            'timeout' => 600, // 10 minutes
            'memory_limit' => '1024M',
        ],

        'admin_dashboard' => [
            'timeout' => 90, // 1.5 minutes
            'memory_limit' => '256M',
        ],

        'bulk_delete' => [
            'timeout' => 120,
            'memory_limit' => '256M',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route-Specific Timeout Settings
    |--------------------------------------------------------------------------
    |
    | Timeout settings for specific routes or route patterns.
    |
    */

    'routes' => [
        'debug.*' => 'debug',
        'csv-export.*' => 'csv_export',
        'import.*' => 'csv_import',
        'recovery.*' => 'recovery',
        'admin.*' => 'admin_dashboard',
        'download.*' => 'pdf_generation',
        'export.*' => 'pdf_generation',
    ],

    /*
    |--------------------------------------------------------------------------
    | Chunking Settings
    |--------------------------------------------------------------------------
    |
    | Settings for chunking large operations to prevent timeouts.
    |
    */

    'chunking' => [
        'default_chunk_size' => 1000,
        'large_table_threshold' => 10000,
        'bulk_delete_chunk_size' => 500,
        'csv_export_chunk_size' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring Settings
    |--------------------------------------------------------------------------
    |
    | Settings for monitoring and logging timeout-related issues.
    |
    */

    'monitoring' => [
        'log_timeouts' => true,
        'log_slow_queries' => true,
        'slow_query_threshold' => 5, // seconds
        'memory_warning_threshold' => '512M',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    |
    | Settings for handling timeout errors gracefully.
    |
    */

    'error_handling' => [
        'return_json_on_timeout' => true,
        'timeout_message' => 'The operation took too long to complete. Please try again with smaller data sets.',
        'log_timeout_details' => true,
    ],
];
