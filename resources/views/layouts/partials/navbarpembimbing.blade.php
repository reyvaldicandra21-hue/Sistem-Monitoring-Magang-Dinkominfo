<div class="navbar-wrapper">
<div class="navbar-box">

    <div class="d-flex align-items-center gap-2">

        <!-- ☰ -->
        <button id="menuToggle" class="btn d-md-none fs-4">
            <i class='bx bx-menu'></i>
        </button>

        <!-- DATE & TIME -->
        <div class="d-none d-md-flex align-items-center bg-light px-3 py-2 rounded-pill shadow-sm border border-white">
            <i class='bx bx-calendar text-primary me-2'></i>
            <span class="small fw-semibold text-dark text-capitalize">{{ now()->translatedFormat('l, d M Y') }}</span>
            <span class="mx-2 text-muted">|</span>
            <i class='bx bx-time text-primary me-2'></i>
            <span class="small fw-semibold text-dark" id="navClock">--:--</span>
        </div>

    </div>

        <!-- RIGHT SECTION -->
        <div class="nav-right">
            <div class="dropdown">

    <div class="profile-box dropdown-toggle"
         data-bs-toggle="dropdown"
         style="cursor:pointer;">

        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}">

        <div class="profile-info">
            <strong>{{ auth()->user()->name }}</strong>
        </div>

    </div>

    <!-- DROPDOWN -->
    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2"
        style="border-radius:15px; min-width:200px;">

        <!-- USER INFO -->
        <li class="px-3 py-2 border-bottom">
            <div class="fw-semibold">{{ auth()->user()->name }}</div>
            <small class="text-muted">{{ auth()->user()->email }}</small>
        </li>

        <!-- LOGOUT -->
        <li class="mt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="dropdown-item text-danger d-flex align-items-center gap-2">
                    <i class='bx bx-log-out'></i> Logout
                </button>
            </form>
        </li>

    </ul>

</div>

        </div>

    </div>
</div>
