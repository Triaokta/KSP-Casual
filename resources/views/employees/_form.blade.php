@if (session('error'))
    <div class="alert alert-danger">
        <strong>Peringatan:</strong> {!! session('error') !!}
    </div>
@endif

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
    <label for="employee_id" class="form-label">ID Karyawan</label>
    <input type="text" class="form-control" id="employee_id" name="employee_id"
        value="{{ old('employee_id', $employee->employee_id ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nama Lengkap Karyawan</label>
    <input type="text" class="form-control" id="name" name="name"
        value="{{ old('name', $employee->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="nik_ktp" class="form-label">NIK</label>
    <input type="text" class="form-control" id="nik_ktp" name="nik_ktp"
        value="{{ old('nik_ktp', $employee->nik_ktp ?? '') }}"
        maxlength="16" pattern="\d{16}" inputmode="numeric" required>
</div>

<div class="mb-3">
    <label for="address" class="form-label">Alamat</label>
    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $employee->address ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="npwp" class="form-label">NPWP *</label>
    <input type="text" class="form-control" id="npwp" name="npwp"
        value="{{ old('npwp', $employee->npwp ?? '') }}"
        maxlength="16" pattern="\d{16}" inputmode="numeric">
</div>

<div class="mb-3">
    <label for="no_rek" class="form-label">Nomor Rekening & Bank</label>
    <div class="d-flex gap-2">
        <input type="text" class="form-control" id="no_rek" name="no_rek"
            value="{{ old('no_rek', $employee->no_rek ?? '') }}"
            placeholder="Nomor Rekening" style="flex:1">

        <select class="form-select select2" id="bank_id" name="bank_id" style="flex:1">
            <option value="">Pilih Bank...</option>
            @foreach($banks as $bank)
                <option value="{{ $bank->id }}" 
                    {{ old('bank_id', $employee->bank_id ?? '') == $bank->id ? 'selected' : '' }}>
                    {{ $bank->name }}
                </option>
            @endforeach
        </select>

        <input type="text" class="form-control" id="nama_bank" 
            name="nama_bank" placeholder="Isi manual jika bank tidak ada"
            value="{{ old('nama_bank', $employee->nama_bank ?? '') }}" style="flex:1">
    </div>
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
