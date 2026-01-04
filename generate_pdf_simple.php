<?php

// Simple PDF generator using DomPDF directly
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Read the markdown content
$markdownContent = file_get_contents(__DIR__ . '/SYSTEM_SETUP_GUIDE.md');

// Convert markdown to HTML
$htmlContent = $markdownContent;

// Headers
$htmlContent = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $htmlContent);
$htmlContent = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $htmlContent);
$htmlContent = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $htmlContent);
$htmlContent = preg_replace('/^#### (.*$)/m', '<h4>$1</h4>', $htmlContent);

// Bold and italic
$htmlContent = preg_replace('/^\*\*(.*)\*\*/m', '<strong>$1</strong>', $htmlContent);
$htmlContent = preg_replace('/^\*(.*)\*/m', '<em>$1</em>', $htmlContent);

// Lists
$htmlContent = preg_replace('/^- (.*$)/m', '<li>$1</li>', $htmlContent);
$htmlContent = preg_replace('/^([0-9]+\.) (.*$)/m', '<li>$2</li>', $htmlContent);

// Code blocks
$htmlContent = preg_replace('/^```(bash|cmd|php|sql|env|json)?\n(.*?)\n```/ms', '<pre><code>$2</code></pre>', $htmlContent);

// Inline code
$htmlContent = preg_replace('/`([^`]+)`/', '<code>$1</code>', $htmlContent);

// Paragraphs
$htmlContent = preg_replace('/\n\n/', '</p><p>', $htmlContent);

// Wrap lists properly
$htmlContent = preg_replace('/(<li>.*?<\/li>)/s', '<ul>$1</ul>', $htmlContent);

// Create full HTML document
$fullHtml = '<html><head><style>
    body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 20px; }
    h1 { font-size: 24px; color: #333; margin-top: 20px; margin-bottom: 10px; page-break-before: auto; }
    h2 { font-size: 20px; color: #444; margin-top: 15px; margin-bottom: 8px; page-break-after: avoid; }
    h3 { font-size: 16px; color: #555; margin-top: 12px; margin-bottom: 6px; page-break-after: avoid; }
    h4 { font-size: 14px; color: #666; margin-top: 10px; margin-bottom: 5px; }
    pre { background-color: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; margin: 10px 0; font-size: 10px; page-break-inside: avoid; }
    code { background-color: #f0f0f0; padding: 2px 4px; border-radius: 2px; font-family: monospace; font-size: 11px; }
    ul, ol { margin-left: 20px; margin-bottom: 10px; }
    li { margin-bottom: 5px; }
    p { margin-bottom: 10px; text-align: justify; }
    a { color: #0066cc; text-decoration: none; }
    blockquote { border-left: 4px solid #ddd; padding-left: 10px; margin: 10px 0; color: #666; }
    .page-break { page-break-before: always; }
    @media print {
        body { margin: 15px; }
        pre { font-size: 9px; }
    }
</style></head><body>' . $htmlContent . '</body></html>';

// Configure DomPDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('paperSize', 'a4');
$options->set('paperOrientation', 'portrait');

// Create PDF
$dompdf = new Dompdf($options);
$dompdf->loadHtml($fullHtml);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save PDF
$output = $dompdf->output();
file_put_contents(__DIR__ . '/SYSTEM_SETUP_GUIDE.pdf', $output);

echo "PDF generated successfully: SYSTEM_SETUP_GUIDE.pdf\n";
echo "File saved at: " . __DIR__ . '/SYSTEM_SETUP_GUIDE.pdf' . "\n";
