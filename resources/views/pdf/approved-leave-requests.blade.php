<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Approved Leave Requests - {{ $month }} {{ $year }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&display=swap');

        @page {
            size: legal;
            margin: 0.25in 0.5in 0 0.5in;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 8.5in;
            font-size: 16px;
            font-family: Times New Roman, serif;
        }

        .unifraktur-font {
            font-family: 'UnifrakturCook' !important;
            font-weight: 700;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .stat-box {
            text-align: center;
        }

        .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #1865a6;
        }

        .table-container {
            margin-top: 20px;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
            margin-top: 10px;
        }

        .requests-table th,
        .requests-table td {
            border: 0.5px solid #333;
            padding: 10px 8px;
            text-align: center;
            vertical-align: middle;
        }

        .requests-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 14px;
        }

        .requests-table td {
            font-size: 13px;
        }

        .requests-table .text-left {
            text-align: left;
        }

        .role-badge {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            color: #333;
        }

        .role-school-head {
            color: #8B5CF6;
        }

        .role-teacher {
            color: #3B82F6;
        }

        .role-other {
            color: #6B7280;
        }

        .days-badge {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            background-color: #10B981;
        }
    </style>
</head>

<body class="text-gray-900">
    <!-- Header Section -->
    <div class="text-center mb-6">
        <img src="{{ public_path('image/kagawaran-ng-edukasyon-logo.png') }}" alt="deped Logo" style="width: 80px; height: auto; margin-bottom: 10px;">
        <h2 class="unifraktur-font">Republic of the Philippines</h2>
        <h1 class="font-semibold unifraktur-font" style="font-size: 24px; margin-top:-20px; letter-spacing:1px;">Department of Education</h1>
        <p style="font-size: 13px;">REGION VIII</p>
        <p style="font-size: 13px;">SCHOOLS DIVISION OF BAYBAY CITY, LEYTE</p>
        <hr class="border-gray-300 mt-3 border-t-2">
        <p class="mt-2 text-left" style="font-size: 15px;">Office of the Schools Division Superintendent</p>
    </div>

    <div class="text-center mb-6">
        <h1 class="font-bold uppercase" style="font-size: 24px;">APPROVED LEAVE REQUESTS REPORT</h1>
        <p class="mt-4" style="font-size: 16px;">{{ $month }} {{ $year }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-box">
            <div class="stat-number">{{ $totalRequests }}</div>
            <div>Total Requests</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $requests->unique('user.personnel.school.id')->count() }}</div>
            <div>Schools Involved</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $requests->sum(function($request) { return \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1; }) }}</div>
            <div>Total Leave Days</div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="table-container">
        @if($requests->count() > 0)
        <table class="requests-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 20%;">Personnel Name</th>
                    <th style="width: 10%;">Role</th>
                    <th style="width: 20%;">School</th>
                    <th style="width: 15%;">Leave Type</th>
                    <th style="width: 15%;">Leave Period</th>
                    <th style="width: 8%;">Days</th>
                    <th style="width: 12%;">Approved Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $index => $request)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        @if($request->user->personnel)
                            {{ $request->user->personnel->first_name }} {{ $request->user->personnel->last_name }}
                            @if($request->user->personnel->position)
                                <br><small style="color: #666;">{{ $request->user->personnel->position->title }}</small>
                            @endif
                        @else
                            {{ $request->user->name }}
                        @endif
                    </td>
                    <td>
                        <span class="role-badge 
                            @if($request->user->role === 'school_head') role-school-head
                            @elseif($request->user->role === 'teacher') role-teacher
                            @else role-other @endif">
                            {{ ucfirst(str_replace('_', ' ', $request->user->role)) }}
                        </span>
                    </td>
                    <td class="text-left">
                        @if($request->user->personnel && $request->user->personnel->school)
                            {{ $request->user->personnel->school->school_name }}
                            <br><small style="color: #666;">{{ $request->user->personnel->school->school_id }}</small>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $request->leave_type }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}
                        <br>to {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                    </td>
                    <td>
                        <span class="days-badge">
                            {{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }}
                        </span>
                    </td>
                    <td>
                        {{ $request->updated_at->format('M d, Y') }}
                        <br><small>{{ $request->updated_at->format('g:i A') }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <h3>No Approved Leave Requests Found</h3>
            <p>No leave requests were approved during {{ $month }} {{ $year }}</p>
        </div>
        @endif
    </div>

    <!-- Footer Section -->
    <div style="margin-top: 50px; page-break-inside: avoid;">
        <hr class="border-gray-300 mt-1 border-t-2 mb-2">

        <!-- Logo Section -->
        <div style="width: 30%; float: left; display: inline-block;">
            <img src="{{ public_path('image/deped-matatag.png') }}" alt="DepEd Matatag Logo" style="height: 70px; vertical-align: top; display: inline-block;">
            <img src="{{ public_path('image/division-logo.png') }}" alt="Division Logo" style="height: 62px; vertical-align: top; margin-left: 10px; display: inline-block;">
        </div>

        <!-- Contact Information -->
        <div style="width: 70%; float: right; font-size: 13px; line-height: 1.5; color: #1f2937;">
            <p><strong>Address:</strong> Diversion Road, Barangay Gaas, Baybay City, Leyte</p>
            <p><strong>Telephone #:</strong> (53) 563-7615</p>
            <p><strong>Email Address:</strong> baybaycity@deped.gov.ph</p>
        </div>
        
        <!-- Clear floats -->
        <div style="clear: both;"></div>
    </div>
</body>

</html>
