<div class="sidebar-wrapper">
    <div class="sidebar-box">

        <!-- LOGO -->
        <div class="logo mb-4" style="flex-direction: column; align-items: flex-start; gap: 6px;">
            <img src="{{ asset('images/logo-mako.png') }}" alt="MAKO Logo" style="width: 140px; height: auto; object-fit: contain;">
        </div>

        <small class="menu-title">MENU</small>

        <ul class="menu-list">

            <li class="menu-item {{ request()->routeIs('pembimbing.dashboard') ? 'active' : '' }}">
                <a href="{{ route('pembimbing.dashboard') }}">
                    <i class='bx bx-grid-alt'></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pembimbing.laporanharian.index') ? 'active' : '' }}">
                <a href="{{ route('pembimbing.laporanharian.index') }}">
                    <i class='bx bx-task'></i>
                    <span>Laporan Harian</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pembimbing.tugas.index') ? 'active' : '' }}">
                <a href="{{ route('pembimbing.tugas.index') }}">
                    <i class='bx bx-clipboard'></i>
                    <span>Tugas</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pembimbing.absensi.index') ? 'active' : '' }}">
                <a href="{{ route('pembimbing.absensi.index') }}">
                    <i class='bx bx-calendar'></i>
                    <span>Absensi</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pembimbing.peserta.index') ? 'active' : '' }}">
                <a href="{{ route('pembimbing.peserta.index') }}">
                    <i class='bx bx-group'></i>
                    <span>Peserta</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pembimbing.penilaian.index') ? 'active' : '' }}">
                <a href="{{ route('pembimbing.penilaian.index') }}">
                    <i class='bx bx-star'></i>
                    <span>Penilaian</span>
                </a>
            </li>

        </ul>

        <!-- Spacer biar bisa nanti taruh logout di bawah -->
        <div class="mt-auto"></div>

    </div>
</div>
