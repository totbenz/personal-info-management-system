<?php

require_once __DIR__ . '/vendor/autoload.php';

use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\ServiceProvider as DomPDFServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use Illuminate\View\ViewServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;

// Bootstrap Laravel's container for PDF generation
$container = new Container();
$container->singleton('events', function () {
    return new Dispatcher();
});
$container->singleton('files', function () {
    return new Filesystem();
});
$container->singleton('config', function () {
    return [
        'view' => [
            'paths' => [__DIR__ . '/resources/views'],
            'compiled' => __DIR__ . '/storage/framework/views'
        ],
        'dompdf' => [
            'orientation' => 'portrait',
            'defaultFont' => 'sans-serif',
            'paperSize' => 'a4'
        ]
    ];
});

// Register service providers
$provider = new ViewServiceProvider($container);
$provider->register();
$provider = new DomPDFServiceProvider($container);
$provider->register();

// Set up the view factory
$viewFactory = $container->make('view');

// Read the markdown content
$markdownContent = file_get_contents(__DIR__ . '/SYSTEM_SETUP_GUIDE.md');

// Convert markdown to HTML
$htmlContent = $markdownContent;
$htmlContent = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $htmlContent);
$htmlContent = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $htmlContent);
$htmlContent = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $htmlContent);
$htmlContent = preg_replace('/^#### (.*$)/m', '<h4>$1</h4>', $htmlContent);
$htmlContent = preg_replace('/^\*\*(.*)\*\*/m', '<strong>$1</strong>', $htmlContent);
$htmlContent = preg_replace('/^\*(.*)\*/m', '<em>$1</em>', $htmlContent);
$htmlContent = preg_replace('/^- (.*$)/m', '<li>$1</li>', $htmlContent);
$htmlContent = preg_replace('/^([0-9]+\.) (.*$)/m', '<li>$2</li>', $htmlContent);
$htmlContent = preg_replace('/^```(bash|cmd|php|sql|env|json)?\n(.*?)\n```/ms', '<pre><code>$2</code></pre>', $htmlContent);
$htmlContent = preg_replace('/`([^`]+)`/', '<code>$1</code>', $htmlContent);
$htmlContent = preg_replace('/\n\n/', '</p><p>', $htmlContent);
$htmlContent = '<html><head><style>
    body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
    h1 { font-size: 24px; color: #333; margin-top: 20px; margin-bottom: 10px; }
    h2 { font-size: 20px; color: #444; margin-top: 15px; margin-bottom: 8px; }
    h3 { font-size: 16px; color: #555; margin-top: 12px; margin-bottom: 6px; }
    h4 { font-size: 14px; color: #666; margin-top: 10px; margin-bottom: 5px; }
    pre { background-color: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; margin: 10px 0; }
    code { background-color: #f0f0f0; padding: 2px 4px; border-radius: 2px; font-family: monospace; }
    ul, ol { margin-left: 20px; margin-bottom: 10px; }
    li { margin-bottom: 5px; }
    p { margin-bottom: 10px; }
    a { color: #0066cc; }
    blockquote { border-left: 4px solid #ddd; padding-left: 10px; margin: 10px 0; color: #666; }
</style></head><body>' . $htmlContent . '</body></html>';

// Create PDF
$pdf = $container->make('dompdf.wrapper');
$pdf->loadHTML($htmlContent);

// Save PDF
$pdf->save(__DIR__ . '/SYSTEM_SETUP_GUIDE.pdf');

echo "PDF generated successfully: SYSTEM_SETUP_GUIDE.pdf\n";
