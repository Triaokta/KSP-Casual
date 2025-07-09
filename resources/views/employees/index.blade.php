@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Karyawan Casual</h4>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">Tambah Karyawan Baru</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>NOMOR KARYAWAN</th>
                        <th>NAMA</th>
                        <th>DEPARTEMEN</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($employees as $employee)
                    <tr>
                        <td><strong>{{ $employee->employee_id }}</strong></td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->department->name ?? 'N/A' }}</td>
                        <td>
                            @if ($employee->is_active)
                                <span class="badge bg-label-success me-1">Aktif</span>
                            @else
                                <span class="badge bg-label-secondary me-1">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            
                            <form action="{{ route('employees.toggle_status', $employee->id) }}" method="POST" class="d-inline">
                                @csrf
                                @if ($employee->is_active)
                                    <button type="submit" class="btn btn-sm btn-secondary">Nonaktifkan</button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-success">Aktifkan</button>
                                @endif
                            </form>
                            
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            @if(request('search'))
                                Karyawan dengan nama "{{ request('search') }}" tidak ditemukan.
                            @else
                                Belum ada data karyawan.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- (BARU) Link Paginasi --}}
        <div class="card-footer">
            {{ $employees->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection
