@extends('layout.main')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Import Data Karyawan</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{!! session('error') !!}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{!! session('warning') !!}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
    @endif

    <p class="text-muted">Silakan download template berikut sebelum mengimpor data:</p>
    <a href="{{ route('import.karyawan.template') }}?v={{ time() }}" class="btn mb-3"
        style="background-color: #5bc0de; color: white;">
        <i class="bx bx-download me-1"></i> Unduh Template Excel
    </a>



    <form action="{{ route('import.karyawan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Upload File Excel</label>
            <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
