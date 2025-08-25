@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold mb-4">
        <span class="text-muted fw-light">Karyawan /</span> Detail Data
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $employee->name }}</h5>
            @if ($employee->is_active)
                <span class="badge bg-label-success">Aktif</span>
            @else
                <span class="badge bg-label-secondary">Nonaktif</span>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Karyawan:</strong><br> {{ $employee->employee_id }}</p>
                    <p><strong>Nama Lengkap:</strong><br> {{ $employee->name }}</p>
                    <p><strong>NIK:</strong><br> {{ $employee->nik_ktp }}</p>
                    <p><strong>Alamat:</strong><br> {{ $employee->address ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>NPWP:</strong><br> {{ $employee->npwp ?? '-' }}</p>
                    <p>
                        <strong>Nomor Rekening:</strong><br> 
                        @if($employee->no_rek)
                            {{ $employee->no_rek }}
                            @if($employee->bank_id && $employee->bank)
                                - {{ $employee->bank->name }}
                            @elseif($employee->nama_bank)
                                - {{ $employee->nama_bank }}
                            @endif
                        @else
                            -
                        @endif
                    </p>
                    <p><strong>Departemen:</strong><br> {{ $employee->department->name ?? 'N/A' }}</p>
                    <p><strong>Terakhir Diperbarui:</strong><br> {{ $employee->updated_at->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>
            
            <!-- Riwayat Status Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="mb-3">Riwayat Status</h5>
                    
                    <div class="alert alert-info mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>Status Saat Ini:</strong> 
                                @if ($employee->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </div>
                            <div>
                                <strong>Riwayat Perubahan:</strong> {{ count($statusHistories) }} kali
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="me-3"><i class="bx bx-check-circle text-success"></i> Diaktifkan: {{ $activeCount }} kali</span>
                            <span><i class="bx bx-x-circle text-secondary"></i> Dinonaktifkan: {{ $inactiveCount }} kali</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Diubah Oleh</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statusHistories as $index => $history)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $history->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @if($history->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->changed_by }}</td>
                                    <td>{{ $history->notes }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada riwayat perubahan status</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
        </div>
    </div>
</div>
@endsection
