<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the automated backup system.
    | Configure backup types, cloud storage, and retention policies here.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Backup Types
    |--------------------------------------------------------------------------
    |
    | Available backup types and their descriptions:
    | - full: Complete database backup (structure + data)
    | - structure: Database structure only (tables, indexes, etc.)
    | - data: Data only (no structure)
    |
    */
    'types' => [
        'full' => 'Complete database backup',
        'structure' => 'Database structure only',
        'data' => 'Data only',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Backup Settings
    |--------------------------------------------------------------------------
    |
    | Default options for backup creation
    |
    */
    'defaults' => [
        'type' => 'full',
        'compress' => true,
        'retention_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloud Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configure cloud storage for backup files. Set enabled to true and
    | specify the disk to use for cloud storage.
    |
    */
    'cloud' => [
        'enabled' => env('BACKUP_CLOUD_ENABLED', false),
        'disk' => env('BACKUP_CLOUD_DISK', 's3'),
        'path' => env('BACKUP_CLOUD_PATH', 'backups'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention Policy
    |--------------------------------------------------------------------------
    |
    | How long to keep backup files before automatic cleanup
    |
    */
    'retention' => [
        'days' => env('BACKUP_RETENTION_DAYS', 30),
        'keep_minimum' => env('BACKUP_KEEP_MINIMUM', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Schedule
    |--------------------------------------------------------------------------
    |
    | Configure when automatic backups should run
    |
    */
    'schedule' => [
        'enabled' => env('BACKUP_SCHEDULE_ENABLED', true),
        'frequency' => env('BACKUP_SCHEDULE_FREQUENCY', 'daily'),
        'time' => env('BACKUP_SCHEDULE_TIME', '02:00'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure notifications for backup success/failure
    |
    */
    'notifications' => [
        'enabled' => env('BACKUP_NOTIFICATIONS_ENABLED', false),
        'email' => env('BACKUP_NOTIFICATION_EMAIL'),
        'slack_webhook' => env('BACKUP_SLACK_WEBHOOK'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Emergency Recovery Settings
    |--------------------------------------------------------------------------
    |
    | Settings for emergency recovery procedures
    |
    */
    'emergency' => [
        'admin_email' => env('EMERGENCY_ADMIN_EMAIL'),
        'admin_phone' => env('EMERGENCY_ADMIN_PHONE'),
        'recovery_instructions' => storage_path('docs/recovery-instructions.md'),
    ],
];
