@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Absensi /</span> Daftar Absensi
    </h4>

    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Absensi</h5>
                    <div>
                        <a href="{{ route('attendance.report') }}" class="btn btn-secondary me-2">
                            <i class="bx bx-bar-chart-alt-2 me-1"></i> Laporan
                        </a>
                        <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i> Tambah Absensi
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('attendance.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="date" class="form-control" value="{{ request('date', now()->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Karyawan</label>
                                <select name="employee_id" class="form-select">
                                    <option value="">-- Semua Karyawan --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">-- Semua Status --</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="tanpa_keterangan" {{ request('status') == 'tanpa_keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>ID Karyawan</th>
                                    <th>Nama</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($attendances) > 0)
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                            <td>{{ $attendance->employee->employee_id }}</td>
                                            <td>{{ $attendance->employee->name }}</td>
                                            <td>{{ $attendance->time_in_formatted }}</td>
                                            <td>{{ $attendance->time_out_formatted }}</td>
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
                                                    
                                                    // Format status untuk tampilan yang lebih baik
                                                    $statusText = $attendance->status;
                                                    if ($statusText == 'tanpa_keterangan') {
                                                        $statusText = 'Tanpa Keterangan';
                                                    } else {
                                                        $statusText = ucfirst($statusText);
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('attendance.show', $attendance->id) }}" class="btn btn-info btn-sm">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
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
                    
                    <div class="mt-4">
                        {{ $attendances->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
