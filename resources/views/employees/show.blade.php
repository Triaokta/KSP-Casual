@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
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
                    <p><strong>Nomor Karyawan:</strong><br> {{ $employee->employee_id }}</p>
                    <p><strong>Nama Lengkap:</strong><br> {{ $employee->name }}</p>
                    <p><strong>No. KTP:</strong><br> {{ $employee->nik_ktp }}</p>
                    <p><strong>Alamat:</strong><br> {{ $employee->address ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>NPWP:</strong><br> {{ $employee->npwp ?? '-' }}</p>
                    <p><strong>No. Kartu Keluarga:</strong><br> {{ $employee->no_kk ?? '-' }}</p>
                    <p><strong>Departemen:</strong><br> {{ $employee->department->name ?? 'N/A' }}</p>
                    <p><strong>Terakhir Diperbarui:</strong><br> {{ $employee->updated_at->format('d F Y, H:i') }} WIB</p>
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
