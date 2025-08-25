<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;  
        }
        td {
            text-align: left;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Karyawan Aktif</h1>
        <p>Tanggal: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Karyawan</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>NPWP</th>
                <th>Departemen</th>
                <th>No. Rekening</th>
                <th>Bank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $index => $employee)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $employee->employee_id }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->nik_ktp }}</td>
                <td>{{ $employee->address ?? '-' }}</td>
                <td>{{ $employee->npwp ?? '-' }}</td>
                <td>{{ $employee->department->name ?? '-' }}</td>
                <td>{{ $employee->no_rek ?? '-' }}</td>
                <td>
                    @if($employee->bank_id)
                        {{ $employee->bank->name }}
                    @elseif($employee->nama_bank)
                        {{ $employee->nama_bank }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data karyawan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
