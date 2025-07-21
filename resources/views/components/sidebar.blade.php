<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            {{-- Logo Anda --}}
            <span class="app-brand-logo demo">
                <img src="{{ asset('logo-KSP-Casual.png') }}" alt="Logo Aplikasi" width="35">
            </span>
            
            {{-- TULISAN KAPITAL --}}
            <span class="app-brand-text demo menu-text fw-bolder ms-2">
                {{ strtoupper(config('app.name')) }}
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- Manajemen Karyawan -->
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('employees.*') ? 'active' : '' }}">
            <a href="{{ route('employees.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Karyawan Casual</div>
            </a>
        </li>

        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('import.*') ? 'active' : '' }}">
            <a href="{{ route('import.karyawan.form') }}" class="menu-link">
                <i class="menu-icon bx bx-download me-1"></i>    
                <div>Import Karyawan</div>
            </a>
        </li>

    </ul>
</aside>
