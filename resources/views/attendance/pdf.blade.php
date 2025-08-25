<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .summary-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 5px 10px;
            text-align: center;
            width: 15%;
        }
        .summary-box h4 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .summary-box p {
            margin: 5px 0 0;
            font-size: 11px;
        }
        /* Style untuk kotak ringkasan sudah dihapus, tidak menggunakan warna-warni lagi */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        /* Style untuk status sudah dihapus, tidak menggunakan warna-warni lagi */
        .page-break {
            page-break-after: always;
        }
        .meta {
            margin-bottom: 15px;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 30px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="subtitle">{{ $periodText }}</div>
    <div class="meta">{{ $employeeText }}</div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 25%;">Karyawan</th>
                <th style="width: 10%;">Jam Masuk</th>
                <th style="width: 10%;">Jam Keluar</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 25%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @if(count($attendances) > 0)
                @foreach($attendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                        <td>{{ $attendance->employee->name }} ({{ $attendance->employee->employee_id }})</td>
                        <td>{{ $attendance->time_in_formatted }}</td>
                        <td>{{ $attendance->time_out_formatted }}</td>
                        <td>
                            @php
                                // Format status untuk tampilan yang lebih baik
                                $statusText = $attendance->status;
                                if ($statusText == 'tanpa_keterangan') {
                                    $statusText = 'Tanpa Keterangan';
                                } else {
                                    $statusText = ucfirst($statusText);
                                }
                            @endphp
                            {{ $statusText }}
                        </td>
                        <td>{{ $attendance->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px;">Tidak ada data absensi ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
