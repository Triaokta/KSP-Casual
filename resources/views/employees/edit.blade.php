@extends('layout.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan /</span> Edit Data</h4>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('employees._form')
            </form>
        </div>
    </div>
</div>
@endsection