{{-- resources/views/nosa.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notice of Salary Adjustment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @page {
            size: legal;
            margin: 0.5in 0.5in 0 0.5in;
        }
        body {
            margin: 0;
            padding: 0;
            min-height: 14in; 
        }
    </style>
</head>
<body class="text-gray-900">
    <div class="text-center mb-6 ">
        <img src="{{ public_path('image/kagawaran-ng-edukasyon-logo.png') }}" alt="deped Logo" style="width: 80px; height: auto; margin-bottom: 10px;">
        <h2 class="font-bold text-lg">Republic of the Philippines</h2>
        <h3 class="font-semibold text-md">Department of Education</h3>
        <p>REGION VIII</p>
        <p class="text-sm">SCHOOLS DIVISION OF BAYBAY CITY, LEYTE</p>
        <hr class="border-gray-300 mt-3 border-t-2">
        <p class="mt-2 text-left text-xs">Office of the Schools Division Superintendent</p>
    </div>

    <div class="text-center mb-6">
        <h1 class="font-bold text-xl uppercase">Notice of Salary Adjustment (NOSA)</h1>
        <div>
            <p class="mt-7 mr-5 text-right">October 24, 2024</p>
            <p class="mr-16 text-right">Date</p>
        </div>
    </div>

    <div class="mb-6">
        <p><strong>Jason Rey Manlangit</strong></p>
        <p>Full Stack Developer</p>
        <p>VSU lang malakas</p>
    </div>

    <div class="mb-6">
        <p class="font-bold">Sir/Madame:</p>
        <p class="mt-4 text-justify">
           <span class="ml-14">Pursuant</span> to National Budget Circular No. 594 dated August 12, 2024, implementing Executive Order No. 64 dated August 2, 2024, your salary is hereby adjusted effective <strong class="underline">January 24, 2024</strong>, as follows:
        </p>
    </div>

    <div class="mb-6">
        <table class="table-auto w-full">
            <tbody>
                <tr class="column-1">
                    <td class="px-4 py-2" style="width: 55%;">1. Adjusted monthly basic salary effective under the New Salary Schedule:
                        <br><span>SG <strong  class="mr-10">19</strong></span>
                        <span>Step <strong>1</strong></span>
                    </td>
                    <td class="px-4 py-2 text-center" style="width: 25%;"><strong class="underline">January 24, 2024</strong></td>
                    <td class="px-4 py-2" style="width: 5%;">P</td>
                    <td class="px-4 py-2 text-right" style="width: 15%; text-decoration: underline;">53,873.00</td>
                </tr>
                <tr>
                    <td class="px-4 py-2" style="width: 40%;">2. Actual monthly basic salary as of
                        <br><span>SG <strong  class="mr-10">18</strong></span>
                        <span>Step <strong>1</strong></span>
                    </td>
                    <td class="px-4 py-2 text-center" style="width: 25%;"><strong class="underline ">January 23, 2024</strong></td>
                    <td class="px-4 py-2" style="width: 5%;">P</td>
                    <td class="px-4 py-2 text-right" style="width: 15%; text-decoration: underline;">49,015.00</td>
                </tr>
                <tr>
                    <td class="px-4 py-2" style="width: 40%;">3. Monthly salary adjustment effective </td>
                    <td class="px-4 py-2 text-center" style="width: 25%;"><strong class="underline ">January 24, 2024</strong></td>
                    <td class="px-4 py-2" style="width: 5%;">P</td>
                    <td class="px-4 py-2 text-right" style="width: 15%; text-decoration: underline;">4,858.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mb-6 text-justify">
        <p>
            <span class="ml-14">It</span> is understood that this salary adjustment is subject to review and post-audit, and to appropriate re-adjustment and refund if found not in order.
        </p>
    </div>

    <div class="mt-10 text-right">
        <p class="text-center">Very truly yours,</p>
        <div class="mt-10 text-center" style="margin-left: 40%;">
            <p class="font-bold underline">MANUEL T. ALBARO, Ph.D., CESO V</p>
            <p>Schools Division Superintendent</p>
        </div>
    </div>

    <div class="mt-20">
        <p class="text-sm">Position: Master Teacher II</p>
        <p class="text-sm">Salary Grade: 19</p>
        <p class="text-sm">Item No./Unique Item No. Fy 2024 Personnel  Services Itemization</p>
        <p class="text-sm">and/or Plantilla of Personnel:  <span class="ml-14 uppercase underline">OSEC-decsbmtchr2-540459-1998</span></p>
    </div>
    <hr class="border-gray-300 mt-3 border-t-2 mb-2">

    <!-- Logo Section -->
    <div style="width: 35%; float: left;">
        <img src="{{ public_path('image/deped-matatag.png') }}" alt="DepEd Matatag Logo" style="height: 70px;">
        <img src="{{ public_path('image/division-logo.png') }}" alt="Division Logo" style="height: 62px;">
    </div>

    <!-- Contact Information -->
    <div style="width: 65%; float: right; font-size: 13px; line-height: 1.5; color: #1f2937;">
        <p><strong>Address:</strong> Diversion Road, Barangay Gaas, Baybay City, Leyte</p>
        <p><strong>Telephone #:</strong> (53) 563-7615</p>
        <p><strong>Email Address:</strong> baybaycity@deped.gov.ph</p>
    </div>
</body>
</html>
