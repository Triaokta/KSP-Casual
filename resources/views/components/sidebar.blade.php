<!-- Sidebar -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- Logo dan Nama Aplikasi -->
    <div class="app-brand demo d-flex align-items-center justify-content-between px-3 mt-3">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('logo-KSP-Casual.png') }}" alt="Logo" width="30">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">ksp-casual</span>
        </a>
    </div>

    <!-- Menu utama -->
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- Karyawan Casual -->
        <li class="menu-item {{ request()->routeIs('employees.index') ? 'active' : '' }}">
            <a href="{{ route('employees.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div>Karyawan Casual</div>
            </a>
        </li>

        <!-- Import -->
        <li class="menu-item {{ request()->routeIs('import.karyawan.form') ? 'active' : '' }}">
            <a href="{{ route('import.karyawan.form') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-upload"></i>
                <div>Import Karyawan</div>
            </a>
        </li>
    </ul>

    <!-- Spacer biar logout turun ke bawah -->
    <div class="flex-grow-1"></div>

    <!-- Garis pembatas -->
    <hr class="my-2 mx-0" />

    <!-- Info Profil User -->
    <div class="text-center border-bottom pb-3 mb-3 mt-2">
        <div class="avatar avatar-online mx-auto">
            <img src="{{ auth()->user()->profile_picture ?? asset('default-avatar.png') }}" alt="Avatar" class="w-px-80 rounded-circle" />
        </div>
        <h6 class="mt-2 mb-0">{{ auth()->user()->name }}</h6>
        <small class="text-muted d-block">{{ auth()->user()->email }}</small>
    </div>

    <!-- Tombol Logout -->
    <div class="px-3 pb-3">
        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin logout?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="bx bx-power-off me-2"></i> Logout
            </button>
        </form>
    </div>
</aside>
<!-- /Sidebar -->

