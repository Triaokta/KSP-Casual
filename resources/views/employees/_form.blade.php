{{-- File: resources/views/employees/_form.blade.php --}}

{{-- Tampilkan error validasi jika ada --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Oops! Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="employee_id" class="form-label">Nomor Induk Karyawan</label>
    <input type="text" class="form-control" id="employee_id" name="employee_id"
        value="{{ old('employee_id', $employee->employee_id ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nama Lengkap</label>
    <input type="text" class="form-control" id="name" name="name"
        value="{{ old('name', $employee->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="nik_ktp" class="form-label">No. KTP</label>
    <input type="text" class="form-control" id="nik_ktp" name="nik_ktp"
        value="{{ old('nik_ktp', $employee->nik_ktp ?? '') }}"
        maxlength="16" pattern="\d{16}" inputmode="numeric" required>
</div>

<div class="mb-3">
    <label for="address" class="form-label">Alamat</label>
    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $employee->address ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="npwp" class="form-label">NPWP</label>
    <input type="text" class="form-control" id="npwp" name="npwp"
        value="{{ old('npwp', $employee->npwp ?? '') }}"
        maxlength="16" pattern="\d{16}" inputmode="numeric">
</div>

<div class="mb-3">
    <label for="no_kk" class="form-label">No. Kartu Keluarga</label>
    <input type="text" class="form-control" id="no_kk" name="no_kk"
        value="{{ old('no_kk', $employee->no_kk ?? '') }}"
        maxlength="16" pattern="\d{16}" inputmode="numeric">
</div>

<div class="mb-3">
    <label for="department_id" class="form-label">Departemen / Divisi</label>
    <select class="form-select" id="department_id" name="department_id" required>
        <option value="" disabled {{ old('department_id', $employee->department_id ?? '') == '' ? 'selected' : '' }}>Pilih Departemen...</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}"
                {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                {{ $department->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="is_active" class="form-label">Status</label>
    <select class="form-select" id="is_active" name="is_active" required>
        <option value="1" {{ old('is_active', $employee->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ old('is_active', $employee->is_active ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Simpan</button>
<a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
