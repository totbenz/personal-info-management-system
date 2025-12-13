<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>CTO Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="header">Compensatory Time Off (CTO) Request</div>
    <div class="section">
        <span class="label">Personnel Name:</span> {{ $personnel->full_name ?? '-' }}<br>
        <span class="label">Position:</span> {{ $personnel->position->name ?? '-' }}<br>
        <span class="label">School:</span> {{ $personnel->school->name ?? '-' }}<br>
        <span class="label">Date Filed:</span> {{ $ctoRequest->created_at->format('M d, Y') }}<br>
    </div>
    <div class="section">
        <span class="label">Work Date:</span> {{ $ctoRequest->work_date->format('M d, Y') }}<br>
        <span class="label">Morning In:</span> {{ $ctoRequest->morning_in ?? '-' }}<br>
        <span class="label">Morning Out:</span> {{ $ctoRequest->morning_out ?? '-' }}<br>
        <span class="label">Afternoon In:</span> {{ $ctoRequest->afternoon_in ?? '-' }}<br>
        <span class="label">Afternoon Out:</span> {{ $ctoRequest->afternoon_out ?? '-' }}<br>
        <span class="label">Total Hours:</span> {{ $ctoRequest->total_hours ?? '-' }}<br>
    </div>
    <div class="section">
        <span class="label">Reason:</span><br>
        <div style="margin-left: 10px;">{{ $ctoRequest->reason }}</div>
    </div>
    <div class="section">
        <span class="label">Description:</span><br>
        <div style="margin-left: 10px;">{{ $ctoRequest->description ?? '-' }}</div>
    </div>
    <div class="section">
        <span class="label">Status:</span> {{ ucfirst($ctoRequest->status) }}<br>
        @if($ctoRequest->status === 'approved')
        <span class="label">Approved At:</span> {{ $ctoRequest->approved_at ? $ctoRequest->approved_at->format('M d, Y') : '-' }}<br>
        @elseif($ctoRequest->status === 'denied')
        <span class="label">Denied At:</span> {{ $ctoRequest->approved_at ? $ctoRequest->approved_at->format('M d, Y') : '-' }}<br>
        @endif
    </div>
</body>

</html>