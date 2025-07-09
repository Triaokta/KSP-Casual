@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Karyawan /</span> Tambah Data Baru</h4>

    <div class="card mb-4">
        <div class="card-body">
            {{-- Arahkan form ke rute 'employees.store' dengan method POST --}}
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                @include('employees._form')
            </form>
        </div>
    </div>
</div>
@endsection