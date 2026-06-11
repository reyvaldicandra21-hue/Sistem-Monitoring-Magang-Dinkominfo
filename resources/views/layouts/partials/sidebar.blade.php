<div class="sidebar-wrapper">
    <div class="sidebar-box">

        <!-- LOGO -->
        <div class="logo mb-4" style="flex-direction: column; align-items: flex-start; gap: 6px;">
            <img src="{{ asset('images/logo-mako.png') }}" alt="MAKO Logo" style="width: 140px; height: auto; object-fit: contain;">
        </div>

        <small class="menu-title">MENU</small>

        <ul class="menu-list">

            <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class='bx bx-grid-alt'></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.laporanharian.index') ? 'active' : '' }}">
                <a href="{{ route('admin.laporanharian.index') }}">
                    <i class='bx bx-file'></i>
                    <span>Laporan</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.absensis.index') ? 'active' : '' }}">
                <a href="{{ route('admin.absensis.index') }}">
                    <i class='bx bx-calendar'></i>
                    <span>Absensi</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.tugas.index') ? 'active' : '' }}">
                <a href="{{ route('admin.tugas.index') }}">
                    <i class='bx bx-clipboard'></i>
                    <span>Tugas</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.divisi.index') ? 'active' : '' }}">
                <a href="{{ route('admin.divisi.index') }}">
                    <i class='bx bx-sitemap'></i>
                    <span>Divisi</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.pesertapkl.index') ? 'active' : '' }}">
                <a href="{{ route('admin.pesertapkl.index') }}">
                    <i class='bx bx-user'></i>
                    <span>Peserta</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.pembimbing.index') ? 'active' : '' }}">
                <a href="{{ route('admin.pembimbing.index') }}">
                    <i class='bx bx-user-pin'></i>
                    <span>Pembimbing</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.penilaian.index') ? 'active' : '' }}">
                <a href="{{ route('admin.penilaian.index') }}">
                    <i class='bx bx-bar-chart-alt-2'></i>
                    <span>Penilaian</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">
                    <i class='bx bx-shield-quarter'></i>
                    <span>Manajemen User</span>
                </a>
            </li>

        </ul>

        <!-- Spacer -->
        <div class="mt-auto"></div>

    </div>
</div>
