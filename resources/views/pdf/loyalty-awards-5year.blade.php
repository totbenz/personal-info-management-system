<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5 Years Milestone Loyalty Award Recipients Report</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&display=swap" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&display=swap');

        @page {
            size: A4;
            margin: 0.25in 0.5in 0.75in 0.5in;
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 16px;
            font-family: Times New Roman, serif;
        }

        .unifraktur-font {
            font-family: 'UnifrakturCook' !important;
            font-weight: 700;
        }

        .page-container {
            min-height: calc(100vh - 1in);
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }

        .footer {
            margin-top: auto;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        @media print {
            .page-container {
                min-height: calc(11in - 1in);
            }

            .footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                background: #fff;
                padding: 0 0.5in 0.25in 0.5in;
            }

            body {
                padding-bottom: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <div class="text-center mb-6 ">
                <img src="{{ public_path('image/kagawaran-ng-edukasyon-logo.png') }}" alt="deped Logo" style="width: 80px; height: auto; margin-bottom: 10px;">
                <h2 class="unifraktur-font">Republic of the Philippines</h2>
                <h1 class="font-semibold unifraktur-font" style="font-size: 24px; margin-top:-20px; letter-spacing:1px;">Department of Education</h1>
                <p style="font-size: 13px;">REGION VIII</p>
                <p style="font-size: 13px;">SCHOOLS DIVISION OF BAYBAY CITY, LEYTE</p>
            </div>
            <hr class="border-gray-300 mt-1 border-t-2 mb-2">

            <div style="clear: both;"></div>

            <h3 style="text-align: center; margin-top: 30px; font-size: 16px; font-weight: bold;">5-YEAR MILESTONE LOYALTY AWARDEES</h3>
            <div style="margin-top: 20px;">
                <table style="width:100%; border-collapse:collapse; margin:0 auto; font-size:14px;">
                    <thead>
                        <tr>
                            <th colspan="5" style="border:2px solid #000; text-align:center; font-weight:bold; font-size:15px; padding:6px 0;">LIST OF NAMES</th>
                        </tr>
                        <tr>
                            <th style="border:2px solid #000; text-align:center; font-weight:bold; width:8%;">NO(s)</th>
                            <th style="border:2px solid #000; text-align:center; font-weight:bold; width:36%;">NAME</th>
                            <th style="border:2px solid #000; text-align:center; font-weight:bold; width:20%;">POSITION</th>
                            <th style="border:2px solid #000; text-align:center; font-weight:bold; width:24%;">SCHOOL</th>
                            <th style="border:2px solid #000; text-align:center; font-weight:bold; width:12%;">YEARS OF SERVICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rowCount = 0; @endphp
                        @foreach($personnels as $index => $personnel)
                        <tr>
                            <td style="border:2px solid #000; text-align:center; padding:8px 0;">{{ $index + 1 }}</td>
                            <td style="border:2px solid #000; text-align:left; padding-left:10px;">{{ $personnel->fullName() }}</td>
                            <td style="border:2px solid #000; text-align:left; padding-left:10px;">{{ $personnel->position->title ?? '' }}</td>
                            <td style="border:2px solid #000; text-align:left; padding-left:10px;">{{ $personnel->school->school_name ?? '' }}</td>
                            <td style="border:2px solid #000; text-align:center; padding:8px 0;">{{ $personnel->years_of_service }} years</td>
                        </tr>
                        @php $rowCount++; @endphp
                        @endforeach
                        @for($i = $rowCount; $i < 8; $i++)
                            <tr>
                            <td style="border:2px solid #000; text-align:center; padding:8px 0;">&nbsp;</td>
                            <td style="border:2px solid #000; text-align:left; padding-left:10px;">&nbsp;</td>
                            <td style="border:2px solid #000; text-align:left; padding-left:10px;">&nbsp;</td>
                            <td style="border:2px solid #000; text-align:left; padding-left:10px;">&nbsp;</td>
                            <td style="border:2px solid #000; text-align:center; padding:8px 0;">&nbsp;</td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
            </div>

            <!-- <div style="margin-top: 30px; font-size: 13px;">
                <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <p style="margin: 0; font-weight: bold; color: #1f2937;">Award Details:</p>
                    <p style="margin: 5px 0 0 0; color: #4b5563;">• Total Recipients: {{ $personnels->count() }}</p>
                    <p style="margin: 5px 0 0 0; color: #4b5563;">• Award Amount per Recipient: ₱5,000</p>
                    <p style="margin: 5px 0 0 0; color: #4b5563;">• Total Award Amount: ₱{{ number_format($personnels->count() * 5000) }}</p>
                    <p style="margin: 5px 0 0 0; color: #4b5563;">• Date Generated: {{ $date }}</p>
                </div>

                @php
                // Group by years of service for better organization
                $groupedByYears = $personnels->groupBy('years_of_service')->sortKeys();
                @endphp

                @if($groupedByYears->count() > 1)
                <div style="background-color: #e0f2fe; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0; font-weight: bold; color: #1f2937;">Recipients by Years of Service:</p>
                    @foreach($groupedByYears as $years => $groupPersonnels)
                    <p style="margin: 5px 0 0 0; color: #4b5563;">• {{ $years }} years: {{ $groupPersonnels->count() }} recipient(s)</p>
                    @endforeach
                </div>
                @endif
            </div> -->

            <div style="margin-top: 40px; font-size: 13px;">
                <div style="margin-bottom: 30px;">
                    <strong>Recommending Approval:</strong><br><br>
                    <span style="font-weight: bold; text-decoration: underline;">{{$oic_assistant_schools_division_superintendent_signature->full_name}}</span><br>
                    {{$oic_assistant_schools_division_superintendent_signature->position_name}}
                </div>
                <div>
                    <strong>Approved:</strong><br><br>
                    <span style="font-weight: bold; text-decoration: underline;">{{$schools_division_superintendent_signature->full_name}}</span><br>
                    {{$schools_division_superintendent_signature->position_name}}
                </div>
            </div>
        </div>

        <!-- Footer always at the bottom -->
        <div class="footer">
            <hr class="border-gray-300 mt-1 border-t-2 mb-2">
            <div style="width: 30%; float: left;">
                <img src="{{ public_path('image/deped-matatag.png') }}" alt="DepEd Matatag Logo" style="height: 70px;">
                <img src="{{ public_path('image/division-logo.png') }}" alt="Division Logo" style="height: 62px;">
            </div>
            <div style="width: 70%; float: right; font-size: 13px; line-height: 1.5; color: #1f2937;">
                <p><strong>Address:</strong> Diversion Road, Barangay Gaas, Baybay City, Leyte</p>
                <p><strong>Telephone #:</strong> (53) 563-7615</p>
                <p><strong>Email Address:</strong> baybaycity@deped.gov.ph</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>

</html>
