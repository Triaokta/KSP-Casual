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
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."/>
            </div>
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        {{-- Gunakan ikon user generik untuk sementara --}}
                        <i class='bx bxs-user-circle' style='font-size: 40px;'></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        {{-- Tombol Logout --}}
                        <form action="{{ route('logout') }}" method="post" onsubmit="return confirm('Anda yakin ingin logout?');">
                            @csrf
                            <button type="submit" class="dropdown-item cursor-pointer">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>