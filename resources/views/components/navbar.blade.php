<nav 
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" 
    id="layout-navbar" 
    style="z-index: 1050;"
>
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <form action="{{ route('employees.index') }}" method="GET" class="d-flex w-100 gap-2 align-items-center">
        {{-- Input Pencarian --}}
        <div class="input-group">
            <span class="input-group-text border-0 bg-white">
                <i class="bx bx-search fs-4 lh-0"></i>
            </span>
            <input
                type="text"
                class="form-control border-0 shadow-none"
                name="search"
                placeholder="Cari nama karyawan..."
                aria-label="Cari nama karyawan..."
                value="{{ request('search') }}"
            />
        </div>

        {{-- Dropdown Departemen --}}
        <select
            name="department_id"
            class="form-select border-0 shadow-none"
            onchange="this.form.submit()"
            style="max-width: 200px;"
        >
            <option value="">Pilih Departemen</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </form>

</nav>
