@extends('layout.main')

@push('style')
    <link rel="stylesheet" href="{{asset('sneat/vendor/libs/apex-charts/apex-charts.css')}}" />
@endpush

@push('script')
    <script src="{{asset('sneat/vendor/libs/apex-charts/apexcharts.js')}}"></script>
    <script>
        const options = {
            chart: {
                type: 'bar'
            },
            series: [{
                name: '{{ __('dashboard.letter_transaction') }}',
                data: [{{ $activeEmployees }}, {{ $inactiveEmployees }}]
            }],
            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                categories: [
                    '{{ __('dashboard.active_employees') }}',
                    '{{ __('dashboard.inactive_employees') }}',
                ],
            }
        }

        const chart = new ApexCharts(document.querySelector("#today-graphic"), options);

        chart.render();
        
        // Script untuk otomatis submit form saat departemen dipilih
        document.getElementById('department_id').addEventListener('change', function() {
            document.getElementById('departmentFilterForm').submit();
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h4 class="card-title text-primary">{{ $greeting }}</h4>
                            <p class="mb-4">
                                {{ $currentDate }}
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{asset('sneat/img/man-with-laptop-light.png')}}" height="140"
                                 alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                 data-app-light-img="illustrations/man-with-laptop-light.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12 mb-4">
            <div class="card">
                <form action="{{ route('dashboard') }}" method="GET" id="departmentFilterForm">
                    <div class="row align-items-center">
                        <div class="input-group">
                            <select class="form-select" name="department_id" id="department_id">
                                <option value="">Semua Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-12 order-1">
            <div class="row text-center">
                <div class="col-md-4 col-12 mb-4">
                    <x-dashboard-card-simple
                        label="Total Karyawan"
                        :value="$totalEmployees"
                        :daily="false"
                        color="info"
                        icon="bx-user"
                        :percentage="0"
                    />
                </div>
                <div class="col-md-4 col-12 mb-4">
                    <x-dashboard-card-simple
                        label="Karyawan Aktif"
                        :value="$activeEmployees"
                        :daily="false"
                        color="success"
                        icon="bx-user-check"
                        :percentage="0"
                    />
                </div>
                <div class="col-md-4 col-12 mb-4">
                    <x-dashboard-card-simple
                        label="Karyawan Nonaktif"
                        :value="$inactiveEmployees"
                        :daily="false"
                        color="danger"
                        icon="bx-user-x"
                        :percentage="0"
                    />
                </div>
            </div>
        </div>

    </div>
@endsection
