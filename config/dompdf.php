<?php

return [

    'show_warnings' => false,
    'public_path' => null,
    'convert_entities' => true,

    'options' => [

        // Font directories
        'font_dir' => resource_path('fonts'),
        'font_cache' => storage_path('fonts'),

        // ✅ Custom Font Setup
        'custom_font_dir' => resource_path('fonts'),
        'custom_font_data' => [
            'oldenglish' => [
                'R' => 'old-english-text-mt.ttf', // Regular
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ],
        'default_font' => 'serif', // Change to 'oldenglish' to make it default

        // Required paths
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),

        // Protocols allowed for assets
        'allowed_protocols' => [
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        'log_output_file' => null,
        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',

        // Document defaults
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',
        'dpi' => 96,

        // Feature toggles
        'enable_php' => false,
        'enable_javascript' => true,
        'enable_remote' => true,
        'font_height_ratio' => 1.1,

        'enable_html5_parser' => true,
    ],
];
