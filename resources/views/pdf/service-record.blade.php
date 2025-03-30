<!DOCTYPE html>
<html>
<head>
    <title>Service Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Service Record of {{ $personnel->first_name }} {{ $personnel->last_name }}</h2>
    <table>
        <thead>
            <tr>
                <th>From Date</th>
                <th>To Date</th>
                <th>Position</th>
                <th>Appointment Status</th>
                <th>Salary</th>
                <th>Station</th>
                <th>Branch</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($serviceRecords as $record)
                <tr>
                    <td>{{ $record->from_date }}</td>
                    <td>{{ $record->to_date }}</td>
                    <td>{{ $record->position_id }}</td>
                    <td>{{ $record->appointment_status }}</td>
                    <td>{{ $record->salary }}</td>
                    <td>{{ $record->station }}</td>
                    <td>{{ $record->branch }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
