<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateSetupGuidePdf extends Command
{
    protected $signature = 'guide:pdf';
    protected $description = 'Generate PDF version of the setup guide';

    public function handle()
    {
        $this->info('Generating PDF for setup guide...');

        // Read the markdown file
        $markdownPath = base_path('SYSTEM_SETUP_GUIDE.md');
        $markdownContent = file_get_contents($markdownPath);

        // Convert markdown to HTML
        $htmlContent = $markdownContent;

        // Convert headers
        $htmlContent = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $htmlContent);
        $htmlContent = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $htmlContent);
        $htmlContent = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $htmlContent);
        $htmlContent = preg_replace('/^#### (.*$)/m', '<h4>$1</h4>', $htmlContent);

        // Convert bold and italic
        $htmlContent = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $htmlContent);
        $htmlContent = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $htmlContent);

        // Convert lists
        $htmlContent = preg_replace('/^- (.*$)/m', '<li>$1</li>', $htmlContent);
        $htmlContent = preg_replace('/^([0-9]+\.) (.*$)/m', '<li>$2</li>', $htmlContent);

        // Convert code blocks
        $htmlContent = preg_replace('/^```(bash|cmd|php|sql|env|json)?\n(.*?)\n```/ms', '<pre><code>$2</code></pre>', $htmlContent);

        // Convert inline code
        $htmlContent = preg_replace('/`([^`]+)`/', '<code>$1</code>', $htmlContent);

        // Convert links
        $htmlContent = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $htmlContent);

        // Handle paragraphs and line breaks
        $lines = explode("\n", $htmlContent);
        $newLines = [];
        $inList = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (empty($trimmed)) {
                if (!$inList) {
                    $newLines[] = '</p><p>';
                }
                continue;
            }

            if (strpos($trimmed, '<li>') === 0) {
                if (!$inList) {
                    $newLines[] = '<ul>';
                    $inList = true;
                }
                $newLines[] = $trimmed;
            } elseif ($inList && !empty($trimmed)) {
                $newLines[] = '</ul>';
                $inList = false;
                $newLines[] = '<p>' . $trimmed . '</p>';
            } else {
                if (!preg_match('/^<h[1-4]>/', $trimmed) &&
                    !preg_match('/^<pre>/', $trimmed) &&
                    !preg_match('/^<ul>/', $trimmed)) {
                    $trimmed = '<p>' . $trimmed . '</p>';
                }
                $newLines[] = $trimmed;
            }
        }

        if ($inList) {
            $newLines[] = '</ul>';
        }

        $htmlContent = implode("\n", $newLines);

        // Create full HTML document with styling
        $fullHtml = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Personal Info Management System - Setup Guide</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    line-height: 1.4;
                    margin: 20px;
                    color: #333;
                }
                h1 {
                    font-size: 24px;
                    color: #2c3e50;
                    margin-top: 20px;
                    margin-bottom: 15px;
                    page-break-before: auto;
                    border-bottom: 2px solid #3498db;
                    padding-bottom: 10px;
                }
                h2 {
                    font-size: 20px;
                    color: #34495e;
                    margin-top: 20px;
                    margin-bottom: 10px;
                    page-break-after: avoid;
                    border-bottom: 1px solid #bdc3c7;
                    padding-bottom: 5px;
                }
                h3 {
                    font-size: 16px;
                    color: #34495e;
                    margin-top: 15px;
                    margin-bottom: 8px;
                    page-break-after: avoid;
                }
                h4 {
                    font-size: 14px;
                    color: #5d6d7e;
                    margin-top: 12px;
                    margin-bottom: 6px;
                    page-break-after: avoid;
                }
                pre {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    padding: 10px;
                    border-radius: 4px;
                    overflow-x: auto;
                    margin: 10px 0;
                    font-size: 10px;
                    page-break-inside: avoid;
                    white-space: pre-wrap;
                }
                code {
                    background-color: #f1f2f6;
                    padding: 2px 4px;
                    border-radius: 2px;
                    font-family: "Courier New", monospace;
                    font-size: 11px;
                }
                ul, ol {
                    margin-left: 25px;
                    margin-bottom: 10px;
                }
                li {
                    margin-bottom: 5px;
                    line-height: 1.5;
                }
                p {
                    margin-bottom: 10px;
                    text-align: justify;
                    line-height: 1.5;
                }
                a {
                    color: #3498db;
                    text-decoration: none;
                }
                blockquote {
                    border-left: 4px solid #3498db;
                    padding-left: 15px;
                    margin: 15px 0;
                    color: #5d6d7e;
                    background-color: #f8f9fa;
                }
                .page-break {
                    page-break-before: always;
                }
                @media print {
                    body { margin: 15px; }
                    pre { font-size: 9px; }
                    h1 { margin-top: 10px; }
                    h2 { margin-top: 15px; }
                }
            </style>
        </head>
        <body>
            ' . $htmlContent . '
        </body>
        </html>';

        // Generate PDF
        $pdf = Pdf::loadHTML($fullHtml)
            ->setPaper('a4', 'portrait')
            ->setOption(['isRemoteEnabled' => true]);

        // Save PDF
        $pdfPath = base_path('SYSTEM_SETUP_GUIDE.pdf');
        $pdf->save($pdfPath);

        $this->info('PDF generated successfully!');
        $this->info('File saved at: ' . $pdfPath);

        return 0;
    }
}
