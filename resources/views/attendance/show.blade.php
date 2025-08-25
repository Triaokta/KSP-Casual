@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Absensi /</span> Detail Absensi
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Absensi</h5>
                    <div>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-warning">
                            <i class="bx bx-edit me-1"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">ID Karyawan</th>
                                    <td>: {{ $attendance->employee->employee_id }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <td>: {{ $attendance->employee->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>: {{ $attendance->date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Jam Masuk</th>
                                    <td>: {{ $attendance->time_in }}</td>
                                </tr>
                                <tr>
                                    <th>Jam Keluar</th>
                                    <td>: {{ $attendance->time_out ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Status</th>
                                    <td>: 
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
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>: {{ $attendance->notes ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Dibuat</th>
                                    <td>: {{ $attendance->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diubah</th>
                                    <td>: {{ $attendance->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
