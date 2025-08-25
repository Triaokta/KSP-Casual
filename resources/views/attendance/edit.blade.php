@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Absensi /</span> Edit Absensi
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Edit Absensi</h5>
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

                    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">Pilih Karyawan</label>
                                <select id="employee_id" name="employee_id" class="form-select" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4" id="timeFields">
                            <div class="col-md-6">
                                <label for="time_in" class="form-label">Jam Masuk</label>
                                <input type="time" class="form-control" id="time_in" name="time_in" value="{{ old('time_in', $attendance->time_in ? $attendance->time_in->format('H:i') : '') }}">
                                <input type="hidden" id="time_in_display" name="time_in_display" value="{{ $attendance->time_in_formatted }}">
                            </div>

                            <div class="col-md-6">
                                <label for="time_out" class="form-label">Jam Keluar</label>
                                <input type="time" class="form-control" id="time_out" name="time_out" value="{{ old('time_out', $attendance->time_out ? $attendance->time_out->format('H:i') : '') }}">
                                <input type="hidden" id="time_out_display" name="time_out_display" value="{{ $attendance->time_out_formatted }}">
                                <small class="text-muted">Kosongkan jika belum pulang</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="hadir" {{ old('status', $attendance->status) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ old('status', $attendance->status) == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ old('status', $attendance->status) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="cuti" {{ old('status', $attendance->status) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="tanpa_keterangan" {{ old('status', $attendance->status) == 'tanpa_keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $attendance->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status');
        const timeFields = document.getElementById('timeFields');
        
        // Fungsi untuk mengatur visibilitas field waktu berdasarkan status
        function toggleTimeFields() {
            if (statusSelect.value === 'hadir') {
                timeFields.style.display = 'flex';
                document.getElementById('time_in').setAttribute('required', 'required');
                // Update hidden fields jika ada nilai
                if (document.getElementById('time_in').value) {
                    document.getElementById('time_in_display').value = document.getElementById('time_in').value;
                } else {
                    document.getElementById('time_in_display').value = '-';
                }
                
                if (document.getElementById('time_out').value) {
                    document.getElementById('time_out_display').value = document.getElementById('time_out').value;
                } else {
                    document.getElementById('time_out_display').value = '-';
                }
            } else {
                timeFields.style.display = 'none';
                document.getElementById('time_in').removeAttribute('required');
                document.getElementById('time_in').value = '';
                document.getElementById('time_out').value = '';
                // Set nilai display ke strip
                document.getElementById('time_in_display').value = '-';
                document.getElementById('time_out_display').value = '-';
            }
        }
        
        // Jalankan saat halaman dimuat
        toggleTimeFields();
        
        // Jalankan saat status berubah
        statusSelect.addEventListener('change', toggleTimeFields);
        
        // Event listeners untuk update nilai display saat nilai waktu berubah
        document.getElementById('time_in').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('time_in_display').value = this.value;
            } else {
                document.getElementById('time_in_display').value = '-';
            }
        });
        
        document.getElementById('time_out').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('time_out_display').value = this.value;
            } else {
                document.getElementById('time_out_display').value = '-';
            }
        });
    });
</script>
@endpush
@endsection
