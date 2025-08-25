@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Absensi /</span> Laporan Absensi
    </h4>

    <div class="row">
        <div class="col-12">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Filter Laporan</h5>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendance.report') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Karyawan</label>
                                <select name="employee_id" class="form-select">
                                    <option value="">-- Semua Karyawan --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $employeeId == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $summary['total'] }}</h4>
                            <p class="mb-0">Total Presensi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $summary['hadir'] }}</h4>
                            <p class="mb-0">Hadir</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $summary['izin'] }}</h4>
                            <p class="mb-0">Izin</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $summary['sakit'] }}</h4>
                            <p class="mb-0">Sakit</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $summary['cuti'] }}</h4>
                            <p class="mb-0">Cuti</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $summary['tanpa_keterangan'] }}</h4>
                            <p class="mb-0">Tanpa Ket.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laporan Absensi</h5>
                    <div>
                        <a href="{{ route('attendance.export-excel', [
                            'start_date' => $startDate, 
                            'end_date' => $endDate,
                            'employee_id' => $employeeId
                        ]) }}" class="btn btn-success" id="btn-export-excel">
                            <i class="bx bx-export me-1"></i> Export Excel
                        </a>
                        <button type="button" class="btn btn-danger" onclick="window.print()">
                            <i class="bx bx-printer me-1"></i> Print
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="attendance-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Karyawan</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($attendances) > 0)
                                    @foreach($attendances as $index => $attendance)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                            <td>{{ $attendance->employee->name }} ({{ $attendance->employee->employee_id }})</td>
                                            <td>{{ $attendance->time_in }}</td>
                                            <td>{{ $attendance->time_out ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'hadir' => 'success',
                                                        'izin' => 'info',
                                                        'sakit' => 'warning',
                                                        'cuti' => 'primary',
                                                        'tanpa_keterangan' => 'danger'
                                                    ];
                                                    $color = $statusColors[$attendance->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $attendance->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">Tidak ada data absensi ditemukan</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan loading state saat tombol export diklik
        const btnExport = document.getElementById('btn-export-excel');
        if (btnExport) {
            btnExport.addEventListener('click', function() {
                // Disable button dan tampilkan status loading
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                this.disabled = true;
                
                // Set timeout untuk enable button kembali jika terjadi masalah
                setTimeout(() => {
                    this.innerHTML = '<i class="bx bx-export me-1"></i> Export Excel';
                    this.disabled = false;
                }, 10000); // 10 detik timeout
            });
        }
    });
</script>
@endpush

<style>
    @media print {
        header, .app-brand, .layout-menu, .btn, footer {
            display: none !important;
        }
        .container-xxl {
            max-width: 100% !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd;
        }
        body {
            padding: 20px !important;
        }
    }
</style>
@endsection
