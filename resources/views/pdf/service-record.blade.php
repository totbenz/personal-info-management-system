<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Record</title>
    <link rel="stylesheet" href="{{ asset('resources/css/fonts.css') }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h4 {
            text-align: center;
            font-size: 14px;
            font-weight: normal;
        }
        h3 {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: -15px;
            letter-spacing: .75px;
        }
        h3 span {
            font-size: 14px;
            font-weight: normal;
        }
        .service-record {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #1865a6;
            margin-top: -12px;
            letter-spacing: 1.5px;
        }
        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        .service-table, .service-table th {
            border: 0.5px solid black; 
        }
        .service-table td {
            border-left: 0.5px dashed black; 
            border-right: 0.5px dashed black; 
        }
        .service-table th, .service-table td {
            padding: 5px;
            text-align: center;
        }
        .service-table th:nth-child(6) {
            width: 80px;
        }
        .service-table th:nth-child(2),
        .service-table th:nth-child(3) {
            width: 120px; 
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="headers" style="display: flex; align-items: center; justify-content: space-between; position: relative;">
        <img src="{{ public_path('image/kagawaran-ng-edukasyon-logo.png') }}" alt="deped Logo" style="width: 100px; height: auto; position: absolute; left: 7%; top: 7%; transform: translateY(-50%);">
        <div style="text-align: center; flex-grow: 1;">
            <h4>
                Republic of the Philippines <br>
                Department of Education<br>
                Region VIII - Eastern Visayas
            </h4>
            <h3>
                SCHOOLS DIVISION OF BAYBAY CITY<br>
                <span>Brgy. Gaas, Baybay City, Leyte</span>
            </h3>
            <h3 class="service-record">SERVICE RECORD</h3>
        </div>
        <img src="{{ public_path('image/division-logo.png') }}" alt="division Logo" style="width: 100px; height: auto; position: absolute; right: 7%; top: 7%; transform: translateY(-50%);">
        <div style="font-size: 14px; position: absolute; right: 0;">
            Employee No. <span style="text-decoration: underline;">{{$personnel->personnel_id}}</span>
        </div>
    </div>
    <br><br>
    <div style="font-size: 14px; width: 100%; text-align: left; margin-top: 20px; display: flex; align-items: center;">
        <span style="margin-right: 10px;">Name:</span>
        <table style="border-collapse: collapse; display: inline-table; margin-bottom: -2px;">
            <tr style="border-bottom: 1px solid black;">
                <td style="width: 116px; text-align: center;">{{$personnel->last_name}}</td>
                <td style="width: 116px; text-align: center;">{{$personnel->first_name}}</td>
                <td style="width: 116px; text-align: center;">{{$personnel->middle_name}}</td>
            </tr>
        </table>
        <span style="margin-left: 20px;">(If married woman, give also maiden name)</span>
    </div>
    <table style="display: inline-table; margin-left: 50px; font-size: 12px;">
        <tr>
            <td style="width: 116px; text-align: center;">(Family Name)</td>
            <td style="width: 116px; text-align: center;">(First Name)</td>
            <td style="width: 116px; text-align: center;">(Middle Name)</td>
        </tr>
    </table>
    <br>
    <div style="font-size: 14px; width: 100%; text-align: left; margin-top: 20px; display: flex; align-items: center;">
        <span style="margin-right: 10px;">Birth:</span>
        <table style="border-collapse: collapse; display: inline-table; margin-bottom: -2px;">
            <tr style="border-bottom: 1px solid black;">
                <td style="width: 144px; text-align: center;">{{ \Carbon\Carbon::parse($personnel->date_of_birth)->format('F d, Y') }}</td>
                <td style="width: 214px; text-align: center;">{{$personnel->place_of_birth}}</td>
            </tr>
        </table>
        <span style="margin-left: 20px;">
            (<span style="white-space: nowrap;">Date herein should be checked from birth</span>
            <br>
            <span style="margin-left: 63%;">or baptismal certificate or other reliable</span>
            <br>
            <span style="margin-left: 63%;">documents)</span>
        </span>
    </div>
    <table style="display: inline-table; margin-left: 42px; font-size: 12px; margin-top: -34px;">
        <tr>
            <td style="width: 144px; text-align: center;">(Date)</td>
            <td style="width: 214px; text-align: center;">(Place)</td>
        </tr>
    </table>
    <br>
    <div  style="font-size: 14px; width: 100%; text-align: left; margin-top: 10px; display: flex; align-items: center;">
        <p>
            <span style="margin-left:10%;">THIS IS TO CERTIFY that the employee named above rendered service in this Office as shown by</span> <br>
            the service records below its line in which is supported by appointment and the other papers actually issued by this Office and approved
            by the authorities concerned.
        </p>
    </div>

    <!-- Service Table -->
    <table class="service-table">
    <thead>
        <tr>
            <th colspan="2">Inclusive Dates of Service<br>(mm-dd-yy)</th>
            <th colspan="3">Record of Appointment</th>
            <th rowspan="2" style="width: 80px;">DepEd/Division Station/Place</th> 
            <th rowspan="2">Branch</th>
            <th rowspan="2" style="width: 70px;">Leave of absence w/o pay</th>
            <th colspan="2">Separation</th>
        </tr>
        <tr>
            <th style="width: 50px;">From</th> 
            <th style="width: 50px;">To</th> 
            <th style="width: 70px;">Designation</th>
            <th>Status</th>
            <th>Salary p.a. (Php)</th>
            <th>Date</th>
            <th>Cause</th>
        </tr>
    </thead>

    <!-- Table Body -->
    <tbody>

        <!-- The data used are for testing purposes only -->
        <tr>
            <td>08-04-93</td>
            <td>12-31-93</td>
            <td>Teacher I</td>
            <td>R/Perm</td>
            <td>37,224</td>
            <td>Baybay N.</td>
            <td>N/M</td>
            <td>None</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>01-01-94</td>
            <td>12-31-94</td>
            <td>"</td>
            <td>"</td>
            <td>46,824</td>
            <td>"</td>
            <td>"</td>
            <td>"</td>
            <td></td>
            <td></td>
        </tr>
       
            <td>01-01-22</td>
            <td>12-31-22</td>
            <td>School Principal III</td>
            <td>"</td><td>730,812</td>
            <td>Bitanhuan ES</td>
            <td>"</td>
            <td>"</td>
            <td></td>
            <td><i>(Promotion)</i></td>
        </tr>
        <tr>
            <td>08-23-21</td>
            <td>12-31-22</td>
            <td>"</td><td>"</td>
            <td>730,812</td>
            <td>Plaridel CS</td>
            <td>"</td>
            <td>"</td>
            <td></td>
            <td><i>(S.O. 085, s.2021: Transfer of Station)</i></td>
        </tr>
        <tr>
            <td>01-01-23</td>
            <td>Present</td>
            <td>"</td>
            <td>"</td>
            <td>767,964</td>
            <td>"</td>
            <td>"</td>
            <td>"</td>
            <td></td>
            <td><i>(NBC 589)</i></td>
        </tr>
        <tr style="border-top: none;">
            <td colspan="10" style="text-align: left; font-weight: bold; padding: 8px; font-size: 12px;">
                Purpose: For Claims / Employment / File Copy / Loans / Ranking / Reclassification / Scholarship / Transfer / Others
            </td>
        </tr>
    </tbody>
        <!-- <tbody>
            @foreach ($serviceRecords as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->from_date)->format('F d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->to_date)->format('F d, Y') }}</td>
                    <td>{{ $record->position_id }}</td>
                    <td>{{ $record->appointment_status }}</td>
                    <td>{{ $record->salary }}</td>
                    <td>{{ $record->station }}</td>
                    <td>{{ $record->branch }}</td>
                </tr>
            @endforeach
        </tbody> -->
    </table>
    <br>

    <div style="font-size: 14px; text-align: left;">
        <span style="margin-left:10%">Issued in compliance with Executive Order No. 54, dated August 10, 1954 and in accordance  with</span><br>
       Circular No. 58, dated August 10, 1954 of the System.
    </div>

    <br><br>

    <table style="width: 100%; font-size: 14px; margin-top: 20px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <br><br><br>
                April 12, 2024<br>
                <span style="border-top: 1px solid black; display: inline-block; width: 150px;">Date</span>
            </td>
            <td style="width: 50%;"> 
            <span>CERTIFIED CORRECT:</span>
                <br><br><br>
                <u style="margin-left:10%;">JULIUS CAESAR C. DE LA CERNA</u><br>
                <span style="margin-left:10%;">Administrative Officer VI (HRMO II)</span><br>
            </td>
        </tr>
    </table>
</body>
</html>
