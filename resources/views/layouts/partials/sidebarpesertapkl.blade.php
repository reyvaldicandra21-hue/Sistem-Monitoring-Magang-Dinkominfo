<div class="sidebar-wrapper">
    <div class="sidebar-box">

        <!-- LOGO -->
        <div class="logo mb-4" style="flex-direction: column; align-items: flex-start; gap: 6px;">
            <img src="{{ asset('images/logo-mako.png') }}" alt="MAKO Logo" style="width: 140px; height: auto; object-fit: contain;">
        </div>

        <small class="menu-title">MENU</small>

        <ul class="menu-list">

            <li class="menu-item {{ request()->routeIs('pesertapkl.dashboard') ? 'active' : '' }}">
                <a href="{{ route('pesertapkl.dashboard') }}">
                    <i class='bx bx-grid-alt'></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pesertapkl.absensi.index') ? 'active' : '' }}">
                <a href="{{ route('pesertapkl.absensi.index') }}">
                    <i class='bx bx-calendar'></i>
                    <span>Absensi</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pesertapkl.laporanharian.index') ? 'active' : '' }}">
                <a href="{{ route('pesertapkl.laporanharian.index') }}">
                    <i class='bx bx-task'></i>
                    <span>Laporan</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pesertapkl.tugas.index') ? 'active' : '' }}">
                <a href="{{ route('pesertapkl.tugas.index') }}">
                    <i class='bx bx-task'></i>
                    <span>Tugas</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pesertapkl.nilai.index') ? 'active' : '' }}">
                <a href="{{ route('pesertapkl.nilai.index') }}">
                    <i class='bx bx-bar-chart-alt-2'></i>
                    <span>Nilai</span>
                </a>
            </li>

        </ul>

        <div class="mt-auto"></div>

    </div>
</div>
