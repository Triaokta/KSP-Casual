@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Absensi /</span> Tambah Absensi Baru
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Absensi Baru</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('attendance.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">Pilih Karyawan</label>
                                <select id="employee_id" name="employee_id" class="form-select" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $today) }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="time_in" class="form-label">Jam Masuk</label>
                                <input type="time" class="form-control" id="time_in" name="time_in" value="{{ old('time_in', '08:00') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="time_out" class="form-label">Jam Keluar</label>
                                <input type="time" class="form-control" id="time_out" name="time_out" value="{{ old('time_out', '17:00') }}">
                                <small class="text-muted">Kosongkan jika belum pulang</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="cuti" {{ old('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="tanpa_keterangan" {{ old('status') == 'tanpa_keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
